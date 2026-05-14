@extends('layouts.app')

@section('title', 'Contrat de vente - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="bc-item"><a href="{{ route('articles.show', $article) }}">Dossier #{{ $article->numero ?? $article->id }}</a></li>
<li class="bc-item active">Contrat de vente</li>
@endsection

@section('content')
@php
    $cession    = $article->cession;
    $modeType   = $cession?->mode_cession ?? $contractVente->type ?? null;
    $isAdjudication = strtolower((string) $modeType) !== 'appel_doffre' && strtolower((string) $modeType) !== 'appel_offre';
    $statut     = $contractVente->is_validated ? 'Contrat validé' : 'Contrat créé';
    $statutColor = $contractVente->is_validated ? 'green' : 'blue';
@endphp

<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Contrat de vente"
        :subtitle="'Article #' . ($article->numero ?? $article->id)"
        icon="fas fa-file-contract"
        :backRoute="route('articles.show', $article)"
        backText="Retour au dossier"
    />

    <div class="max-w-3xl space-y-6">

        {{-- Status banner --}}
        <div class="rounded-xl border border-{{ $statutColor }}-200 bg-{{ $statutColor }}-50 p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-{{ $contractVente->is_validated ? 'check-circle text-green-600' : 'file-contract text-blue-600' }} text-lg"></i>
                <div>
                    <p class="text-sm font-bold text-{{ $statutColor }}-800">{{ $statut }}</p>
                    @if($contractVente->is_validated)
                        <p class="text-xs text-{{ $statutColor }}-600">Validé le {{ $contractVente->validated_at?->format('d/m/Y \à H:i') }}</p>
                    @else
                        <p class="text-xs text-{{ $statutColor }}-600">Créé le {{ $contractVente->created_at?->format('d/m/Y \à H:i') }}</p>
                    @endif
                </div>
            </div>
            @if(!$contractVente->is_validated)
            <a href="{{ route('contract-ventes.edit', [$article, $contractVente]) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
                <i class="fas fa-edit"></i> Modifier
            </a>
            @endif
        </div>

        {{-- Summary card — issue 7 fields --}}
        <div class="rounded-2xl border bg-white p-6" style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <h3 class="mb-5 flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-gray-500">
                <i class="fas fa-clipboard-list text-emerald-600"></i> Récapitulatif du contrat
            </h3>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-3">

                <div class="rounded-lg bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Type de cession</p>
                    <p class="font-semibold text-gray-800 capitalize">{{ str_replace('_', ' ', $modeType ?? '—') }}</p>
                </div>

                @if(!$isAdjudication)
                <div class="rounded-lg bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Numéro AO</p>
                    <p class="font-semibold text-gray-800">{{ $cession?->numAO ?? $contractVente->numeraAO ?? '—' }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Date AO</p>
                    <p class="font-semibold text-gray-800">{{ $cession?->dateAO?->format('d/m/Y') ?? '—' }}</p>
                </div>
                @else
                <div class="rounded-lg bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Date d'adjudication</p>
                    <p class="font-semibold text-gray-800">{{ ($cession?->DateAdj ?? $contractVente->date_adjudication)?->format('d/m/Y') ?? '—' }}</p>
                </div>
                @endif

                <div class="rounded-lg bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Durée (mois)</p>
                    <p class="font-semibold text-gray-800">{{ $contractVente->duree_decheache ?? '—' }}</p>
                </div>

                <div class="rounded-lg bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Date d'expiration</p>
                    <p class="font-semibold @if($contractVente->date_expiration?->isPast()) text-red-600 @else text-gray-800 @endif">
                        {{ $contractVente->date_expiration?->format('d/m/Y') ?? '—' }}
                    </p>
                </div>

                <div class="rounded-lg bg-emerald-50 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Prix de vente</p>
                    <p class="font-bold text-emerald-700">{{ number_format($contractVente->prix_vente ?? 0, 2, ',', ' ') }} DH</p>
                </div>

                <div class="rounded-lg bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Adjudicataire</p>
                    <p class="font-semibold text-gray-800">{{ $contractVente->exploitant?->nom_complet ?? '—' }}</p>
                </div>

                <div class="rounded-lg bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Nombre de tranches</p>
                    <p class="font-semibold text-gray-800">{{ $contractVente->nombre_tranche ?? '—' }}</p>
                </div>

                @if($contractVente->percepteur)
                <div class="rounded-lg bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Percepteur</p>
                    <p class="font-semibold text-gray-800">{{ $contractVente->percepteur }}</p>
                </div>
                @endif

                <div class="rounded-lg bg-{{ $statutColor }}-50 border border-{{ $statutColor }}-200 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Statut</p>
                    <p class="font-bold text-{{ $statutColor }}-700">{{ $statut }}</p>
                </div>

                @if($contractVente->date_limite_taxes)
                <div class="rounded-lg bg-yellow-50 border border-yellow-200 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Date limite — Taxes</p>
                    <p class="font-semibold text-yellow-800">{{ $contractVente->date_limite_taxes->format('d/m/Y') }}</p>
                </div>
                @endif

                @if($contractVente->date_limite_tranche)
                <div class="rounded-lg bg-purple-50 border border-purple-200 px-4 py-3">
                    <p class="text-xs text-gray-500 mb-1">Date limite — Tranches</p>
                    <p class="font-semibold text-purple-800">{{ $contractVente->date_limite_tranche->format('d/m/Y') }}</p>
                </div>
                @endif

            </div>

            <div class="mt-5 flex gap-3">
                <a href="{{ route('articles.show', $article) }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:border-emerald-300 hover:text-emerald-700 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i> Retour au dossier
                </a>
                <a href="#details"
                    onclick="document.getElementById('details').classList.toggle('hidden')"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-list"></i> Voir détails
                </a>
            </div>
        </div>

        {{-- Full details (collapsed by default, toggled by "Voir détails") --}}
        <div id="details" class="hidden space-y-5">

            {{-- Taxes et charges --}}
            @if($charges->isNotEmpty())
            <div class="rounded-2xl border bg-white p-6" style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-gray-500">
                        <i class="fas fa-calculator text-yellow-600"></i> Taxes et charges
                    </h3>
                    @if($contractVente->date_limite_taxes)
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                        <i class="fas fa-calendar-day"></i> Date limite : {{ $contractVente->date_limite_taxes->format('d/m/Y') }}
                    </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th class="pb-2 pr-4">Désignation</th>
                                <th class="pb-2 pr-4">Montant (DH)</th>
                                <th class="pb-2">Date d'échéance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($charges as $charge)
                            <tr>
                                <td class="py-2 pr-4 font-medium text-gray-800">{{ $charge->nom }}</td>
                                <td class="py-2 pr-4 font-semibold text-emerald-700">{{ number_format($charge->montant ?? 0, 2, ',', ' ') }} DH</td>
                                <td class="py-2 text-gray-600">{{ $charge->date_echeance?->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Tranches --}}
            @if($tranches->isNotEmpty())
            <div class="rounded-2xl border bg-white p-6" style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-gray-500">
                        <i class="fas fa-calendar-alt text-purple-600"></i> Tranches de paiement
                    </h3>
                    @if($contractVente->date_limite_tranche)
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800">
                        <i class="fas fa-calendar-day"></i> Date limite : {{ $contractVente->date_limite_tranche->format('d/m/Y') }}
                    </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th class="pb-2 pr-4">Tranche</th>
                                <th class="pb-2 pr-4">Montant (DH)</th>
                                <th class="pb-2">Date d'échéance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($tranches as $tranche)
                            <tr>
                                <td class="py-2 pr-4 font-medium text-gray-800">{{ $tranche->nom }}</td>
                                <td class="py-2 pr-4 font-semibold text-emerald-700">{{ number_format($tranche->montant ?? 0, 2, ',', ' ') }} DH</td>
                                <td class="py-2 text-gray-600">{{ $tranche->date_echeance?->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>

    </div>
</div>
@endsection
