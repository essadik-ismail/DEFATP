@extends('layouts.app')

@section('title', 'Détail Permis d\'Enlever - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="bc-item"><a href="{{ route('articles.show', $article) }}">Détail #{{ $article->numero ?? $article->id }}</a></li>
<li class="bc-item active">Permis d'enlever</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-7xl">

        <x-page-header
            title="Permis d'Enlever"
            :subtitle="($permiEnlever->num_quittance ? 'Quittance n° ' . $permiEnlever->num_quittance . ' — ' : '') . 'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-file-contract"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        @if($isReadOnly)
        <div class="mb-4 flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            <i class="fas fa-lock"></i>
            <span>Cette page est en lecture seule : l'étape a été validée.</span>
        </div>
        @endif

        {{-- Action buttons --}}
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <a href="{{ route('articles.print-permis-enlever', ['article' => $article, 'permiEnlever' => $permiEnlever]) }}"
               target="_blank"
               class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 transition-colors">
                <i class="fas fa-print"></i>
                Imprimer permis d'enlever
            </a>

            @if(!$isReadOnly)
            @if($permiEnlever->fichier_permis_signe)
                <a href="{{ asset('storage/' . $permiEnlever->fichier_permis_signe) }}" target="_blank"
                   class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-file-signature"></i>
                    Voir version signée
                </a>
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-amber-600 transition-colors">
                    <i class="fas fa-sync-alt"></i>
                    Remplacer version signée
                    <form action="{{ route('articles.permis-enlever.upload-signe', ['article' => $article, 'permiEnlever' => $permiEnlever]) }}"
                          method="POST" enctype="multipart/form-data" class="hidden" id="upload-signe-form">
                        @csrf
                        <input type="file" name="fichier_permis_signe" accept=".pdf,.jpg,.jpeg,.png"
                               onchange="document.getElementById('upload-signe-form').submit()">
                    </form>
                </label>
            @else
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-amber-600 transition-colors">
                    <i class="fas fa-upload"></i>
                    Uploader version signée
                    <form action="{{ route('articles.permis-enlever.upload-signe', ['article' => $article, 'permiEnlever' => $permiEnlever]) }}"
                          method="POST" enctype="multipart/form-data" class="hidden" id="upload-signe-form">
                        @csrf
                        <input type="file" name="fichier_permis_signe" accept=".pdf,.jpg,.jpeg,.png"
                               onchange="document.getElementById('upload-signe-form').submit()">
                    </form>
                </label>
            @endif
            @elseif($permiEnlever->fichier_permis_signe)
                <a href="{{ asset('storage/' . $permiEnlever->fichier_permis_signe) }}" target="_blank"
                   class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-file-signature"></i>
                    Voir version signée
                </a>
            @endif
        </div>

        {{-- Volume table --}}
        <div class="mb-6 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="flex items-center gap-3 text-lg font-semibold" style="color:#1F2D24;">
                    <i class="fas fa-tree" style="color:#6B7C72;"></i>
                    Volumes autorisés et consommation
                </h2>
            </div>
            <div class="p-0">
                @if($volumeRows->isEmpty())
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-info-circle mb-2 text-3xl text-gray-300"></i>
                        <p>Aucun volume enregistré pour ce permis.</p>
                    </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b-2 border-green-200 bg-green-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-green-800">Essence</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-green-800">Produit</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-green-800">Vol. autorisé</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-green-800">Consommation</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-green-800">Volume restant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-green-100 bg-white">
                            @foreach($volumeRows as $row)
                            <tr class="hover:bg-green-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $row['essence_name'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $row['product_name'] }}</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                                    {{ number_format($row['authorized'], 2, ',', ' ') }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm">
                                    @if($row['consumed'] > 0)
                                        <span class="font-medium text-orange-600">{{ number_format($row['consumed'], 2, ',', ' ') }}</span>
                                    @else
                                        <span class="text-gray-400">0,00</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-bold">
                                    @if($row['remaining'] <= 0)
                                        <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">Épuisé</span>
                                    @else
                                        <span class="text-green-700">{{ number_format($row['remaining'], 2, ',', ' ') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- Permis de colportage section --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="flex items-center gap-3 text-lg font-semibold" style="color:#1F2D24;">
                    <i class="fas fa-truck" style="color:#6B7C72;"></i>
                    Permis de colportage liés
                    <span class="ml-1 rounded-full bg-green-100 px-2 py-0.5 text-sm font-bold text-green-800">{{ $colportages->count() }}</span>
                </h2>

                @if(!$isReadOnly)
                @if($allConsumed)
                    <span class="inline-flex cursor-not-allowed items-center gap-2 rounded-lg bg-gray-300 px-4 py-2 text-sm font-medium text-gray-500"
                          title="Tout le volume est consommé">
                        <i class="fas fa-plus"></i>
                        Créer permis de colportage
                    </span>
                @else
                    <a href="{{ route('articles.permis-colportage.create', ['article' => $article, 'permis_enlever_id' => $permiEnlever->id]) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-700 transition-colors">
                        <i class="fas fa-plus"></i>
                        Créer permis de colportage
                    </a>
                @endif
                @endif
            </div>

            <div class="p-0">
                @if($colportages->isEmpty())
                    <x-empty-state
                        icon="fas fa-truck"
                        title="Aucun permis de colportage"
                        message="Aucun permis de colportage n'a encore été créé pour ce permis d'enlever."
                        color="purple"
                    />
                @else
                <div class="overflow-x-auto">
                    <table class="w-full" id="colportage-table">
                        <thead class="border-b-2 border-purple-200 bg-purple-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-800">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-800">N° Carnet</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-800">Dates</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-800">Véhicule</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-800">Chauffeur</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-800">Destination</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-purple-800">Volume</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-purple-800">Nuit</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-800">Essence(s)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-purple-100 bg-white">
                            @foreach($colportages as $index => $colportage)
                            <tr class="hover:bg-purple-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-semibold text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    @if($colportage->carnet)
                                        <span class="font-mono font-semibold text-purple-800">{{ $colportage->carnet->serie }}-{{ $colportage->carnet->num }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($colportage->date_debut)
                                        <span>{{ \Carbon\Carbon::parse($colportage->date_debut)->format('d/m/Y') }}</span>
                                        @if($colportage->date_fin)
                                            <span class="text-gray-400"> → </span>
                                            <span>{{ \Carbon\Carbon::parse($colportage->date_fin)->format('d/m/Y') }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-mono text-gray-900">{{ $colportage->vehicule_immatriculation ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <div>{{ $colportage->chauffeur_nom ?? '—' }}</div>
                                    @if($colportage->chauffeur_cin)
                                        <div class="text-xs text-gray-500">CIN: {{ $colportage->chauffeur_cin }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $colportage->destination ?? '—' }}</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                                    @php
                                        $totalVol = collect($colportage->detail_rows ?? [])->sum('quantity');
                                    @endphp
                                    {{ $totalVol > 0 ? number_format($totalVol, 2, ',', ' ') : '—' }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    @if($colportage->transport_nuit)
                                        <span class="inline-flex items-center justify-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-semibold text-indigo-700">
                                            <i class="fas fa-moon mr-1 text-xs"></i> Oui
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Non</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @forelse(collect($colportage->detail_rows ?? []) as $dr)
                                        <div class="text-xs">
                                            <span class="font-medium">{{ $dr['essence_name'] ?? '—' }}</span>
                                            @if(!empty($dr['product_name']) && $dr['product_name'] !== '-')
                                                <span class="text-gray-400"> / {{ $dr['product_name'] }}</span>
                                            @endif
                                            <span class="ml-1 text-gray-600">{{ number_format($dr['quantity'], 2, ',', ' ') }}</span>
                                        </div>
                                    @empty
                                        <span class="text-gray-400">—</span>
                                    @endforelse
                                </td>
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
@endsection
