{{--
  Multi-Step Workflow for article show page.
  Variables: $article, $contractVente, $permisEnlevers, $exploitants, $steps, $alerts
--}}
@php
use App\Services\ArticleWorkflowService as WF;
use App\Enums\Permission as P;

$wfState = $article->workflow_state ?? WF::DRAFT_ARTICLE;

$chargesAll   = $contractVente?->chargeApayer ?? collect();
$charges      = $chargesAll->filter(fn($c) => !str_starts_with($c->nom, 'Tranche'))->values();
$tranches     = $chargesAll->filter(fn($c) => str_starts_with($c->nom, 'Tranche'))
                    ->sortBy(fn($c) => (int) preg_replace('/\D/', '', $c->nom))
                    ->values();

$cautionCharge = $charges->first(fn($c) => str_contains(strtolower($c->nom), 'caution') || str_contains(strtolower($c->nom), 'cautionnement'));
$taxeCharges   = $charges->filter(fn($c) => !str_contains(strtolower($c->nom), 'caution') && !str_contains(strtolower($c->nom), 'cautionnement'));

$cautionPaid   = $cautionCharge?->payments?->first()?->is_paye ?? false;
$allTaxesPaid  = $taxeCharges->filter(fn($c) => !str_contains(strtolower($c->nom), 'anef'))->every(fn($c) => $c->payments->first()?->is_paye);

$stateOrder     = WF::STATE_ORDER;
$taxesValidated = ($stateOrder[$wfState] ?? 0) >= ($stateOrder[WF::TAXES_PAID] ?? 5);
$cautionValidated = ($stateOrder[$wfState] ?? 0) >= ($stateOrder[WF::CAUTION_PAID] ?? 4);

$permisExploiter     = $contractVente?->permisExploiter;
$pvInstallation      = $contractVente?->pvInstallations?->first();
$vehicleDeclarations = $contractVente?->vehicleDeclarations ?? collect();
$prorogations        = $contractVente?->prorogations ?? collect();
$denombrements       = $contractVente?->denombrements ?? collect();
$recolement          = $contractVente?->recolement;

// 11-step definitions matching WF::LABELS order
$allStepDefs = [
    WF::DRAFT_ARTICLE        => ['icon' => 'fa-pencil-alt',      'title' => "Création de l'article",  'badge' => '1'],
    WF::CONTRACT_CREATED     => ['icon' => 'fa-file-contract',   'title' => 'Contrat de vente',        'badge' => '2'],
    WF::LETTER_SIGNED_UPLOADED => ['icon' => 'fa-file-signature','title' => 'Lettre adjudicataire',    'badge' => '3'],
    WF::CAUTION_PAID         => ['icon' => 'fa-shield-alt',      'title' => 'Paiement caution',        'badge' => '4'],
    WF::TAXES_PAID           => ['icon' => 'fa-money-bill-wave', 'title' => 'Paiement des taxes',      'badge' => '5'],
    WF::PERMIT_ISSUED        => ['icon' => 'fa-stamp',           'title' => "Permis d'exploiter",      'badge' => '6'],
    WF::PV_INSTALLATION_DONE => ['icon' => 'fa-clipboard-check', 'title' => "PV d'installation",       'badge' => '7'],
    WF::TRANCHES_IN_PROGRESS => ['icon' => 'fa-credit-card',     'title' => 'Paiement des tranches',   'badge' => '8'],
    WF::RECOLEMENT_PENDING   => ['icon' => 'fa-clipboard-list',  'title' => 'Récolement',              'badge' => '9'],
    WF::MAINLEVEE_DONE       => ['icon' => 'fa-unlock',          'title' => 'Mainlevée',               'badge' => '10'],
    WF::CLOSED               => ['icon' => 'fa-archive',         'title' => 'Clôture',                 'badge' => '11'],
];

$permisEnleverList = $permisEnlevers ?? collect();

// $steps already contains exactly the 12 LABELS states from getStepStatuses()
$displaySteps = $steps;

// Map each step to the permission required to see it
$stepPermissions = [
    WF::DRAFT_ARTICLE          => P::ARTICLE_VIEW,
    WF::CONTRACT_CREATED       => P::CONTRACT_SALE_VIEW,
    WF::LETTER_SIGNED_UPLOADED => P::ADJUDICATAIRE_LETTER_VIEW,
    WF::CAUTION_PAID           => P::CAUTION_PAYMENT_VIEW,
    WF::TAXES_PAID             => P::TAX_PAYMENT_VIEW,
    WF::PERMIT_ISSUED          => P::OPERATING_PERMIT_VIEW,
    WF::PV_INSTALLATION_DONE   => P::INSTALLATION_REPORT_VIEW,
    WF::TRANCHES_IN_PROGRESS   => P::INSTALLMENT_PAYMENT_VIEW,
    WF::RECOLEMENT_PENDING     => P::RECOLEMENT_REPORT_VIEW,
    WF::MAINLEVEE_DONE         => P::RELEASE_VIEW,
    WF::CLOSED                 => P::CESSION_VIEW,
];

$visibleStepDefs = collect($allStepDefs)
    ->filter(fn($def, $state) => auth()->user()->can($stepPermissions[$state] ?? P::CESSION_VIEW))
    ->all();

// Map each step to the permission required to perform write actions on it
$stepWritePermissions = [
    WF::DRAFT_ARTICLE          => P::ARTICLE_UPDATE,
    WF::CONTRACT_CREATED       => P::CONTRACT_SALE_UPDATE,
    WF::LETTER_SIGNED_UPLOADED => P::ADJUDICATAIRE_LETTER_UPLOAD_SIGNED,
    WF::CAUTION_PAID           => P::CAUTION_PAYMENT_UPDATE,
    WF::TAXES_PAID             => P::TAX_PAYMENT_UPDATE,
    WF::PERMIT_ISSUED          => P::OPERATING_PERMIT_UPDATE,
    WF::PV_INSTALLATION_DONE   => P::INSTALLATION_REPORT_UPDATE,
    WF::TRANCHES_IN_PROGRESS   => P::INSTALLMENT_PAYMENT_UPDATE,
    WF::RECOLEMENT_PENDING     => P::RECOLEMENT_REPORT_UPDATE,
    WF::MAINLEVEE_DONE         => P::RELEASE_CREATE,
    WF::CLOSED                 => P::CESSION_CLOSE,
];

// For initial panel: open the first active step, falling back to first blocked or step 1
$stateKeys = array_keys($visibleStepDefs);
$initialVisibleState = collect($stateKeys)->first(fn($s) => ($displaySteps[$s]['status'] ?? null) === 'active');

// When article is validated (ARTICLE_READY) but no contract yet, open CONTRACT_CREATED panel
if (!$initialVisibleState && $wfState === WF::ARTICLE_READY) {
    $initialVisibleState = WF::CONTRACT_CREATED;
}

if (!$initialVisibleState) {
    $initialVisibleState = collect($stateKeys)->first(fn($s) => ($displaySteps[$s]['status'] ?? null) === 'pending');
}
if (!$initialVisibleState) {
    $initialVisibleState = collect($stateKeys)->first(fn($s) => ($displaySteps[$s]['status'] ?? null) === 'blocked');
}
if (!$initialVisibleState || !isset($visibleStepDefs[$initialVisibleState])) {
    $initialVisibleState = array_key_first($visibleStepDefs);
}

$articleValidated = in_array($wfState, [WF::ARTICLE_READY, ...array_slice(array_keys(WF::LABELS), 1)], true);
@endphp

