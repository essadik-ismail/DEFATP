@extends('layouts.app')

@section('title', 'Article #' . ($article->numero ?? $article->id) . ' - DEFATP')

@section('breadcrumb')
    <li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    @if ($article->cession)
        <li class="bc-item"><a href="{{ route('cessions.show', $article->cession) }}">Cession #{{ $article->cession->id }}</a></li>
    @endif
    <li class="bc-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
    <li class="bc-item active">Consultation</li>
@endsection

@section('content')
    <div class="min-w-0 max-w-full overflow-x-hidden">

        <x-page-header title="Article #{{ $article->numero ?? $article->id }}" icon="fas fa-eye">
            <x-slot name="actions">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full border border-emerald-200">
                    <i class="fas fa-lock text-emerald-500"></i> Article validé — lecture seule
                </span>
                <x-button href="{{ route('articles.show', $article) }}" variant="secondary" icon="fas fa-arrow-left" size="sm">
                    Retour au dossier
                </x-button>
            </x-slot>
        </x-page-header>

        <div class="space-y-6">

            {{-- 1. Informations générales --}}
            <x-form-section number="1" title="Informations générales" icon="fas fa-info-circle" color="green">
                @php $cession = $article->cession; @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Numéro d'article</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $article->numero ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Numéro du lot</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $article->lot ?? '—' }}</p>
                    </div>
                </div>

                @if ($cession)
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-100">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Type de cession</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $cession->mode_cession === 'appel_offre' ? "Appel d'offre" : 'Adjudication' }}
                            </p>
                        </div>
                        @if ($cession->mode_cession === 'adjudication')
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Date d'adjudication</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $cession->DateAdj ? $cession->DateAdj->format('d/m/Y') : '—' }}</p>
                            </div>
                        @else
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Numéro AO</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $cession->numAO ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Date d'attribution (AO)</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $cession->dateAO ? $cession->dateAO->format('d/m/Y') : '—' }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </x-form-section>

            {{-- 2. Localisation du lot --}}
            <x-form-section number="2" title="Localisation du lot" icon="fas fa-map-marker-alt" color="blue">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Province</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $article->provinces->pluck('nom')->implode(', ') ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Communes</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $article->communes->pluck('nom')->implode(', ') ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">DRANEF</p>
                        <p class="text-sm font-semibold text-gray-900">
                            @if($dranef)
                                {{ $dranef->dranef }} - {{ $dranef->{'Abréviation'} }}
                            @else
                                {{ $article->dranef_code ?? '—' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">DPANEF</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $dpanef?->dpanef ?? $article->dpanef_code ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">ZDTF</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $zdtf?->zdtf ?? $article->zdtf_code ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">DFP</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $dfp?->dfp ?? $article->dfp_code ?? '—' }}</p>
                    </div>
                </div>
            </x-form-section>

            {{-- 3. Informations forestières --}}
            <x-form-section number="3" title="Informations forestières" icon="fas fa-tree" color="purple">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Forêt(s)</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $article->forets->pluck('foret')->implode(', ') ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Canton</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $article->canton ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Parcelle</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $article->parcelle ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nature juridique</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $article->nature_juridique ?? '—' }}</p>
                    </div>
                </div>
            </x-form-section>

            {{-- 4. Description du lot --}}
            <x-form-section number="4" title="Description du lot" icon="fas fa-clipboard-list" color="orange">

                <h4 class="text-sm font-semibold text-gray-700 mb-3">Limites du lot</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @foreach ([
                        'limite_nord'  => 'Limite Nord',
                        'limite_sud'   => 'Limite Sud',
                        'limite_est'   => 'Limite Est',
                        'limite_ouest' => 'Limite Ouest',
                        'limite_ne'    => 'Limite Nord-Est',
                        'limite_no'    => 'Limite Nord-Ouest',
                        'limite_se'    => 'Limite Sud-Est',
                        'limite_so'    => 'Limite Sud-Ouest',
                    ] as $field => $label)
                        <div class="bg-gray-50 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">{{ $label }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $article->$field ?? '—' }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-gray-100 mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Coordonnées du centre</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Coordonnée X</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $article->coordonnee_x ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Coordonnée Y</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $article->coordonnee_y ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nature de coupe</p>
                        @if($article->natureDeCoupes->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($article->natureDeCoupes as $ndc)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                        {{ $ndc->nature_de_coupe }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">—</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mode d'exploitation</p>
                        @if($article->modeExploitations->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($article->modeExploitations as $me)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                        {{ $me->mode_exploiattion }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">—</p>
                        @endif
                    </div>
                </div>

                @if($article->depots->isNotEmpty())
                    <div class="pt-4 border-t border-gray-100 mt-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Dépôts</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($article->depots as $depot)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ $depot->nom }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </x-form-section>

            {{-- 5. Consistance du lot --}}
            <x-form-section number="5" title="Consistance du lot" icon="fas fa-cubes" color="gray">
                <div class="mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Superficie</p>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ $article->superficie ? number_format($article->superficie, 2, ',', ' ') . ' ha' : '—' }}
                    </p>
                </div>

                @if($article->essences->isNotEmpty())
                    <div class="pt-4 border-t border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Produits présumés</h4>
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Essence</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produit</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Volume / Quantité</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($article->essences as $essence)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 font-medium text-gray-800">{{ $essence->essence }}</td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $article->products->firstWhere('id', $essence->pivot->product_id)?->name ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $essence->pivot->quantity ? number_format($essence->pivot->quantity, 2, ',', ' ') : '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </x-form-section>

            {{-- 6. Charges --}}
            <x-form-section number="6" title="Charges" icon="fas fa-file-invoice-dollar" color="red">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Taxe de réfection de chemin (DH)</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $article->taxe_refection_chemins ? number_format($article->taxe_refection_chemins, 2, ',', ' ') . ' DH' : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Échéancier – Taxe réfection chemin</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $article->date_echeance_taxe_refection_chemins ? $article->date_echeance_taxe_refection_chemins->format('d/m/Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Service rendu par l'ANEF (DH)</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $article->service_rendu_anef ? number_format($article->service_rendu_anef, 2, ',', ' ') . ' DH' : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Échéancier – Service rendu ANEF</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $article->date_echeance_service_rendu_anef ? $article->date_echeance_service_rendu_anef->format('d/m/Y') : '—' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Volume bois de chauffage</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Volume (m³)</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $article->bois_chauffage_volume ? number_format($article->bois_chauffage_volume, 2, ',', ' ') . ' m³' : '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Destination</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $article->bois_chauffage_destination ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Date de livraison</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $article->date_livraison_bois_chauffage ? $article->date_livraison_bois_chauffage->format('d/m/Y') : '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-form-section>

            {{-- 7. Particulière --}}
            @if($article->particuliere)
                <x-form-section number="7" title="Particulière" icon="fas fa-file-alt" color="green">
                    <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $article->particuliere }}</p>
                </x-form-section>
            @endif

        </div>
    </div>
@endsection
