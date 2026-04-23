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
        $showPermisColportage = $hasPaidTranches && $currentStepIndex >= 3;
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
     FLASH MESSAGES
════════════════════════════════════════════════════════════ --}}
    @if (session('success'))
        <x-alert type="success" title="Succès !" dismissible class="mb-4">{{ session('success') }}</x-alert>
    @endif
    @if (session('error'))
        <x-alert type="error" title="Erreur !" dismissible class="mb-4">{{ session('error') }}</x-alert>
    @endif

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