<div x-data="{ active: '{{ $initialVisibleState }}' }" class="mb-8">

    {{-- �f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,
         STEP RAIL
    �f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�, --}}
    <div class="bg-white rounded-2xl border overflow-hidden mb-4" style="border-color:rgba(154,179,163,0.35);box-shadow:0 1px 6px rgba(0,0,0,0.06);">

        {{-- Rail header --}}
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-3">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#059669,#047857);">
                <i class="fas fa-stream text-white text-xs"></i>
            </div>
            <h2 class="text-sm font-bold text-gray-800">Suivi du dossier</h2>
            <span class="ml-auto text-xs text-gray-400">Cliquez sur une étape pour la consulter</span>
        </div>

        {{-- Scrollable step pills --}}
        <div class="workflow-step-rail overflow-x-auto px-4 py-4" style="scrollbar-width:thin;">
            {{-- Scroll hint shadow (right side) ? CSS trick via gradient overlay --}}
            <ol class="flex items-start gap-0 min-w-max">
                @foreach($visibleStepDefs as $state => $def)
                    @php
                        $stepInfo  = $displaySteps[$state] ?? ['status' => 'blocked', 'label' => $def['title'], 'blocked_reason' => null];
                        $status    = $stepInfo['status'];
                        $isLast    = $state === array_key_last($allStepDefs);
                    @endphp
                    <li class="flex items-center">
                        {{-- Step button --}}
                        <button type="button"
                                @click="active = '{{ $state }}'"
                                class="flex flex-col items-center gap-1 group focus:outline-none"
                                style="min-width:72px;">

                            {{-- Circle --}}
                            <span class="relative flex items-center justify-center w-9 h-9 rounded-full border-2 transition-all duration-200
                                @if($status === 'done')    border-emerald-500 bg-emerald-500
                                @elseif($status === 'active') border-blue-500 bg-blue-500
                                @elseif($status === 'blocked') border-gray-200 bg-gray-100
                                @else border-gray-200 bg-gray-100 @endif"
                                 :class="active === '{{ $state }}' ? 'ring-2 ring-offset-2 ring-blue-400 scale-110' : 'group-hover:scale-105'">

                                @if($status === 'done')
                                    <i class="fas fa-check text-white text-xs"></i>
                                @elseif($status === 'active')
                                    <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                @else
                                    <span class="text-xs font-bold text-gray-400">{{ $def['badge'] }}</span>
                                @endif
                            </span>

                            {{-- Label --}}
                            <span class="step-label text-center leading-tight transition-colors duration-200 px-1"
                                  style="font-size:10px;max-width:72px;word-break:break-word;"
                                  :class="active === '{{ $state }}'
                                      ? 'font-bold text-blue-700'
                                      : '@if($status==='done') text-emerald-700 @elseif($status==='active') text-blue-600 @else text-gray-400 @endif'">
                                @if($status === 'done')
                                    <span class="text-emerald-700">{{ $def['title'] }}</span>
                                @elseif($status === 'active')
                                    <span class="text-blue-600 font-semibold">{{ $def['title'] }}</span>
                                @else
                                    <span class="text-gray-400">{{ $def['title'] }}</span>
                                @endif
                            </span>
                        </button>

                        {{-- Connector line --}}
                        @if(!$isLast)
                            <div class="w-8 h-0.5 flex-shrink-0 mt-[-18px]
                                @if($status === 'done') bg-emerald-400 @else bg-gray-200 @endif">
                            </div>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </div>

    {{-- �f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,
         STEP CONTENT PANEL
    �f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�,�f�'�,¢�f¢â�?s¬�,¢�f�?s�, --}}
    @foreach($visibleStepDefs as $state => $def)
        @php
            $stepInfo       = $displaySteps[$state] ?? ['status' => 'blocked', 'label' => $def['title'], 'blocked_reason' => null];
            $status         = $stepInfo['status'];
            $isDone         = $status === 'done';
            $isActive       = $status === 'active';
            $isBlocked      = $status === 'blocked';
        @endphp

        <div x-show="active === '{{ $state }}'"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="workflow-panel bg-white rounded-2xl border overflow-hidden"
             style="display:none;border-color:rgba(154,179,163,0.35);box-shadow:0 1px 6px rgba(0,0,0,0.06);">

            {{-- Panel header --}}
            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100 flex flex-wrap items-center gap-3"
                 style="@if($isDone) background:rgba(240,253,244,0.6) @elseif($isActive) background:rgba(239,246,255,0.8) @else background:rgba(249,250,251,0.5) @endif">

                <span class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                      style="@if($isDone) background:linear-gradient(135deg,#059669,#047857) @elseif($isActive) background:linear-gradient(135deg,#2563eb,#1d4ed8) @else background:#e5e7eb @endif">
                    <i class="fas {{ $def['icon'] }} text-sm @if($isDone || $isActive) text-white @else text-gray-400 @endif"></i>
                </span>

                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold
                            @if($isDone) text-emerald-800
                            @elseif($isActive) text-blue-800
                            @else text-gray-500 @endif">
                            Étape {{ $def['badge'] }} — {{ $def['title'] }}
                        </h3>
                        @if($isActive)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>En cours
                            </span>
                        @elseif($isDone)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                <i class="fas fa-check text-xs"></i>Terminé
                            </span>
                        @elseif($isBlocked && $stepInfo['blocked_reason'])
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                <i class="fas fa-lock text-xs"></i>Bloqué
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full">
                                En attente
                            </span>
                        @endif
                    </div>
                    @if($isBlocked && $stepInfo['blocked_reason'])
                        <p class="text-xs text-amber-600 mt-0.5">{{ $stepInfo['blocked_reason'] }}</p>
                    @endif
                </div>

                {{-- Prev / Next navigation --}}
                <div class="flex items-center gap-2 flex-shrink-0 ml-auto">
                    @php $stateKeys = array_keys($visibleStepDefs); $idx = array_search($state, $stateKeys); @endphp
                    @if($idx > 0)
                        <button @click="active = '{{ $stateKeys[$idx - 1] }}'"
                                class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors focus:outline-none"
                                title="Étape précédente">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                    @endif
                    @if($idx < count($stateKeys) - 1)
                        <button @click="active = '{{ $stateKeys[$idx + 1] }}'"
                                class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors focus:outline-none"
                                title="Étape suivante">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Panel body --}}
            <div class="p-4 sm:p-5">
                @php
                    $validateBlocked = false;
                    $validateBlockedReason = null;
                    $canWriteStep = auth()->user()->can($stepWritePermissions[$state] ?? P::CESSION_VIEW);
                @endphp

                @if($isBlocked)
                    <div class="flex items-center gap-3 py-4 px-3 bg-gray-50 rounded-xl border border-gray-200 text-sm text-gray-500">
                        <i class="fas fa-lock text-gray-300 text-lg"></i>
                        <span>Cette étape sera accessible une fois les étapes précédentes complétées.</span>
                    </div>
                @else

                {{-- ============================================================
                     Étape 1 — DRAFT_ARTICLE / ARTICLE_READY
                ============================================================ --}}
                @if($state === WF::DRAFT_ARTICLE)
                    <div class="info-tiles grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 text-sm mb-5">
                        <div class="bg-gray-50 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Numéro</p>
                            <p class="font-semibold text-gray-800">{{ $article->numero ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Superficie</p>
                            <p class="font-semibold text-gray-800">{{ $article->superficie ?? '—' }} ha</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Canton</p>
                            <p class="font-semibold text-gray-800">{{ $article->canton ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Forêts</p>
                            <p class="font-semibold text-gray-800">{{ $article->forets->pluck('foret')->implode(', ') ?: '—' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Province</p>
                            <p class="font-semibold text-gray-800">{{ $article->provinces->pluck('nom')->implode(', ') ?: '—' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Créé le</p>
                            <p class="font-semibold text-gray-800">{{ $article->created_at?->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    @if($articleValidated)
                        {{-- Article already validated — show confirmation --}}
                        <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-800 mb-4">
                            <i class="fas fa-check-circle"></i>
                            <span>Article validé. Passez à l'étape suivante : <strong>Contrat de vente</strong>.</span>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('articles.consult', $article) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors">
                                <i class="fas fa-eye text-emerald-500"></i> Consulter l'article
                            </a>
                        </div>
                    @else
                        {{-- Explicit validation button for step 1 --}}
                        <div class="mt-2 pt-4 border-t border-gray-100" x-data="{ showConfirmArticle: false }">
                            <p class="text-sm text-gray-600 mb-3">Vérifiez les informations ci-dessus puis validez la création de l'article pour continuer.</p>
                            @if($canWriteStep)
                            <div class="flex gap-2 flex-wrap">
                                <a href="{{ route('articles.edit', $article) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-edit text-gray-500"></i> Modifier l'article
                                </a>
                                <button type="button"
                                        @click="showConfirmArticle = true"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                    <i class="fas fa-check"></i> Valider la création de l'article
                                </button>
                            </div>
                            @endif

                            {{-- Confirmation modal --}}
                            <template x-teleport="body">
                            <div x-show="showConfirmArticle"
                                 x-cloak
                                 x-effect="document.body.style.overflow = showConfirmArticle ? 'hidden' : ''"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm"
                                 @click.self="showConfirmArticle = false">
                                <div class="mx-4 w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                                    <div class="mb-4 flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-amber-600"></i>
                                        </div>
                                        <h3 class="text-base font-bold text-gray-900">Attention : action irréversible</h3>
                                    </div>
                                    <p class="mb-2 text-sm text-gray-700">Une fois validé, cet article sera verrouillé et ne pourra plus être modifié.</p>
                                    <p class="mb-6 text-sm text-amber-700 font-medium">Confirmez-vous la validation de l'article ?</p>
                                    <div class="flex gap-3 justify-end">
                                        <button type="button"
                                                @click="showConfirmArticle = false"
                                                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            Annuler
                                        </button>
                                        <form method="POST" action="{{ route('workflow.validate', $article) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                                <i class="fas fa-check"></i> Confirmer la validation
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            </template>
                        </div>
                    @endif

                {{-- ============================================================
                     Step 2 ? CONTRACT_CREATED
                ============================================================ --}}
                @elseif($state === WF::CONTRACT_CREATED)
                    @if(!$contractVente)
                        @php $validateBlocked = true; $validateBlockedReason = 'Un contrat de vente doit être créé avant de valider.'; @endphp
                    @endif
                    @if(!$contractVente)
                        @can(P::CONTRACT_SALE_CREATE)
                        @if(!$articleValidated)
                            {{-- Article not yet validated — block contract creation --}}
                            <div class="flex items-start gap-3 p-4 bg-red-50 rounded-lg border border-red-200 mb-4 text-sm">
                                <i class="fas fa-lock text-red-500 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <p class="font-semibold text-red-800 mb-1">Action non disponible</p>
                                    <p class="text-red-700">L'article doit être <strong>validé à l'étape 1</strong> avant de pouvoir créer un contrat de vente.</p>
                                </div>
                            </div>
                            <button type="button" disabled
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-gray-200 text-gray-400 rounded-lg cursor-not-allowed opacity-60">
                                <i class="fas fa-lock"></i> Créer le contrat de vente
                            </button>
                        @else
                            <a href="{{ route('contract-ventes.create', $article) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-plus"></i> Créer le contrat de vente
                            </a>
                        @endif
                        @endcan
                    @else
                        <div class="info-tiles grid grid-cols-2 md:grid-cols-3 gap-3 text-sm mb-4">
                            @foreach([
                                'Type'              => $article->cession?->mode_cession ?? $contractVente->type ?? '?',
                                'N? AO'             => $article->cession?->numAO ?? $contractVente->numeraAO ?? '?',
                                'Date adjudication' => ($article->cession?->DateAdj ?? $contractVente->date_adjudication)?->format('d/m/Y') ?? '?',
                                'Exploitant'        => $contractVente->exploitant?->nom_complet ?? '?',
                                'Nb tranches'       => $contractVente->nombre_tranche ?? '?',
                                'Date expiration'   => $contractVente->date_expiration?->format('d/m/Y') ?? '?',
                            ] as $label => $value)
                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-500 mb-0.5">{{ $label }}</p>
                                <p class="font-semibold text-gray-800 @if($label === 'Date expiration' && $contractVente->date_expiration?->isPast()) text-red-600 @endif">{{ $value }}</p>
                            </div>
                            @endforeach
                            <div class="bg-emerald-50 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-500 mb-0.5">Prix de vente</p>
                                <p class="font-bold text-emerald-700">{{ number_format($contractVente->prix_vente ?? 0, 2, ',', ' ') }} MAD</p>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            @if($isDone || $contractVente->is_validated)
                                <div class="flex items-center gap-2 p-2 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-800 w-full">
                                    <i class="fas fa-lock"></i>
                                    <span>Contrat validé — verrouillé en consultation uniquement.</span>
                                </div>
                                <a href="{{ route('contract-ventes.show', [$article, $contractVente]) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye"></i> Consulter contrat
                                </a>
                            @else
                                @can(P::CONTRACT_SALE_UPDATE)
                                <a href="{{ route('contract-ventes.edit', [$article, $contractVente]) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-edit text-gray-500"></i> Modifier
                                </a>
                                @endcan
                                <a href="{{ route('contract-ventes.show', [$article, $contractVente]) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-eye text-gray-500"></i> Consulter
                                </a>
                            @endif
                        </div>
                    @endif


                {{-- ============================================================
                     Étape 3 — LETTER_SIGNED_UPLOADED (Lettre adjudicataire)
                ============================================================ --}}
                @elseif($state === WF::LETTER_SIGNED_UPLOADED)
                    @if(!$contractVente)
                        @php $validateBlocked = true; $validateBlockedReason = 'Un contrat de vente doit exister avant cette étape.'; @endphp
                    @endif

                    {{-- Download generated letter --}}
                    @if($contractVente)
                        @can(P::ADJUDICATAIRE_LETTER_DOWNLOAD)
                        <div class="flex flex-wrap gap-3 mb-4">
                            <a href="{{ route('articles.lettre-adjudicataire.print', $article) }}"
                               target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <i class="fas fa-print"></i> Imprimer la lettre adjudicataire
                            </a>
                        </div>
                        @endcan
                        @if($contractVente->letter_generated_at)
                            <p class="text-xs text-gray-500 mb-3">Générée le {{ $contractVente->letter_generated_at->format('d/m/Y à H:i') }}</p>
                        @endif
                    @endif

                    @if($isDone && $contractVente?->letter_signed_file)
                        {{-- Step validated — locked --}}
                        <div class="flex items-center gap-2 text-sm text-emerald-700 mb-3">
                            <i class="fas fa-check-circle"></i>
                            <span>Importée le {{ $contractVente->letter_signed_at?->format('d/m/Y') ?? '—' }}</span>
                            <a href="{{ route('workflow.view-signed-letter', $article) }}" target="_blank"
                               class="ml-2 inline-flex items-center gap-1 text-blue-600 hover:underline text-xs font-medium">
                                <i class="fas fa-file-pdf text-red-500"></i> Ouvrir
                            </a>
                        </div>
                        <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-800">
                            <i class="fas fa-lock"></i>
                            <span>Lettre adjudicataire validée — document verrouillé.</span>
                        </div>
                    @elseif($contractVente)
                        @can(P::ADJUDICATAIRE_LETTER_UPLOAD_SIGNED)
                        {{-- Upload form --}}
                        @if($errors->has('signed_letter'))
                            <div class="flex items-center gap-2 p-3 mb-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $errors->first('signed_letter') }}</span>
                            </div>
                        @endif

                        @if($contractVente->letter_signed_file)
                            <div class="flex items-center gap-2 text-sm text-emerald-700 mb-4">
                                <i class="fas fa-check-circle"></i>
                                <span>Importée le {{ $contractVente->letter_signed_at?->format('d/m/Y') ?? '—' }}</span>
                                <a href="{{ route('workflow.view-signed-letter', $article) }}" target="_blank"
                                   class="ml-2 inline-flex items-center gap-1 text-blue-600 hover:underline text-xs font-medium">
                                    <i class="fas fa-file-pdf text-red-500"></i> Ouvrir
                                </a>
                            </div>
                        @else
                            <p class="text-sm text-gray-600 mb-4">Importez la lettre adjudicataire signée par l'adjudicataire (PDF uniquement).</p>
                        @endif

                        <form action="{{ route('workflow.upload-signed-letter', $article) }}" method="POST"
                              enctype="multipart/form-data" class="flex items-end gap-3 flex-wrap mb-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    {{ $contractVente->letter_signed_file ? 'Remplacer le fichier (PDF)' : 'Fichier signé (PDF uniquement)' }}
                                </label>
                                <input type="file" name="signed_letter" accept=".pdf"
                                       class="block text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-medium hover:file:bg-emerald-100">
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-upload"></i> Importer
                            </button>
                        </form>

                        @if($contractVente->letter_signed_file)
                            <div class="pt-3 border-t border-gray-100">
                                <button type="button" id="btn_valider_lettre"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                    <i class="fas fa-check-circle"></i> Valider la lettre adjudicataire
                                </button>
                            </div>
                            {{-- Validation popup --}}
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var modal = document.createElement('div');
                                    modal.id = 'modal_valider_lettre';
                                    modal.style.display = 'none';
                                    modal.className = 'fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm';
                                    modal.innerHTML = `<div class="mx-4 w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                                        <div class="mb-4 flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 flex-shrink-0">
                                                <i class="fas fa-exclamation-triangle text-amber-600"></i>
                                            </div>
                                            <h3 class="text-base font-bold text-gray-900">Attention : action irréversible</h3>
                                        </div>
                                        <p class="mb-6 text-sm text-gray-700">Une fois validée, cette lettre d'adjudicataire ne pourra plus être modifiée. Confirmez-vous la validation ?</p>
                                        <div class="flex gap-3 justify-end">
                                            <button type="button" id="btn_annuler_lettre"
                                                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                Annuler
                                            </button>
                                            <form method="POST" action="{{ route('workflow.validate-signed-letter', $article) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                                    <i class="fas fa-check"></i> Confirmer la validation
                                                </button>
                                            </form>
                                        </div>
                                    </div>`;
                                    document.body.appendChild(modal);

                                    var btnVal = document.getElementById('btn_valider_lettre');
                                    if (btnVal) btnVal.addEventListener('click', function() {
                                        modal.style.display = 'flex';
                                        document.body.style.overflow = 'hidden';
                                    });
                                    function closeModal() {
                                        modal.style.display = 'none';
                                        document.body.style.overflow = '';
                                    }
                                    modal.addEventListener('click', function(e) {
                                        if (e.target === modal) closeModal();
                                    });
                                    modal.addEventListener('click', function(e) {
                                        if (e.target && e.target.id === 'btn_annuler_lettre') closeModal();
                                    });
                                });
                            </script>
                        @endif
                        @endcan
                    @endif

                {{-- ============================================================
                     Step 6 — CAUTION_PAID
                ============================================================ --}}
                @elseif($state === WF::CAUTION_PAID)
                    @php
                        $cautionPayment   = $cautionCharge?->payments?->first();
                        $cautionDateLimite = $contractVente?->date_de_decheance
                            ? \Carbon\Carbon::parse($contractVente->date_de_decheance)
                            : null;
                        $cautionPayDate   = $cautionPayment?->date_payment ? \Carbon\Carbon::parse($cautionPayment->date_payment) : null;
                        $cautionDateDepassee = $cautionDateLimite && $cautionPayDate && $cautionPayDate->gt($cautionDateLimite);
                        // Overdue = deadline passed AND caution is not yet paid
                        $cautionOverdue   = $cautionDateLimite
                            ? (!($cautionPayment?->is_paye) && $cautionDateLimite->isPast())
                            : false;
                    @endphp

                    @if(!$cautionPaid)
                        @php $validateBlocked = true; $validateBlockedReason = 'La caution doit être marquée comme payée avant de valider.'; @endphp
                    @elseif(!$cautionPayment?->date_payment)
                        @php $validateBlocked = true; $validateBlockedReason = 'La date de paiement de la caution est obligatoire.'; @endphp
                    @elseif(!$cautionPayment?->fichier_joint)
                        @php $validateBlocked = true; $validateBlockedReason = 'La quittance (fichier) est obligatoire.'; @endphp
                    @endif

                    {{-- Overdue alert --}}
                    @if($cautionOverdue)
                        <div class="flex items-start gap-3 p-3 mb-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
                            <i class="fas fa-exclamation-triangle mt-0.5 flex-shrink-0 text-red-500"></i>
                            <span><strong>Caution non payée – action requise.</strong> La date limite ({{ $cautionDateLimite->format('d/m/Y') }}) est dépassée.</span>
                        </div>
                    @endif

                    @if(!$cautionCharge)
                        <p class="text-sm text-gray-400 italic">Aucune charge "Cautionnement" trouvée dans le contrat.</p>
                    @elseif($cautionPayment?->is_paye && $cautionValidated)
                        {{-- ---- LOCKED: caution step already validated ---- --}}
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-800">
                                <i class="fas fa-lock"></i>
                                <span>Caution validée — champs verrouillés.</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Charge</p>
                                    <p class="font-medium text-gray-800">{{ $cautionCharge->nom }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Montant</p>
                                    <p class="font-medium text-gray-800">{{ number_format($cautionCharge->montant ?? 0, 2) }} MAD</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Date de paiement</p>
                                    <p class="font-medium text-gray-800">{{ $cautionPayDate?->format('d/m/Y') ?? '—' }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">N° quittance</p>
                                    <p class="font-medium text-gray-800">{{ $cautionPayment->num_quittace ?? '—' }}</p>
                                </div>
                                @if($cautionPayment->percepteur)
                                <div class="p-3 bg-gray-50 rounded-lg col-span-2">
                                    <p class="text-xs text-gray-500 mb-1">Percepteur</p>
                                    <p class="font-medium text-gray-800">{{ $cautionPayment->percepteur }}</p>
                                </div>
                                @endif
                            </div>
                            @if($cautionPayment->fichier_joint)
                                <a href="{{ asset('storage/' . $cautionPayment->fichier_joint) }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 text-sm bg-blue-50 text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-100">
                                    <i class="fas fa-file-alt"></i> Voir quittance
                                </a>
                            @endif
                        </div>
                    @else
                        {{-- ---- FORM: mark caution as paid ---- --}}
                        @if($canWriteStep)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Charge</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Montant</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Échéance</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Statut</th>
                                        <th class="text-left py-2 text-gray-600 font-medium">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <form action="{{ route('articles.update-charge-payments', $article) }}" method="POST"
                                          enctype="multipart/form-data" class="contents">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="payments[{{ $cautionCharge->id }}][charge_id]" value="{{ $cautionCharge->id }}">
                                        <input type="hidden" name="payments[{{ $cautionCharge->id }}][charge_nom]" value="{{ $cautionCharge->nom }}">
                                        <tr>
                                            <td class="py-2 pr-4 font-medium">{{ $cautionCharge->nom }}</td>
                                            <td class="py-2 pr-4">{{ number_format($cautionCharge->montant ?? 0, 2) }} MAD</td>
                                            <td class="py-2 pr-4">{{ $cautionCharge->date_echeance ? \Carbon\Carbon::parse($cautionCharge->date_echeance)->format('d/m/Y') : '—' }}</td>
                                            <td class="py-2 pr-4">
                                                @if($cautionPayment?->is_paye)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">
                                                        <i class="fas fa-check-circle"></i> Payée
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                                        <i class="fas fa-times-circle"></i> Impayée
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-2">
                                                <input type="hidden" name="payments[{{ $cautionCharge->id }}][statut]" value="0">
                                                <div class="space-y-2" x-data="{ checked: {{ $cautionPayment?->is_paye ? 'true' : 'false' }}, dateVal: '{{ $cautionPayment?->date_payment ? \Carbon\Carbon::parse($cautionPayment->date_payment)->format('Y-m-d') : '' }}' }">
                                                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-medium">
                                                        <input type="checkbox" name="payments[{{ $cautionCharge->id }}][statut]" value="1"
                                                               x-model="checked"
                                                               class="w-4 h-4 rounded text-emerald-600">
                                                        Marquer payée
                                                    </label>
                                                    <input type="text" name="payments[{{ $cautionCharge->id }}][reference]"
                                                           value="{{ $cautionPayment?->num_quittace ?? '' }}"
                                                           placeholder="N° quittance"
                                                           class="block w-40 px-2 py-1 text-xs border border-gray-300 rounded">
                                                    <div>
                                                        <input type="date" name="payments[{{ $cautionCharge->id }}][date_payment]"
                                                               x-model="dateVal"
                                                               class="block w-40 px-2 py-1 text-xs border border-gray-300 rounded"
                                                               :class="checked && !dateVal ? 'border-red-400 bg-red-50' : ''">
                                                        <template x-if="checked && !dateVal">
                                                            <p class="text-xs text-red-600 mt-0.5">Date de paiement obligatoire</p>
                                                        </template>
                                                        @if($cautionDateLimite && $cautionPayDate && $cautionDateDepassee)
                                                            <p class="text-xs text-amber-600 mt-0.5">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                La date dépasse la date limite autorisée ({{ $cautionDateLimite->format('d/m/Y') }})
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs text-gray-600 mb-1">Quittance <span class="text-red-500">*</span></label>
                                                        <input type="file" name="payments[{{ $cautionCharge->id }}][fichier_joint]"
                                                               accept=".pdf,.jpg,.jpeg,.png"
                                                               class="block w-40 text-xs text-gray-600 file:mr-2 file:rounded file:border-0 file:bg-emerald-50 file:px-2 file:py-1 file:font-medium file:text-emerald-700 hover:file:bg-emerald-100">
                                                        <p class="text-xs text-gray-400 mt-0.5">PDF / JPG / PNG</p>
                                                        @if($cautionPayment?->fichier_joint)
                                                            <a href="{{ asset('storage/' . $cautionPayment->fichier_joint) }}" target="_blank"
                                                               class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline mt-1">
                                                                <i class="fas fa-paperclip"></i> Voir quittance
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700">
                                                        <i class="fas fa-save"></i> Sauvegarder
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </form>
                                </tbody>
                            </table>
                        </div>

                        {{-- Déchéance — admin only, shown when overdue --}}
                        @if($cautionOverdue)
                            @can(P::FORFEITURE_CREATE)
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <form action="{{ route('workflow.caution-decheance', $article) }}" method="POST"
                                      onsubmit="return confirm('Confirmez-vous la mise en déchéance ?\n\nCette action est irréversible.')">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                        <i class="fas fa-ban"></i> Mettre en déchéance
                                    </button>
                                </form>
                            </div>
                            @endcan
                        @endif
                        @else
                        {{-- Read-only: show current payment status without the form --}}
                        @if($cautionCharge)
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Charge</p>
                                    <p class="font-medium text-gray-800">{{ $cautionCharge->nom }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Montant</p>
                                    <p class="font-medium text-gray-800">{{ number_format($cautionCharge->montant ?? 0, 2) }} MAD</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg col-span-2">
                                    <p class="text-xs text-gray-500 mb-1">Statut</p>
                                    @if($cautionPayment?->is_paye)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium"><i class="fas fa-check-circle"></i> Payée</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium"><i class="fas fa-times-circle"></i> Impayée</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @endif
                    @endif

                {{-- ============================================================
                     Step 7 ? TAXES_PAID
                ============================================================ --}}
                @elseif($state === WF::TAXES_PAID)
                    @if(!$allTaxesPaid)
                        @php $validateBlocked = true; $validateBlockedReason = 'Toutes les taxes doivent être payées avant de valider.'; @endphp
                    @endif
                    @if($taxeCharges->isEmpty())
                        <p class="text-sm text-gray-400 italic">Aucune taxe configurée dans le contrat.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Taxe</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Montant</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Échéance</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Statut</th>
                                        @if($canWriteStep)
                                        <th class="text-left py-2 text-gray-600 font-medium">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($taxeCharges as $charge)
                                        @php
                                            $payment = $charge->payments->first();
                                            $isOverdue = !$payment?->is_paye && $charge->date_echeance && now()->gt(\Carbon\Carbon::parse($charge->date_echeance));
                                            $paidLate = $payment?->is_paye && $payment->date_payment && $charge->date_echeance
                                                && \Carbon\Carbon::parse($payment->date_payment)->gt(\Carbon\Carbon::parse($charge->date_echeance));
                                        @endphp
                                        <tr class="hover:bg-gray-50 {{ $isOverdue ? 'bg-red-50' : '' }}">
                                            <td class="py-2 pr-4 font-medium">{{ $charge->nom }}</td>
                                            <td class="py-2 pr-4">{{ number_format($charge->montant ?? 0, 2) }} MAD</td>
                                            <td class="py-2 pr-4">
                                                {{ $charge->date_echeance ? \Carbon\Carbon::parse($charge->date_echeance)->format('d/m/Y') : '—' }}
                                                @if($isOverdue)
                                                    <span class="ml-1 inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium"><i class="fas fa-clock"></i> En retard</span>
                                                @endif
                                            </td>
                                            <td class="py-2 pr-4">
                                                @if($payment?->is_paye)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium"><i class="fas fa-check-circle"></i> Payée</span>
                                                    @if($paidLate)
                                                        <span class="ml-1 inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-amber-100 text-amber-700 rounded-full text-xs"><i class="fas fa-exclamation-triangle"></i> Hors délai</span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium"><i class="fas fa-times-circle"></i> Impayée</span>
                                                @endif
                                            </td>
                                            @if($canWriteStep)
                                            <td class="py-2">
                                                @if($payment?->is_paye && $taxesValidated)
                                                    {{-- LOCKED: step already validated --}}
                                                    <div class="space-y-1 text-xs text-gray-600">
                                                        <p><span class="text-gray-400">Date :</span> {{ $payment->date_payment ? \Carbon\Carbon::parse($payment->date_payment)->format('d/m/Y') : '—' }}</p>
                                                        <p><span class="text-gray-400">Quittance :</span> {{ $payment->num_quittace ?? '—' }}</p>
                                                        @if($payment->percepteur)
                                                            <p><span class="text-gray-400">Percepteur :</span> {{ $payment->percepteur }}</p>
                                                        @endif
                                                        @if($payment->fichier_joint)
                                                            <a href="{{ asset('storage/' . $payment->fichier_joint) }}" target="_blank"
                                                               class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline">
                                                                <i class="fas fa-file-alt"></i> Voir quittance
                                                            </a>
                                                        @endif
                                                        <p class="text-xs text-gray-400 italic"><i class="fas fa-lock mr-0.5"></i>Étape validée</p>
                                                    </div>
                                                @else
                                                    <form action="{{ route('articles.update-charge-payments', $article) }}" method="POST"
                                                          enctype="multipart/form-data">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="payments[{{ $charge->id }}][charge_id]" value="{{ $charge->id }}">
                                                        <input type="hidden" name="payments[{{ $charge->id }}][charge_nom]" value="{{ $charge->nom }}">
                                                        <input type="hidden" name="payments[{{ $charge->id }}][statut]" value="0">
                                                        <div class="space-y-1">
                                                            <label class="inline-flex items-center gap-1 cursor-pointer text-xs">
                                                                <input type="checkbox" name="payments[{{ $charge->id }}][statut]" value="1"
                                                                       {{ $payment?->is_paye ? 'checked' : '' }}
                                                                       class="w-4 h-4 rounded text-emerald-600">
                                                                Marquer payée
                                                            </label>
                                                            <input type="text" name="payments[{{ $charge->id }}][reference]"
                                                                   placeholder="N° quittance *"
                                                                   value="{{ $payment?->num_quittace ?? '' }}"
                                                                   class="block w-32 px-2 py-1 text-xs border border-gray-300 rounded" required>
                                                            <input type="date" name="payments[{{ $charge->id }}][date_payment]"
                                                                   value="{{ $payment?->date_payment ? \Carbon\Carbon::parse($payment->date_payment)->format('Y-m-d') : '' }}"
                                                                   class="block w-32 px-2 py-1 text-xs border border-gray-300 rounded" required>
                                                            <div>
                                                                <label class="block text-xs text-gray-600 mb-0.5">Quittance{!! $payment?->fichier_joint ? '' : ' <span class="text-red-500">*</span>' !!}</label>
                                                                @if($payment?->fichier_joint)
                                                                    <a href="{{ asset('storage/' . $payment->fichier_joint) }}" target="_blank"
                                                                       class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline mb-0.5">
                                                                        <i class="fas fa-file-alt"></i> Fichier actuel
                                                                    </a>
                                                                    <p class="text-xs text-gray-400">Nouveau fichier (optionnel) :</p>
                                                                @endif
                                                                <input type="file" name="payments[{{ $charge->id }}][fichier_joint]"
                                                                       accept=".pdf,.jpg,.jpeg,.png"
                                                                       {{ $payment?->fichier_joint ? '' : 'required' }}
                                                                       class="block w-32 text-xs text-gray-600 file:mr-2 file:rounded file:border-0 file:bg-emerald-50 file:px-2 file:py-1 file:font-medium file:text-emerald-700 hover:file:bg-emerald-100">
                                                                <p class="text-xs text-gray-400 mt-0.5">PDF / JPG / PNG</p>
                                                            </div>
                                                            <button type="submit"
                                                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700">
                                                                <i class="fas fa-save"></i> Enregistrer
                                                            </button>
                                                        </div>
                                                    </form>
                                                @endif
                                            </td>
                                            @endif {{-- canWriteStep --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($allTaxesPaid)
                            <p class="mt-3 text-sm text-emerald-700 font-medium"><i class="fas fa-check-circle mr-1"></i>Toutes les taxes sont payées.</p>
                        @endif
                    @endif

                {{-- ============================================================
                     Étape 6 — PERMIT_ISSUED (Permis d'exploiter)
                ============================================================ --}}
                @elseif($state === WF::PERMIT_ISSUED)
                    @if(!$permisExploiter)
                        @php $validateBlocked = true; $validateBlockedReason = "Le permis d'exploiter doit être créé avant de valider."; @endphp
                    @endif
                    @if($permisExploiter)
                        <div class="info-tiles grid grid-cols-2 md:grid-cols-3 gap-3 text-sm mb-4">
                            @foreach([
                                'Percepteur'       => $permisExploiter->percepteur ?? '—',
                                'N° quittance'     => $permisExploiter->num_quittance ?? '—',
                                'Expir. assurance' => $permisExploiter->date_expiration_assurance ? \Carbon\Carbon::parse($permisExploiter->date_expiration_assurance)->format('d/m/Y') : '—',
                                'DFP'              => $permisExploiter->dfp ?? '—',
                            ] as $label => $value)
                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-500 mb-0.5">{{ $label }}</p>
                                <p class="font-semibold text-gray-800">{{ $value }}</p>
                            </div>
                            @endforeach
                        </div>
                        @if($isDone)
                            <div class="flex items-center gap-2 p-2 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-800 mb-3">
                                <i class="fas fa-lock"></i>
                                <span>Permis validé — verrouillé en consultation uniquement.</span>
                            </div>
                        @endif

                        {{-- Signed file upload --}}
                        @can(P::OPERATING_PERMIT_UPLOAD_SIGNED)
                        <div class="mt-3 mb-3">
                            @if($permisExploiter->fichier_permis_signe)
                                <div class="flex items-center gap-3 p-2 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-800 mb-2">
                                    <i class="fas fa-check-circle text-emerald-600"></i>
                                    <span class="flex-1">Permis signé importé@if($permisExploiter->signed_at) le {{ $permisExploiter->signed_at->format('d/m/Y') }}@endif</span>
                                    <a href="{{ asset('storage/' . $permisExploiter->fichier_permis_signe) }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-2 py-1 bg-white border border-emerald-300 text-emerald-700 rounded hover:bg-emerald-50">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                </div>
                            @endif
                            <form action="{{ route('articles.permis-exploiter.upload-signe', $article) }}" method="POST"
                                  enctype="multipart/form-data" class="flex items-end gap-2 flex-wrap">
                                @csrf
                                <div class="flex-1 min-w-48">
                                    <label class="block text-xs text-gray-600 mb-1">
                                        {{ $permisExploiter->fichier_permis_signe ? 'Remplacer le permis signé' : 'Importer le permis signé' }}
                                    </label>
                                    <input type="file" name="fichier_permis_signe" accept=".pdf,.jpg,.jpeg,.png"
                                           class="block w-full text-xs text-gray-600 file:mr-2 file:rounded file:border-0 file:bg-emerald-50 file:px-2 file:py-1 file:font-medium file:text-emerald-700 hover:file:bg-emerald-100"
                                           required>
                                    <p class="text-xs text-gray-400 mt-0.5">PDF / JPG / PNG, max 10 Mo</p>
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                    <i class="fas fa-upload"></i> Importer
                                </button>
                            </form>
                        </div>
                        @endcan

                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('articles.permis-exploiter', $article) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="{{ $isDone ? 'fas fa-eye' : 'fas fa-edit' }} text-gray-500"></i> {{ $isDone ? 'Consulter' : 'Voir / modifier' }}
                            </a>
                            @can(P::OPERATING_PERMIT_DOWNLOAD_FOR_SIGNATURE)
                            <a href="{{ route('articles.print-permis-exploiter', $article) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-print"></i> Imprimer
                            </a>
                            @endcan
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-4">Toutes les obligations financières sont remplies. Vous pouvez maintenant émettre le permis d'exploiter.</p>
                        @can(P::OPERATING_PERMIT_CREATE)
                        <a href="{{ route('articles.permis-exploiter', $article) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-file-contract"></i> Gérer le permis d'exploiter
                        </a>
                        @endcan
                    @endif

                {{-- ============================================================
                     Step 10 ? PV_INSTALLATION_DONE
                ============================================================ --}}
                @elseif($state === WF::PV_INSTALLATION_DONE)
                    @if(!$pvInstallation || !$pvInstallation->fichier_pv_signe)
                        @php $validateBlocked = true; $validateBlockedReason = 'Le PV d\'installation signé doit être importé avant de valider.'; @endphp
                    @endif
                    @error('fichier_pv_signe')
                        <div class="flex items-center gap-2 text-sm text-red-600 mb-3">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    {{-- Gérer les véhicules --}}
                    @can(P::VEHICLE_CREATE)
                    <div class="flex items-center gap-2 flex-wrap mb-4">
                        <a href="{{ route('vehicles.index', $article) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition-colors">
                            <i class="fas fa-truck"></i> Gérer les véhicules
                        </a>
                    </div>
                    @endcan

                    {{-- Importer le PV d'installation signé --}}
                    @if($pvInstallation?->fichier_pv_signe)
                        <div class="flex items-center gap-2 text-sm text-emerald-700 mb-3">
                            <i class="fas fa-check-circle"></i>
                            <span>PV signé importé le {{ $pvInstallation->pv_signed_at?->format('d/m/Y') ?? '—' }}</span>
                            <a href="{{ route('workflow.view-signed-pv', $article) }}" target="_blank"
                               class="ml-2 inline-flex items-center gap-1 text-blue-600 hover:underline text-xs font-medium">
                                <i class="fas fa-file-pdf text-red-500"></i> Ouvrir le fichier
                            </a>
                        </div>
                    @endif
                    @if(!$isDone)
                    @can(P::INSTALLATION_REPORT_UPLOAD_SIGNED)
                    <form action="{{ route('workflow.upload-signed-pv', $article) }}" method="POST"
                          enctype="multipart/form-data" class="flex items-end gap-3 flex-wrap">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                {{ $pvInstallation?->fichier_pv_signe ? 'Remplacer le PV signé (PDF / JPG / PNG)' : 'Importer le PV d\'installation signé (PDF / JPG / PNG)' }}
                            </label>
                            <input type="file" name="fichier_pv_signe" accept=".pdf,.jpg,.jpeg,.png"
                                   class="block text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-medium hover:file:bg-emerald-100">
                        </div>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-upload"></i> Importer
                        </button>
                    </form>
                    @endcan
                    @elseif($pvInstallation?->fichier_pv_signe)
                        <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-800">
                            <i class="fas fa-lock"></i>
                            <span>Document verrouillé — le PV d'installation a été validé.</span>
                        </div>
                    @endif

                {{-- ============================================================
                     Étape 8 — TRANCHES_IN_PROGRESS
                ============================================================ --}}
                @elseif($state === WF::TRANCHES_IN_PROGRESS)
                    @if(!empty($tranchesBlockedReason))
                        @php $validateBlocked = true; $validateBlockedReason = $tranchesBlockedReason; @endphp
                    @endif
                    @php
                        $permisEnleverList = collect($permisEnlevers ?? []);
                        $permisDates = $permisEnleverList
                            ->pluck('date_paiement')
                            ->filter()
                            ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
                            ->unique()
                            ->values()
                            ->all();
                        $permisIdByDate = $permisEnleverList
                            ->filter(fn($p) => $p->date_paiement)
                            ->mapWithKeys(fn($p) => [\Carbon\Carbon::parse($p->date_paiement)->format('Y-m-d') => $p->id])
                            ->all();

                        $denombrementDates = $denombrements
                            ->pluck('date_denombrement')
                            ->filter()
                            ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
                            ->unique()
                            ->values()
                            ->all();
                    @endphp
                    @php
                        $anefCharge = $contractVente?->chargeApayer->first(fn($c) => str_contains(strtolower($c->nom), 'anef'));
                        $anefPaid   = $anefCharge ? (bool) $anefCharge->payments->first()?->is_paye : true;
                    @endphp
                    {{-- ANEF taxe payment section --}}
                    @if(!$anefPaid && $anefCharge)
                        @php
                            $anefPayment = $anefCharge->payments->first();
                        @endphp
                        <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg mb-4">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-exclamation-triangle text-amber-600"></i>
                                <h4 class="text-sm font-semibold text-amber-800">Taxe service rendu ANEF — Paiement requis</h4>
                            </div>
                            <div class="flex items-center gap-4 text-sm mb-3">
                                <span class="text-gray-700 font-medium">{{ $anefCharge->nom }}</span>
                                <span class="text-gray-600">{{ number_format($anefCharge->montant ?? 0, 2) }} MAD</span>
                                @if($anefCharge->date_echeance)
                                    <span class="text-gray-500">Échéance : {{ \Carbon\Carbon::parse($anefCharge->date_echeance)->format('d/m/Y') }}</span>
                                @endif
                            </div>
                            @if(!$isDone && $canWriteStep)
                            <form action="{{ route('articles.update-charge-payments', $article) }}" method="POST"
                                  enctype="multipart/form-data" class="space-y-2">
                                @csrf @method('PUT')
                                <input type="hidden" name="payments[{{ $anefCharge->id }}][charge_id]" value="{{ $anefCharge->id }}">
                                <input type="hidden" name="payments[{{ $anefCharge->id }}][charge_nom]" value="{{ $anefCharge->nom }}">
                                <input type="hidden" name="payments[{{ $anefCharge->id }}][statut]" value="0">
                                <div class="flex flex-wrap items-end gap-2">
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-medium">
                                        <input type="checkbox" name="payments[{{ $anefCharge->id }}][statut]" value="1"
                                               {{ $anefPayment?->is_paye ? 'checked' : '' }}
                                               class="w-4 h-4 rounded text-emerald-600">
                                        Marquer payée
                                    </label>
                                    <input type="text" name="payments[{{ $anefCharge->id }}][reference]"
                                           placeholder="N° quittance *"
                                           value="{{ $anefPayment?->num_quittace ?? '' }}"
                                           class="block w-36 px-2 py-1 text-xs border border-gray-300 rounded" required>
                                    <input type="date" name="payments[{{ $anefCharge->id }}][date_payment]"
                                           value="{{ $anefPayment?->date_payment ? \Carbon\Carbon::parse($anefPayment->date_payment)->format('Y-m-d') : '' }}"
                                           class="block w-36 px-2 py-1 text-xs border border-gray-300 rounded" required>
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-0.5">Quittance{!! $anefPayment?->fichier_joint ? '' : ' <span class="text-red-500">*</span>' !!}</label>
                                        @if($anefPayment?->fichier_joint)
                                            <a href="{{ asset('storage/' . $anefPayment->fichier_joint) }}" target="_blank"
                                               class="text-xs text-blue-600 hover:underline block mb-0.5">
                                                <i class="fas fa-file-pdf text-red-500"></i> Voir fichier actuel
                                            </a>
                                            <p class="text-xs text-gray-400">Nouveau fichier (optionnel) :</p>
                                        @endif
                                        <input type="file" name="payments[{{ $anefCharge->id }}][fichier_joint]"
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               {{ $anefPayment?->fichier_joint ? '' : 'required' }}
                                               class="block w-36 text-xs text-gray-600 file:mr-2 file:rounded file:border-0 file:bg-emerald-50 file:px-2 file:py-1 file:font-medium file:text-emerald-700 hover:file:bg-emerald-100">
                                    </div>
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                        <i class="fas fa-check"></i> Enregistrer
                                    </button>
                                </div>
                            </form>
                            @endif
                        </div>
                    @endif
                    @if($isDone)
                        <div class="flex items-center gap-2 p-2 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-800 mb-3">
                            <i class="fas fa-lock"></i>
                            <span>Étape tranches validée — données verrouillées en consultation.</span>
                        </div>
                    @endif
                    @if($tranches->isEmpty())
                        <p class="text-sm text-gray-400 italic">Aucune tranche configurée dans le contrat.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Tranche</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Montant</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Échéance</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Statut</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Dénombrement</th>
                                        <th class="text-left py-2 text-gray-600 font-medium">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($tranches as $tranche)
                                        @php
                                            $tp = $tranche->payments->first();
                                            $trancheOverdue = !$tp?->is_paye && $tranche->date_echeance && now()->gt(\Carbon\Carbon::parse($tranche->date_echeance));
                                        @endphp
                                        <form action="{{ route('articles.pay-tranches', $article) }}" method="POST"
                                              enctype="multipart/form-data" class="contents">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="selected_tranche" value="{{ $tranche->id }}">
                                            <tr class="hover:bg-gray-50 {{ $trancheOverdue ? 'bg-red-50' : '' }}">
                                                <td class="py-2 pr-4 font-medium">{{ $tranche->nom }}</td>
                                                <td class="py-2 pr-4">{{ number_format($tranche->montant ?? 0, 2) }} MAD</td>
                                                <td class="py-2 pr-4">
                                                    {{ $tranche->date_echeance ? \Carbon\Carbon::parse($tranche->date_echeance)->format('d/m/Y') : '—' }}
                                                    @if($trancheOverdue)
                                                        <span class="ml-1 inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium"><i class="fas fa-clock"></i> En retard</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 pr-4">
                                                    @if($tp?->is_paye)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium"><i class="fas fa-check-circle"></i> Payée</span>
                                                    @elseif($trancheOverdue)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium"><i class="fas fa-exclamation-circle"></i> En retard</span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-xs font-medium"><i class="fas fa-clock"></i> En attente</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 pr-4">
                                                    @php
                                                        $paymentDate = $tp?->date_payment
                                                            ? \Carbon\Carbon::parse($tp->date_payment)->format('Y-m-d')
                                                            : null;
                                                        $hasDenombrementForDate = $paymentDate && in_array($paymentDate, $denombrementDates, true);
                                                        $denomUploadId = 'denum_' . $tranche->id;
                                                    @endphp
                                                    @if($hasDenombrementForDate)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">
                                                            <i class="fas fa-check-circle"></i> Oui
                                                        </span>
                                                    @else
                                                        {{-- No dénombrement: checkbox + upload (form outside table via HTML5 form= attribute) --}}
                                                        <div x-data="{ checked: false }">
                                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                                <input type="checkbox" x-model="checked"
                                                                       class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                                                                <span class="text-xs font-medium" :class="checked ? 'text-teal-700' : 'text-gray-500'"
                                                                      x-text="checked ? 'Oui' : 'Non'"></span>
                                                            </label>
                                                            <div x-show="checked" x-cloak class="mt-2 space-y-1">
                                                                <label class="block text-xs text-gray-600">PV de dénombrement <span class="text-red-500">*</span></label>
                                                                <input type="file" name="fichier_pv"
                                                                       form="{{ $denomUploadId }}"
                                                                       accept=".pdf,.jpg,.jpeg,.png"
                                                                       required
                                                                       class="block w-36 text-xs text-gray-600 file:mr-1 file:rounded file:border-0 file:bg-teal-50 file:px-2 file:py-1 file:font-medium file:text-teal-700 hover:file:bg-teal-100">
                                                                <button type="submit"
                                                                        form="{{ $denomUploadId }}"
                                                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-teal-600 text-white rounded hover:bg-teal-700">
                                                                    <i class="fas fa-upload"></i> Uploader
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="py-2">
                                                    @php
                                                        $hasPermisForDate = $paymentDate && in_array($paymentDate, $permisDates, true);
                                                    @endphp
                                                    @if(!$tp?->is_paye && !$isDone && $canWriteStep)
                                                    <div class="space-y-1">
                                                        <input type="text" name="num_quittance" placeholder="N° quittance *"
                                                               class="block w-32 px-2 py-1 text-xs border border-gray-300 rounded" required>
                                                        <input type="date" name="date_payment"
                                                               class="block w-32 px-2 py-1 text-xs border border-gray-300 rounded" required>
                                                        <div>
                                                            <label class="block text-xs text-gray-600 mb-0.5">Quittance <span class="text-red-500">*</span></label>
                                                            <input type="file" name="fichier_joint"
                                                                   accept=".pdf,.jpg,.jpeg,.png"
                                                                   class="block w-32 text-xs text-gray-600 file:mr-2 file:rounded file:border-0 file:bg-emerald-50 file:px-2 file:py-1 file:font-medium file:text-emerald-700 hover:file:bg-emerald-100"
                                                                   required>
                                                        </div>
                                                        <button type="submit"
                                                                class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700">
                                                            <i class="fas fa-check"></i> Payer
                                                        </button>
                                                    </div>
                                                    @elseif(!$tp?->is_paye && $isDone)
                                                    <span class="text-xs text-gray-400 italic"><i class="fas fa-lock mr-0.5"></i>Verrouillé</span>
                                                    @else
                                                        <div class="space-y-2">
                                                            <div class="text-xs">
                                                                <p><span class="text-gray-400">Quittance :</span> {{ $tp->num_quittace ?? '—' }}</p>
                                                                @if($tp->date_payment)
                                                                    <p><span class="text-gray-400">Date :</span> {{ \Carbon\Carbon::parse($tp->date_payment)->format('d/m/Y') }}</p>
                                                                @endif
                                                                @if($tp->fichier_joint)
                                                                    <a href="{{ asset('storage/' . $tp->fichier_joint) }}" target="_blank"
                                                                       class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline">
                                                                        <i class="fas fa-file-alt"></i> Voir quittance
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                            @if($hasPermisForDate)
                                                                @php $permisIdForDate = $permisIdByDate[$paymentDate] ?? null; @endphp
                                                                @if($permisIdForDate)
                                                                <a href="{{ route('articles.permis-enlever.show', ['article' => $article, 'permiEnlever' => $permisIdForDate]) }}"
                                                                   class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                                    <i class="fas fa-eye"></i> Voir permis d'enlever
                                                                </a>
                                                                @endif
                                                            @else
                                                                @can(P::REMOVAL_PERMIT_CREATE)
                                                                @if($anefPaid)
                                                                <a href="{{ route('articles.permis-enlever.create', ['article' => $article, 'date_paiement' => $paymentDate]) }}"
                                                                   class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700">
                                                                    <i class="fas fa-plus"></i> Créer permis d'enlever
                                                                </a>
                                                                @else
                                                                <span title="La taxe service rendu ANEF doit être payée avant de créer un permis d'enlever."
                                                                      class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-gray-100 text-gray-400 rounded cursor-not-allowed opacity-60">
                                                                    <i class="fas fa-lock"></i> Créer permis d'enlever
                                                                </span>
                                                                @endif
                                                                @endcan
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </form>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Dénombrement upload forms (outside table to avoid nested <form>) --}}
                        @foreach($tranches as $tranche)
                            @php
                                $tp2 = $tranche->payments->first();
                                $pd2 = $tp2?->date_payment ? \Carbon\Carbon::parse($tp2->date_payment)->format('Y-m-d') : null;
                                $hasDenom2 = $pd2 && in_array($pd2, $denombrementDates, true);
                            @endphp
                            @if(!$hasDenom2)
                                <form id="denum_{{ $tranche->id }}"
                                      action="{{ route('articles.store-denombrement', $article) }}"
                                      method="POST" enctype="multipart/form-data" style="display:none">
                                    @csrf
                                    <input type="hidden" name="date_denombrement" value="{{ $pd2 }}">
                                </form>
                            @endif
                        @endforeach
                    @endif


                    {{-- Résiliation contrat --}}
                    @can('termination.create')
                    @if($contractVente && !$contractVente->is_resiliation)
                        <div class="mt-4 pt-4 border-t border-gray-100" x-data="{ confirmResilier: false }">
                            <button type="button" @click="confirmResilier = true"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-ban"></i> Résilier le contrat
                            </button>
                            <div x-show="confirmResilier" x-cloak class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm font-medium text-red-800 mb-3">Confirmer la résiliation du contrat ? Cette action est irréversible.</p>
                                <form action="{{ route('workflow.resilier', $article) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 mr-2">
                                        <i class="fas fa-check"></i> Confirmer
                                    </button>
                                    <button type="button" @click="confirmResilier = false"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                        Annuler
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($contractVente?->is_resiliation)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-red-100 text-red-800 rounded-lg">
                                <i class="fas fa-ban"></i> Contrat résilié le {{ $contractVente->date_de_resiliation?->format('d/m/Y') ?? '—' }}
                            </span>
                        </div>
                    @endif
                    @endcan


                {{-- ============================================================
                     Step 9 — RECOLEMENT_PENDING
                ============================================================ --}}
                @elseif($state === WF::RECOLEMENT_PENDING)
                    @if($recolement)
                        <div class="info-tiles grid grid-cols-2 gap-3 text-sm mb-4">
                            @foreach([
                                'N? PV'        => $recolement->num_pv ?? '?',
                                'Date PV'      => $recolement->date_pv?->format('d/m/Y') ?? '?',
                                'Observations' => $recolement->observations ?? '?',
                            ] as $label => $value)
                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-500 mb-0.5">{{ $label }}</p>
                                <p class="font-semibold text-gray-800">{{ $value }}</p>
                            </div>
                            @endforeach
                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-500 mb-0.5">Statut</p>
                                <p class="font-semibold">
                                    @if($recolement->status === 'pv_submitted') <span class="text-blue-600">PV soumis</span>
                                    @elseif($recolement->status === 'mainlevee_issued') <span class="text-emerald-600">Mainlevée émise</span>
                                    @elseif($recolement->status === 'closed') <span class="text-gray-600">Clôturé</span>
                                    @else <span class="text-amber-600">En attente</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-4">Aucun PV de récolement soumis.</p>
                    @endif
                    @if($isDone)
                        <div class="flex items-center gap-2 p-2 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-800 mb-3">
                            <i class="fas fa-lock"></i>
                            <span>Récolement validé — données verrouillées.</span>
                        </div>
                    @else
                    @can(P::RECOLEMENT_REPORT_CREATE)
                    <a href="{{ route('workflow.recolement.create', $article) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-plus"></i> {{ $recolement ? 'Modifier le PV' : 'Soumettre le PV de récolement' }}
                    </a>
                    @endcan
                    @endif

                {{-- ============================================================
                     Step 15 ? MAINLEVEE_DONE
                ============================================================ --}}
                @elseif($state === WF::MAINLEVEE_DONE)
                    @if($recolement?->date_mainlevee)
                        <div class="info-tiles grid grid-cols-2 gap-3 text-sm mb-4">
                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-500 mb-0.5">N° mainlevée</p>
                                <p class="font-semibold text-gray-800">{{ $recolement->num_mainlevee ?? '?' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-500 mb-0.5">Date</p>
                                <p class="font-semibold text-gray-800">{{ $recolement->date_mainlevee?->format('d/m/Y') ?? '?' }}</p>
                            </div>
                        </div>
                        @if($recolement->fichier_mainlevee)
                            <a href="{{ asset('storage/' . $recolement->fichier_mainlevee) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-pdf text-red-500"></i> Voir le fichier mainlevée
                            </a>
                        @endif
                    @else
                        <p class="text-sm text-gray-600 mb-4">Mainlevée non encore émise.</p>
                        @if($recolement?->status === 'pv_submitted')
                        @can(P::RELEASE_CREATE)
                        <form action="{{ route('workflow.mainlevee.issue', $article) }}" method="POST"
                              enctype="multipart/form-data" class="space-y-3 max-w-md">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">N° mainlevée <span class="text-red-500">*</span></label>
                                <input type="text" name="num_mainlevee" required
                                       class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Date mainlevée <span class="text-red-500">*</span></label>
                                <input type="date" name="date_mainlevee" required
                                       class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Fichier (PDF)</label>
                                <input type="file" name="fichier_mainlevee" accept=".pdf"
                                       class="block text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-medium">
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-stamp"></i> Émettre la mainlevée
                            </button>
                        </form>
                        @endcan
                        @endif
                    @endif

                {{-- ============================================================
                     Step 16 ? CLOSED
                ============================================================ --}}
                @elseif($state === WF::CLOSED)
                    @if($wfState === WF::CLOSED)
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <i class="fas fa-archive text-gray-400 text-2xl"></i>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Dossier clôturé</p>
                                <p class="text-xs text-gray-500">Ce dossier est définitivement archivé.</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-4">Clôturez définitivement ce dossier après émission de la mainlevée.</p>
                        @if($wfState === WF::MAINLEVEE_DONE)
                        @can(P::CESSION_CLOSE)
                        <form action="{{ route('workflow.close', $article) }}" method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir clôturer définitivement ce dossier ?')">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                                <i class="fas fa-archive"></i> Clôturer le dossier
                            </button>
                        </form>
                        @endcan
                        @endif
                    @endif
                @endif

                @php
                    $transitionStateKeys = array_keys($allStepDefs);
                    $transitionIndex = array_search($state, $transitionStateKeys, true);
                    $workflowStateIndex = array_search($wfState, $transitionStateKeys, true);
                    // ARTICLE_READY sits between DRAFT_ARTICLE (0) and CONTRACT_CREATED (1).
                    // It is not in $allStepDefs, so array_search returns false, breaking the
                    // "is the step ahead of the current state?" guard below. Treat it as index 0.
                    if ($workflowStateIndex === false && $wfState === WF::ARTICLE_READY) {
                        $workflowStateIndex = 0;
                    }
                    $nextTransitionState = $transitionIndex !== false && $transitionIndex < count($transitionStateKeys) - 1
                        ? $transitionStateKeys[$transitionIndex + 1]
                        : null;
                    $transitionTargetState = $isActive
                        ? (($workflowStateIndex !== false && $transitionIndex !== false && $transitionIndex > $workflowStateIndex)
                            ? $state
                            : $nextTransitionState)
                        : null;
                    // Exclude steps that have their own dedicated validation form/button
                    $stepsWithOwnValidation = [WF::DRAFT_ARTICLE, WF::LETTER_SIGNED_UPLOADED, WF::RECOLEMENT_PENDING, WF::MAINLEVEE_DONE, WF::CLOSED];
                    $showTransitionButton = $isActive
                        && $transitionTargetState !== null
                        && !in_array($state, $stepsWithOwnValidation, true);
                    $transitionTargetDef = $transitionTargetState ? (($visibleStepDefs[$transitionTargetState] ?? $allStepDefs[$transitionTargetState]) ?? null) : null;
                    $isValidatingCurrentStep = $transitionTargetState === $state;
                    $modalId = 'modal_validate_' . str_replace('_', '', strtolower($state));
                @endphp

                @if($showTransitionButton && $canWriteStep)
                    <div class="mt-5 pt-4 border-t border-gray-100" x-data="{ showConfirm_{{ strtolower($state) }}: false }">
                        @if($validateBlocked)
                            {{-- Blocked: prerequisite not met --}}
                            <div class="flex items-center gap-3 p-4 rounded-xl"
                                 style="background:rgba(254,243,199,0.6);border:1px solid rgba(217,119,6,0.25);">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-amber-100">
                                    <i class="fas fa-lock text-amber-600 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-amber-800 mb-0.5">Validation bloquée</p>
                                    <p class="text-xs text-amber-700">{{ $validateBlockedReason }}</p>
                                </div>
                                <button type="button" disabled
                                        class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg cursor-not-allowed opacity-50"
                                        style="background:#9ca3af;">
                                    <i class="fas fa-lock"></i>
                                    Valider l'étape
                                </button>
                            </div>
                        @else
                            {{-- Ready: show validate card with confirmation modal --}}
                            <div class="flex items-center gap-3 p-4 rounded-xl"
                                 style="background:linear-gradient(135deg,rgba(5,150,105,0.06) 0%,rgba(16,185,129,0.04) 100%);border:1px solid rgba(5,150,105,0.2);">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background:linear-gradient(135deg,#059669,#047857);">
                                    <i class="fas fa-check-circle text-white text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-emerald-800 mb-0.5">
                                        {{ $isValidatingCurrentStep ? 'Valider cette étape' : 'Passer à l\'étape suivante' }}
                                    </p>
                                    <p class="text-xs text-emerald-700 truncate">
                                        Étape {{ $transitionTargetDef['badge'] ?? '' }} — {{ $transitionTargetDef['title'] ?? '' }}
                                    </p>
                                </div>
                                <button type="button"
                                        @click="showConfirm_{{ strtolower($state) }} = true"
                                        class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all duration-150 hover:shadow-md active:scale-95"
                                        style="background:linear-gradient(135deg,#059669,#047857);box-shadow:0 2px 8px rgba(5,150,105,0.3);">
                                    <i class="fas fa-check-circle"></i>
                                    Valider l'étape
                                </button>
                            </div>

                            {{-- Confirmation modal --}}
                            <template x-teleport="body">
                            <div x-show="showConfirm_{{ strtolower($state) }}"
                                 x-cloak
                                 x-effect="document.body.style.overflow = showConfirm_{{ strtolower($state) }} ? 'hidden' : ''"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm"
                                 @click.self="showConfirm_{{ strtolower($state) }} = false">
                                <div class="mx-4 w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                                    <div class="mb-4 flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-amber-600"></i>
                                        </div>
                                        <h3 class="text-base font-bold text-gray-900">Attention : action irréversible</h3>
                                    </div>
                                    <p class="mb-2 text-sm text-gray-700">
                                        Vous êtes sur le point de valider l'étape <strong>{{ $def['title'] }}</strong>.
                                    </p>
                                    <p class="mb-6 text-sm text-amber-700 font-medium">
                                        Une fois validée, cette étape sera verrouillée et les données ne pourront plus être modifiées.
                                    </p>
                                    <div class="flex gap-3 justify-end">
                                        <button type="button"
                                                @click="showConfirm_{{ strtolower($state) }} = false"
                                                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            Annuler
                                        </button>
                                        <form method="POST" action="{{ route('workflow.transition', $article) }}">
                                            @csrf
                                            <input type="hidden" name="state" value="{{ $transitionTargetState }}">
                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                                <i class="fas fa-check"></i> Confirmer la validation
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            </template>
                        @endif
                    </div>
                @endif

                {{-- Validation errors --}}
                @if(isset($errors) && $errors->any() && $isActive)
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="text-xs text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @endif {{-- end blocked check --}}

            </div>{{-- end panel body --}}

            {{-- Panel footer: progress indicator --}}
            <div class="px-4 sm:px-5 py-2.5 border-t border-gray-100 bg-gray-50 flex items-center justify-between text-xs text-gray-400">
                @php $allKeys = array_keys($visibleStepDefs); $pos = array_search($state, $allKeys) + 1; @endphp
                <span>Étape {{ $pos }} / {{ count($visibleStepDefs) }}</span>
                <div class="flex items-center gap-1">
                    @foreach($allKeys as $k)
                        @php $s = $displaySteps[$k]['status'] ?? 'blocked'; @endphp
                        <span class="w-1.5 h-1.5 rounded-full transition-all
                            @if($k === $state) w-3 bg-blue-500
                            @elseif($s === 'done') bg-emerald-400
                            @else bg-gray-300 @endif"></span>
                    @endforeach
                </div>
            </div>

        </div>{{-- end panel --}}
    @endforeach

{{-- @push('scripts') removed: colportage JS no longer needed --}}
{{--
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    //�f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬�f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬ Permis d'Enlever selector in Step 13: reload page with selected PE �f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬�f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬
    document.querySelectorAll('[id^="colportage-pe-select-"]').forEach(function (sel) {
        sel.addEventListener('change', function () {
            const url = new URL(window.location.href);
            const articleId = this.id.replace('colportage-pe-select-', '');
            url.searchParams.set('colportage_pe_' + articleId, this.value);
            window.location.href = url.toString();
        });
    });

    // �f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬�f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬ Live search inside colportage table �f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬�f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬
    document.querySelectorAll('.colportage-search').forEach(function (input) {
        input.addEventListener('input', function () {
            const q     = this.value.toLowerCase();
            const tbody = document.getElementById(this.dataset.table);
            if (!tbody) return;
            tbody.querySelectorAll('tr').forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    });

});
</script>
@endpush
--}}

</div>
