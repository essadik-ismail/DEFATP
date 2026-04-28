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
     STATUT SPÉCIAL (Délai / Résiliation / Maintenance)
════════════════════════════════════════════════════════════ --}}
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-amber-200 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-amber-500">
                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900">Statut spécial</h3>
            @if($article->statut_special)
                @php
                    $labelMap = ['delai' => 'Délai', 'resiliation' => 'Résiliation', 'maintenance' => 'Maintenance'];
                    $colorMap = ['delai' => 'amber', 'resiliation' => 'red', 'maintenance' => 'blue'];
                    $color = $colorMap[$article->statut_special] ?? 'gray';
                @endphp
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800">
                    {{ $labelMap[$article->statut_special] ?? $article->statut_special }}
                </span>
                @if($article->date_statut_special)
                    <span class="text-xs text-gray-500">depuis le {{ \Carbon\Carbon::parse($article->date_statut_special)->format('d/m/Y') }}</span>
                @endif
            @else
                <span class="text-xs text-gray-400">Aucun statut spécial</span>
            @endif
        </div>

        @if($article->motif_statut_special)
            <p class="text-sm text-gray-600 mb-4 italic">{{ $article->motif_statut_special }}</p>
        @endif

        <form action="{{ route('articles.statut-special', $article) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf @method('PATCH')
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Statut <span class="text-red-500">*</span></label>
                <select name="statut_special" required
                        class="form-select w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">— Choisir —</option>
                    <option value="delai" {{ $article->statut_special === 'delai' ? 'selected' : '' }}>Délai</option>
                    <option value="resiliation" {{ $article->statut_special === 'resiliation' ? 'selected' : '' }}>Résiliation</option>
                    <option value="maintenance" {{ $article->statut_special === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date <span class="text-red-500">*</span></label>
                <input type="date" name="date_statut_special" required
                       value="{{ $article->date_statut_special ? \Carbon\Carbon::parse($article->date_statut_special)->format('Y-m-d') : '' }}"
                       class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Motif</label>
                <input type="text" name="motif_statut_special"
                       value="{{ $article->motif_statut_special }}"
                       placeholder="Motif..."
                       class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white text-sm font-semibold rounded-lg hover:bg-amber-700 transition-colors">
                    <i class="fas fa-save"></i> Appliquer le statut
                </button>
            </div>
        </form>
    </div>

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
