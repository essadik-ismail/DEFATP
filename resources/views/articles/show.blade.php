@extends('layouts.app')

@section('title', 'Détails de l\'Article - DEFATP')

@section('breadcrumb')
    <li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    <li class="bc-item active">Dossier #{{ $article->numero ?? $article->id }}</li>
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
        'tranchesBlockedReason' => $tranchesBlockedReason ?? null,
    ])

    @push('scripts')
        <script>
            function printPermisEnlever(permisId) {
                const url = '{{ route('articles.show', $article) }}' + '/permis-enlever/' + permisId + '/print';
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
