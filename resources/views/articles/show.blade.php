@extends('layouts.app')

@section('title', 'Détails de l\'Article - DEFATP')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    <li class="breadcrumb-item active">Dossier #{{ $article->numero ?? $article->id }}</li>
@endsection

@section('content')
    @php
        $hasPaidTranches = false;
        $permisExploiter = $contractVente?->permisExploiter;
        if ($contractVente && $contractVente->chargeApayer) {
            foreach ($contractVente->chargeApayer as $charge) {
                if (str_starts_with($charge->nom, 'Tranche')) {
                    $payment = $charge->payments->first();
                    if ($payment && $payment->is_paye && $payment->date_payment) {
                        $hasPaidTranches = true;
                        break;
                    }
                }
            }
        }

        $currentStep = $article->current_step ?? 'cahier_affiche';
        $documentStepOrder = [
            'cahier_affiche',
            'contrat_vente',
            'paiement_charges',
            'paiement_tranches',
            'recollement',
            'main_levee',
        ];
        $currentStepIndex = array_search($currentStep, $documentStepOrder);

        $showLettreAdjudicataire = $contractVente && $currentStepIndex >= 1;
        $showPermisExploiter = $hasPaidTranches && $currentStepIndex >= 2;
        $showPVInstallation = $hasPaidTranches && $currentStepIndex >= 2;
        $showPermisEnlever = $hasPaidTranches && $currentStepIndex >= 3;
        $showPermisColportage = $hasPaidTranches && $currentStepIndex >= 3 && $permisEnlevers->isNotEmpty();
        $hasAnyDocument =
            $showLettreAdjudicataire ||
            $showPermisExploiter ||
            $showPVInstallation ||
            $showPermisEnlever ||
            $showPermisColportage;

        $wfLabel =
            \App\Services\ArticleWorkflowService::LABELS[$article->workflow_state ?? 'DRAFT_ARTICLE'] ??
            ($article->workflow_state ?? '—');
    @endphp

    {{-- ═══════════════════════════════════════════════════════════
     ALL-STEPS ACCORDION
════════════════════════════════════════════════════════════ --}}
    @include('workflow.all-steps-accordion', [
        'article' => $article,
        'contractVente' => $contractVente,
        'permisEnlevers' => $permisEnlevers,
        'exploitants' => $exploitants,
        'steps' => $steps,
        'alerts' => $alerts,
    ])

    {{-- ═══════════════════════════════════════════════════════════
     DÉNOMBREMENT (accès rapide)
════════════════════════════════════════════════════════════ --}}
    @if($contractVente)
    <div class="mt-4 bg-white rounded-xl shadow-sm border border-teal-200 p-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-teal-600">
                <i class="fas fa-clipboard-list text-white text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Dénombrement</p>
                @php
                    $denom = \App\Models\Denombrement::where('contract_vente_id', $contractVente->id)->first();
                @endphp
                @if($denom)
                    <p class="text-xs text-teal-700">Enregistré le {{ $denom->date_denombrement?->format('d/m/Y') }} — {{ $denom->volume_denombre ? number_format($denom->volume_denombre, 3) . ' m³' : 'Volume non renseigné' }}</p>
                @else
                    <p class="text-xs text-gray-400">Aucun dénombrement saisi</p>
                @endif
            </div>
        </div>
        <a href="{{ route('articles.denombrement', $article) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white text-sm font-semibold rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-{{ $denom ?? false ? 'edit' : 'plus' }}"></i>
            {{ ($denom ?? false) ? 'Modifier' : 'Saisir' }}
        </a>
    </div>
    @endif

    @push('scripts')
        <script>
            function printPermisEnlever(permisId) {
                const url = '{{ route('articles.permis-enlever', $article) }}?print=' + permisId;
                const printWindow = window.open(url, '_blank');
                if (printWindow) {
                    printWindow.onload = function() {
                        setTimeout(function() {
                            printWindow.print();
                        }, 500);
                    };
                }
            }
        </script>
    @endpush
@endsection
