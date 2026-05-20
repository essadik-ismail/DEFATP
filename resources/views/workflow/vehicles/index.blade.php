@extends('layouts.app')

@section('title', 'Véhicules déclarés - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
<li class="bc-item active">Véhicules</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-5xl">

        <x-page-header
            title="Véhicules déclarés"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-truck"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        @if(session('success'))
            <div class="mb-4 flex items-center gap-2 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-800">
                <i class="fas fa-check-circle text-emerald-600"></i>
                {{ session('success') }}
            </div>
        @endif

        @can('vehicle.declare')
        {{-- Search / Add Section --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-search text-green-600"></i>
                Rechercher ou ajouter un véhicule
            </h2>

            {{-- Search bar --}}
            <div class="flex gap-2 mb-4">
                <input type="text" id="search-immat"
                       placeholder="Immatriculation (ex: 12345-A-6)"
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       autocomplete="off">
                <button id="btn-search"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </div>

            {{-- Search result area --}}
            <div id="search-result" class="hidden">

                {{-- Found: show vehicle info + attach button --}}
                <div id="result-found" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-blue-800 mb-1 flex items-center gap-1">
                                <i class="fas fa-truck text-blue-600"></i>
                                Véhicule trouvé
                            </p>
                            <dl class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-1 text-sm text-gray-700">
                                <div><span class="text-gray-500">Immatriculation :</span> <span id="r-immat" class="font-medium"></span></div>
                                <div><span class="text-gray-500">Marque :</span> <span id="r-marque"></span></div>
                                <div><span class="text-gray-500">Capacité :</span> <span id="r-capacite"></span></div>
                                <div><span class="text-gray-500">Chauffeur :</span> <span id="r-chauffeur"></span></div>
                                <div><span class="text-gray-500">CIN :</span> <span id="r-cin"></span></div>
                            </dl>
                        </div>
                        <div id="attach-actions" class="shrink-0"></div>
                    </div>
                </div>

                {{-- Not found: show create form --}}
                <div id="result-not-found" class="hidden">
                    <div class="flex items-center gap-2 mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
                        <i class="fas fa-exclamation-triangle text-amber-500"></i>
                        Aucun véhicule trouvé avec cette immatriculation. Remplissez le formulaire pour le créer et le lier à l'article.
                    </div>

                    <form action="{{ route('vehicles.store', $article) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" id="form-immat-hidden" name="immatriculation">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Immatriculation <span class="text-red-500">*</span></label>
                                <input type="text" id="form-immat-display"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
                                       readonly>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Marque</label>
                                <input type="text" name="marque" maxlength="100"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Capacité</label>
                                <input type="number" name="capacite" step="0.01" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Unité <span class="text-red-500">*</span></label>
                                <select name="capacite_unite"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    <option value="m3">m³</option>
                                    <option value="stere">Stère</option>
                                    <option value="sacs">Sacs</option>
                                    <option value="tonnes">Tonnes</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" id="btn-cancel-create"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-times"></i> Annuler
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-save"></i> Créer et lier
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        @endcan

        {{-- Linked vehicles list --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-list text-gray-500"></i>
                    Véhicules liés à cet article
                    <span class="ml-1 text-sm font-normal text-gray-500">({{ $vehicles->count() }})</span>
                </h2>
            </div>

            <div class="p-6">
                @if($vehicles->isEmpty())
                    <x-empty-state
                        icon="fas fa-truck"
                        title="Aucun véhicule lié"
                        message="Aucun véhicule n'a encore été lié à cet article."
                        color="green"
                    />
                @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-green-50 border-b-2 border-green-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Immatriculation</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Marque</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Capacité</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Chauffeur</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Lié par</th>
                                @can('vehicle.declare')
                                <th class="px-4 py-3 text-center text-xs font-semibold text-green-800 uppercase">Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-green-100">
                            @foreach($vehicles as $i => $vehicle)
                            <tr class="hover:bg-green-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-semibold text-green-900">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $vehicle->immatriculation }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $vehicle->marque ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($vehicle->capacite)
                                        {{ number_format($vehicle->capacite, 2) }} {{ $vehicle->capacite_unite }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $vehicle->chauffeur_nom ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $vehicle->declaredBy?->name ?? '—' }}</td>
                                @can('vehicle.declare')
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('vehicles.destroy', [$article, $vehicle]) }}" method="POST"
                                          onsubmit="return confirm('Retirer ce véhicule de l\'article ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition-colors"
                                                title="Retirer de l'article">
                                            <i class="fas fa-unlink text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@can('vehicle.declare')
<script>
(function () {
    const searchInput  = document.getElementById('search-immat');
    const btnSearch    = document.getElementById('btn-search');
    const resultArea   = document.getElementById('search-result');
    const resultFound  = document.getElementById('result-found');
    const resultNF     = document.getElementById('result-not-found');
    const attachActions= document.getElementById('attach-actions');
    const formImmatH   = document.getElementById('form-immat-hidden');
    const formImmatD   = document.getElementById('form-immat-display');
    const btnCancel    = document.getElementById('btn-cancel-create');

    const searchUrl    = "{{ route('vehicles.search', $article) }}";
    const attachUrl    = "{{ route('vehicles.attach', $article) }}";
    const csrfToken    = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    function reset() {
        resultArea.classList.add('hidden');
        resultFound.classList.add('hidden');
        resultNF.classList.add('hidden');
    }

    btnSearch.addEventListener('click', doSearch);
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
    });

    btnCancel.addEventListener('click', function () {
        reset();
        searchInput.value = '';
    });

    function doSearch() {
        const immat = searchInput.value.trim();
        if (!immat) return;

        btnSearch.disabled = true;
        btnSearch.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Recherche...';

        fetch(searchUrl + '?immatriculation=' + encodeURIComponent(immat), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            resultArea.classList.remove('hidden');
            resultFound.classList.add('hidden');
            resultNF.classList.add('hidden');

            if (data.found) {
                const v = data.vehicle;
                document.getElementById('r-immat').textContent     = v.immatriculation;
                document.getElementById('r-marque').textContent    = v.marque || '—';
                document.getElementById('r-capacite').textContent  = v.capacite ? v.capacite + ' ' + v.capacite_unite : '—';
                document.getElementById('r-chauffeur').textContent = v.chauffeur_nom || '—';
                document.getElementById('r-cin').textContent       = v.chauffeur_cin || '—';

                if (data.already_linked) {
                    attachActions.innerHTML = '<span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-500 rounded-lg"><i class="fas fa-check"></i> Déjà lié</span>';
                } else {
                    attachActions.innerHTML = `
                        <form method="POST" action="${attachUrl}">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="vehicle_id" value="${v.id}">
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-link"></i> Lier à l'article
                            </button>
                        </form>`;
                }

                resultFound.classList.remove('hidden');
            } else {
                formImmatH.value = immat;
                formImmatD.value = immat;
                resultNF.classList.remove('hidden');
            }
        })
        .catch(() => {
            alert('Erreur lors de la recherche. Veuillez réessayer.');
        })
        .finally(() => {
            btnSearch.disabled = false;
            btnSearch.innerHTML = '<i class="fas fa-search"></i> Rechercher';
        });
    }
})();
</script>
@endcan
@endsection
