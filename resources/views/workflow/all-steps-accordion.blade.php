{{--
  Multi-Step Workflow for article show page.
  Variables: $article, $contractVente, $permisEnlevers, $exploitants, $steps, $alerts
--}}
@php
use App\Services\ArticleWorkflowService as WF;

$wfState = $article->workflow_state ?? WF::DRAFT_ARTICLE;

$chargesAll   = $contractVente?->chargeApayer ?? collect();
$charges      = $chargesAll->filter(fn($c) => !str_starts_with($c->nom, 'Tranche'))->values();
$tranches     = $chargesAll->filter(fn($c) => str_starts_with($c->nom, 'Tranche'))
                    ->sortBy(fn($c) => (int) preg_replace('/\D/', '', $c->nom))
                    ->values();

$cautionCharge = $charges->first(fn($c) => str_contains(strtolower($c->nom), 'caution') || str_contains(strtolower($c->nom), 'cautionnement'));
$taxeCharges   = $charges->filter(fn($c) => !str_contains(strtolower($c->nom), 'caution') && !str_contains(strtolower($c->nom), 'cautionnement'));

$cautionPaid   = $cautionCharge?->payments?->first()?->is_paye ?? false;
$allTaxesPaid  = $taxeCharges->every(fn($c) => $c->payments->first()?->is_paye);

$permisExploiter     = $contractVente?->permisExploiter;
$pvInstallation      = $contractVente?->pvInstallations?->first();
$vehicleDeclarations = $contractVente?->vehicleDeclarations ?? collect();
$prorogations        = $contractVente?->prorogations ?? collect();
$denombrements       = $contractVente?->denombrements ?? collect();
$recolement          = $contractVente?->recolement;

// 12-step definitions matching WF::LABELS order
$allStepDefs = [
    WF::DRAFT_ARTICLE        => ['icon' => 'fa-pencil-alt',      'title' => "Création de l'article",  'badge' => '1'],
    WF::CONTRACT_CREATED     => ['icon' => 'fa-file-contract',   'title' => 'Contrat de vente',        'badge' => '2'],
    WF::LETTER_SIGNED_UPLOADED => ['icon' => 'fa-file-signature','title' => 'Lettre adjudicataire',    'badge' => '3'],
    WF::CAUTION_PAID         => ['icon' => 'fa-shield-alt',      'title' => 'Paiement caution',        'badge' => '4'],
    WF::TAXES_PAID           => ['icon' => 'fa-money-bill-wave', 'title' => 'Paiement des taxes',      'badge' => '5'],
    WF::PERMIT_ISSUED        => ['icon' => 'fa-stamp',           'title' => "Permis d'exploiter",      'badge' => '6'],
    WF::PV_INSTALLATION_DONE => ['icon' => 'fa-clipboard-check', 'title' => "PV d'installation",       'badge' => '7'],
    WF::TRANCHES_IN_PROGRESS => ['icon' => 'fa-credit-card',     'title' => 'Paiement des tranches',   'badge' => '8'],
    WF::COLPORTAGE_ACTIVE    => ['icon' => 'fa-shipping-fast',   'title' => 'Colportage',              'badge' => '9'],
    WF::RECOLEMENT_PENDING   => ['icon' => 'fa-clipboard-list',  'title' => 'Récolement',              'badge' => '10'],
    WF::MAINLEVEE_DONE       => ['icon' => 'fa-unlock',          'title' => 'Mainlevée',               'badge' => '11'],
    WF::CLOSED               => ['icon' => 'fa-archive',         'title' => 'Clôture',                 'badge' => '12'],
];

$permisEnleverList = $permisEnlevers ?? collect();
$colportageIsNextAction = $permisEnleverList->isNotEmpty()
    && ($steps[WF::COLPORTAGE_ACTIVE]['status'] ?? 'blocked') !== 'done';

// $steps already contains exactly the 12 LABELS states from getStepStatuses()
$displaySteps = $steps;
$visibleStepDefs = $allStepDefs;

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
            <span class="ml-auto text-xs text-gray-400">Cliquez sur une ?tape pour la consulter</span>
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
                                @elseif($colportageIsNextAction && $state === WF::COLPORTAGE_ACTIVE) border-amber-400 bg-amber-400
                                @elseif($status === 'blocked') border-gray-200 bg-gray-100
                                @else border-gray-200 bg-gray-100 @endif"
                                 :class="active === '{{ $state }}' ? 'ring-2 ring-offset-2 ring-blue-400 scale-110' : 'group-hover:scale-105'">

                                @if($status === 'done')
                                    <i class="fas fa-check text-white text-xs"></i>
                                @elseif($status === 'active')
                                    <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                @elseif($colportageIsNextAction && $state === WF::COLPORTAGE_ACTIVE)
                                    <i class="fas fa-arrow-right text-white text-xs"></i>
                                @elseif($status === 'blocked')
                                    <span class="text-xs font-bold text-gray-400">{{ $def['badge'] }}</span>
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
                                @elseif($colportageIsNextAction && $state === WF::COLPORTAGE_ACTIVE)
                                    <span class="text-amber-600 font-semibold">{{ $def['title'] }}</span>
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
            $isNextAction   = $colportageIsNextAction && $state === WF::COLPORTAGE_ACTIVE && !$isDone;
        @endphp

        <div x-show="active === '{{ $state }}'"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="workflow-panel bg-white rounded-2xl border overflow-hidden"
             style="display:none;border-color:rgba(154,179,163,0.35);box-shadow:0 1px 6px rgba(0,0,0,0.06);">

            {{-- Panel header --}}
            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100 flex flex-wrap items-center gap-3"
                 style="@if($isDone) background:rgba(240,253,244,0.6) @elseif($isActive) background:rgba(239,246,255,0.8) @elseif($isNextAction) background:rgba(255,251,235,0.9) @else background:rgba(249,250,251,0.5) @endif">

                <span class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                      style="@if($isDone) background:linear-gradient(135deg,#059669,#047857) @elseif($isActive) background:linear-gradient(135deg,#2563eb,#1d4ed8) @elseif($isNextAction) background:linear-gradient(135deg,#d97706,#b45309) @else background:#e5e7eb @endif">
                    <i class="fas {{ $def['icon'] }} text-sm @if($isDone || $isActive || $isNextAction) text-white @else text-gray-400 @endif"></i>
                </span>

                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold
                            @if($isDone) text-emerald-800
                            @elseif($isActive) text-blue-800
                            @elseif($isNextAction) text-amber-800
                            @else text-gray-500 @endif">
                            ?tape {{ $def['badge'] }} ? {{ $def['title'] }}
                        </h3>
                        @if($isActive)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>En cours
                            </span>
                        @elseif($isDone)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                <i class="fas fa-check text-xs"></i>Termin?
                            </span>
                        @elseif($isNextAction)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                <i class="fas fa-arrow-right text-xs"></i>Prochaine action
                            </span>
                        @elseif($isBlocked && $stepInfo['blocked_reason'])
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                <i class="fas fa-lock text-xs"></i>Bloqu?
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
                                title="?tape pr?c?dente">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                    @endif
                    @if($idx < count($stateKeys) - 1)
                        <button @click="active = '{{ $stateKeys[$idx + 1] }}'"
                                class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors focus:outline-none"
                                title="?tape suivante">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Panel body --}}
            <div class="p-4 sm:p-5">
                @php $validateBlocked = false; $validateBlockedReason = null; @endphp

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
                    @else
                        {{-- Explicit validation button for step 1 --}}
                        <div class="mt-2 pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-600 mb-3">Vérifiez les informations ci-dessus puis validez la création de l'article pour continuer.</p>
                            <div class="flex gap-2 flex-wrap">
                                <a href="{{ route('articles.edit', $article) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-edit text-gray-500"></i> Modifier l'article
                                </a>
                                <form action="{{ route('workflow.validate', $article) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Attention : cette action est irréversible.\nUne fois validé, cet article ne pourra plus être modifié.\n\nConfirmez-vous la validation ?')"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                        <i class="fas fa-check"></i> Valider la création de l'article
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                {{-- ============================================================
                     Step 2 ? CONTRACT_CREATED
                ============================================================ --}}
                @elseif($state === WF::CONTRACT_CREATED)
                    @if(!$contractVente)
                        @php $validateBlocked = true; $validateBlockedReason = 'Un contrat de vente doit être créé avant de valider.'; @endphp
                    @elseif(!$contractVente->letter_signed_file)
                        @php $validateBlocked = true; $validateBlockedReason = 'La lettre adjudicataire signée doit être uploadée avant de valider.'; @endphp
                    @endif
                    @if(!$contractVente)
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
                            <div class="flex items-start gap-3 p-3 bg-amber-50 rounded-lg border border-amber-200 mb-4 text-sm">
                                <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
                                <span class="text-amber-800">Aucun contrat de vente. Créez-en un pour continuer.</span>
                            </div>
                            <a href="{{ route('contract-ventes.create', $article) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-plus"></i> Créer le contrat de vente
                            </a>
                        @endif
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
                            @if($contractVente->is_validated)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-green-50 border border-green-300 text-green-700 rounded-lg">
                                    <i class="fas fa-check-circle"></i> Contrat validé
                                </span>
                                <a href="{{ route('contract-ventes.show', [$article, $contractVente]) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye"></i> Consulter contrat
                                </a>
                            @else
                                <a href="{{ route('contract-ventes.edit', [$article, $contractVente]) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-edit text-gray-500"></i> Modifier
                                </a>
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
                    @elseif(!$contractVente->letter_signed_file)
                        @php $validateBlocked = true; $validateBlockedReason = 'La lettre adjudicataire signée doit être uploadée avant de valider.'; @endphp
                    @endif

                    {{-- Download generated letter --}}
                    @if($contractVente)
                        <div class="flex flex-wrap gap-3 mb-4">
                            <a href="{{ route('articles.lettre-adjudicataire.download-pdf', $article) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-pdf text-red-500"></i> Télécharger la lettre (.pdf)
                            </a>
                        </div>
                        @if($contractVente->letter_generated_at)
                            <p class="text-xs text-gray-500 mb-3">Générée le {{ $contractVente->letter_generated_at->format('d/m/Y à H:i') }}</p>
                        @endif
                    @endif

                    {{-- Signed letter status --}}
                    @if($contractVente?->letter_signed_file)
                        <div class="flex items-center gap-2 text-sm text-emerald-700 mb-4">
                            <i class="fas fa-check-circle"></i>
                            <span>Importée le {{ $contractVente->letter_signed_at?->format('d/m/Y') ?? '—' }}</span>
                            <a href="{{ route('workflow.view-signed-letter', $article) }}" target="_blank"
                               class="ml-2 inline-flex items-center gap-1 text-blue-600 hover:underline text-xs font-medium">
                                <i class="fas fa-file-pdf text-red-500"></i> Ouvrir le fichier
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-4">Importez la lettre adjudicataire signée par l'adjudicataire (PDF uniquement).</p>
                    @endif

                    {{-- Upload form: only shown when step is not yet validated --}}
                    @if($contractVente && !$isDone)
                        @if($errors->has('signed_letter'))
                            <div class="flex items-center gap-2 p-3 mb-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $errors->first('signed_letter') }}</span>
                            </div>
                        @endif
                        <form action="{{ route('workflow.upload-signed-letter', $article) }}" method="POST"
                              enctype="multipart/form-data" class="flex items-end gap-3 flex-wrap">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    {{ $contractVente->letter_signed_file ? 'Remplacer le fichier (PDF)' : 'Fichier signé (PDF uniquement)' }}
                                </label>
                                <input type="file" name="signed_letter" accept=".pdf"
                                       class="block text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-medium hover:file:bg-emerald-100">
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-upload"></i> Importer et valider
                            </button>
                        </form>
                    @elseif($isDone && $contractVente?->letter_signed_file)
                        <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-800">
                            <i class="fas fa-lock"></i>
                            <span>Document verrouillé — la lettre adjudicataire a été validée.</span>
                        </div>
                    @endif

                {{-- ============================================================
                     Step 6 — CAUTION_PAID
                ============================================================ --}}
                @elseif($state === WF::CAUTION_PAID)
                    @php
                        $cautionPayment   = $cautionCharge?->payments?->first();
                        $cautionDateLimite = null;
                        $cautionPayDate   = $cautionPayment?->date_payment ? \Carbon\Carbon::parse($cautionPayment->date_payment) : null;
                        $cautionDateDepassee = false;
                        $cautionOverdue   = false;
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
                    @elseif($cautionPayment?->is_paye)
                        {{-- ---- LOCKED: caution already validated ---- --}}
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

                        {{-- Déchéance — DRANEF only --}}
                        @can('forfeiture.create')
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
                    @else
                        {{-- ---- FORM: mark caution as paid ---- --}}
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
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                                    <i class="fas fa-times-circle"></i> Impayée
                                                </span>
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

                        {{-- Déchéance — DRANEF only, shown when overdue --}}
                        @if($cautionOverdue)
                            @can('forfeiture.create')
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
                    @endif

                {{-- ============================================================
                     Step 7 ? TAXES_PAID
                ============================================================ --}}
                @elseif($state === WF::TAXES_PAID)
                    @if(!$allTaxesPaid)
                        @php $validateBlocked = true; $validateBlockedReason = 'Toutes les taxes doivent être payées avant de valider.'; @endphp
                    @endif
                    @if($taxeCharges->isEmpty())
                        <p class="text-sm text-gray-400 italic">Aucune taxe configur?e dans le contrat.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Taxe</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Montant</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">?ch?ance</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Statut</th>
                                        <th class="text-left py-2 text-gray-600 font-medium">Actions</th>
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
                                            <td class="py-2">
                                                @if($payment?->is_paye)
                                                    {{-- LOCKED: taxe already validated --}}
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
                                                        <p class="text-xs text-gray-400 italic"><i class="fas fa-lock mr-0.5"></i>Modification non autorisée</p>
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
                                                                       class="w-4 h-4 rounded text-emerald-600">
                                                                Marquer payée
                                                            </label>
                                                            <input type="text" name="payments[{{ $charge->id }}][reference]"
                                                                   placeholder="N° quittance *"
                                                                   class="block w-32 px-2 py-1 text-xs border border-gray-300 rounded" required>
                                                            <input type="date" name="payments[{{ $charge->id }}][date_payment]"
                                                                   class="block w-32 px-2 py-1 text-xs border border-gray-300 rounded" required>
                                                            <div>
                                                                <label class="block text-xs text-gray-600 mb-0.5">Quittance <span class="text-red-500">*</span></label>
                                                                <input type="file" name="payments[{{ $charge->id }}][fichier_joint]"
                                                                       accept=".pdf,.jpg,.jpeg,.png"
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($allTaxesPaid)
                            <p class="mt-3 text-sm text-emerald-700 font-medium"><i class="fas fa-check-circle mr-1"></i>Toutes les taxes sont pay?es.</p>
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
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('articles.permis-exploiter', $article) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-edit text-gray-500"></i> Voir / modifier
                            </a>
                            <a href="{{ route('articles.print-permis-exploiter', $article) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-print"></i> Imprimer
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-4">Toutes les obligations financières sont remplies. Vous pouvez maintenant émettre le permis d'exploiter.</p>
                        <a href="{{ route('articles.permis-exploiter', $article) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-file-contract"></i> Gérer le permis d'exploiter
                        </a>
                    @endif

                {{-- ============================================================
                     Step 10 ? PV_INSTALLATION_DONE
                ============================================================ --}}
                @elseif($state === WF::PV_INSTALLATION_DONE)
                    @if(!$pvInstallation)
                        @php $validateBlocked = true; $validateBlockedReason = 'Le PV d\'installation doit ?tre rempli avant de valider.'; @endphp
                    @endif
                    @if($pvInstallation)
                        <div class="info-tiles grid grid-cols-2 md:grid-cols-3 gap-3 text-sm mb-4">
                            @foreach([
                                'N? PV'        => $pvInstallation->pvn ?? '?',
                                'Date'         => $pvInstallation->date ? \Carbon\Carbon::parse($pvInstallation->date)->format('d/m/Y') : '?',
                                'Exploitant'   => $pvInstallation->exploitant ?? '?',
                                'Participants' => $pvInstallation->participants ?? '?',
                                'R?serve'      => $pvInstallation->reserve ?? '?',
                            ] as $label => $value)
                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-500 mb-0.5">{{ $label }}</p>
                                <p class="font-semibold text-gray-800">{{ $value }}</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-4">PV d'installation non encore rempli.</p>
                    @endif
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('articles.pv-installation', $article) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-clipboard-check"></i> {{ $pvInstallation ? 'Voir / modifier' : 'Remplir le PV' }}
                        </a>
                        @can('vehicle.declare')
                        <a href="{{ route('vehicles.index', $article) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition-colors">
                            <i class="fas fa-truck"></i> G&eacute;rer les v&eacute;hicules
                        </a>
                        @endcan
                        @if($pvInstallation)
                        <a href="{{ route('articles.pv-installation.print', $article) }}"
                           target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-print text-gray-500"></i> Imprimer
                        </a>
                        @endif
                    </div>

                {{-- ============================================================
                     Étape 8 — TRANCHES_IN_PROGRESS
                ============================================================ --}}
                @elseif($state === WF::TRANCHES_IN_PROGRESS)
                    @php
                        $permisDates = collect($permisEnlevers ?? [])
                            ->pluck('date_paiement')
                            ->filter()
                            ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
                            ->unique()
                            ->values()
                            ->all();
                    @endphp
                    @if($tranches->isEmpty())
                        <p class="text-sm text-gray-400 italic">Aucune tranche configur?e dans le contrat.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Tranche</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Montant</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">?ch?ance</th>
                                        <th class="text-left py-2 pr-4 text-gray-600 font-medium">Statut</th>
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
                                                <td class="py-2">
                                                    @php
                                                        $paymentDate = $tp?->date_payment
                                                            ? \Carbon\Carbon::parse($tp->date_payment)->format('Y-m-d')
                                                            : null;
                                                        $hasPermisForDate = $paymentDate && in_array($paymentDate, $permisDates, true);
                                                    @endphp
                                                    @if(!$tp?->is_paye)
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
                                                                <a href="{{ route('articles.permis-enlever', $article) }}"
                                                                   class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                                    <i class="fas fa-eye"></i> Voir permis d'enlever
                                                                </a>
                                                            @else
                                                                <a href="{{ route('articles.permis-enlever', ['article' => $article, 'action' => 'create', 'date_paiement' => $paymentDate]) }}"
                                                                   class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700">
                                                                    <i class="fas fa-plus"></i> Cr?er permis d'enlever
                                                                </a>
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
                    @endif

                    {{-- Next action: Colportage --}}
                    @if($colportageIsNextAction)
                        <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-arrow-right text-amber-500 text-sm"></i>
                                <span class="text-sm font-semibold text-amber-800">Un permis d'enlever a ?t? cr??. Vous pouvez maintenant cr?er un permis de colportage.</span>
                            </div>
                            <button type="button"
                                    @click="active = '{{ WF::COLPORTAGE_ACTIVE }}'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors flex-shrink-0">
                                <i class="fas fa-shipping-fast"></i> Aller au Colportage
                            </button>
                        </div>
                    @endif

                    {{-- Dénombrement --}}
                    <div class="mt-4 pt-4 border-t border-gray-100" x-data="{ showDenombrement: {{ $denombrements->isNotEmpty() ? 'true' : 'false' }} }">
                        <label class="inline-flex items-center gap-2 cursor-pointer mb-3">
                            <input type="checkbox" x-model="showDenombrement" class="w-4 h-4 rounded text-emerald-600">
                            <span class="text-sm font-semibold text-gray-700">Dénombrement</span>
                        </label>
                        <div x-show="showDenombrement">
                            @if($denombrements->isNotEmpty())
                                <div class="space-y-2 mb-3">
                                    @foreach($denombrements as $denombrement)
                                        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-xs">
                                            <div class="flex items-center justify-between gap-3">
                                                <div>
                                                    <p class="font-medium text-emerald-800">{{ $denombrement->date_denombrement?->format('d/m/Y') ?? '—' }}</p>
                                                    <p class="text-emerald-600">Agent : {{ $denombrement->agent_responsable ?? '—' }} | Volume : {{ $denombrement->volume_denombre ?? '—' }}</p>
                                                </div>
                                                @if($denombrement->fichier_pv)
                                                    <a href="{{ route('articles.denombrement.download', $article) }}" target="_blank"
                                                       class="inline-flex items-center gap-1 px-2 py-1 text-xs text-blue-600 border border-blue-200 rounded hover:bg-blue-50">
                                                        <i class="fas fa-file-alt"></i> Voir PV
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <a href="{{ route('articles.denombrement', $article) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-plus"></i> {{ $denombrements->isNotEmpty() ? 'Nouveau dénombrement' : 'Saisir un dénombrement' }}
                            </a>
                        </div>
                    </div>

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

                    {{-- Prorogations sub-section --}}
                    @if($contractVente)
                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Prorogations</p>
                            @if($prorogations->isNotEmpty())
                                <div class="space-y-2 mb-3">
                                    @foreach($prorogations as $pror)
                                        <div class="flex items-center gap-3 text-xs p-2 bg-gray-50 rounded border border-gray-100">
                                            <span class="font-medium">+{{ $pror->duration_months }} mois</span>
                                            <span class="text-gray-500">{{ $pror->motif }}</span>
                                            <span class="ml-auto px-1.5 py-0.5 rounded-full font-medium
                                                @if($pror->status === 'approved') bg-emerald-100 text-emerald-700
                                                @elseif($pror->status === 'rejected') bg-red-100 text-red-600
                                                @else bg-amber-100 text-amber-700 @endif">
                                                {{ ['pending' => 'En attente', 'approved' => 'Approuv?e', 'rejected' => 'Refus?e'][$pror->status] ?? $pror->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <a href="{{ route('workflow.prorogation.create', $article) }}"
                               class="inline-flex items-center gap-1 text-xs text-emerald-700 hover:underline">
                                <i class="fas fa-calendar-plus"></i> Nouvelle demande de prorogation
                            </a>
                        </div>
                    @endif

                {{-- ============================================================
                     Step 13 ? COLPORTAGE_ACTIVE
                ============================================================ --}}
                @elseif($state === WF::COLPORTAGE_ACTIVE)
                    @php
                        $permisEnleverList = $permisEnlevers ?? collect();
                        $selectedPEId = request('colportage_pe_' . $article->id);
                        $selectedPE   = $selectedPEId ? $permisEnleverList->firstWhere('id', (int)$selectedPEId) : $permisEnleverList->first();
                    @endphp

                    {{-- Permis d'Enlever selector --}}
                    @if($permisEnleverList->isEmpty())
                        <div class="text-sm text-gray-500 mb-4">
                            Aucun permis d'enlever disponible.
                            <a href="{{ route('articles.permis-enlever', $article) }}" class="text-emerald-600 hover:underline ml-1">Cr?er un permis d'enlever</a>
                        </div>
                    @else
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <select id="colportage-pe-select-{{ $article->id }}"
                                    class="flex-1 min-w-0 max-w-sm px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @foreach($permisEnleverList as $pe)
                                    <option value="{{ $pe->id }}" {{ optional($selectedPE)->id == $pe->id ? 'selected' : '' }}>
                                        Permis #{{ $pe->id }}
                                        @if($pe->date) ? {{ $pe->date->format('d/m/Y') }} @endif
                                        @if($pe->num_quittance) ? {{ $pe->num_quittance }} @endif
                                        ({{ $pe->colportages->count() }} colportage(s))
                                    </option>
                                @endforeach
                            </select>

                            @if($selectedPE)
                                <a href="{{ route('articles.permis-colportage.create', [$article, 'permis_enlever_id' => $selectedPE->id]) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors flex-shrink-0">
                                    <i class="fas fa-plus"></i> Nouveau permis de colportage
                                </a>
                            @endif

                            <a href="{{ route('articles.permis-enlever', $article) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex-shrink-0">
                                <i class="fas fa-file-alt text-gray-500"></i> Permis d'enlever
                            </a>
                        </div>

                        {{-- Colportage datatable --}}
                        @if($selectedPE)
                            @php
                                $colportageRows = $selectedPE->colportages;
                                $peVolume = (float)($selectedPE->volume ?? 0);
                            @endphp
                            <div class="border border-gray-200 rounded-lg overflow-hidden mb-4" id="colportage-table-{{ $article->id }}">
                                <div class="flex items-center justify-between px-4 py-2 bg-gray-50 border-b border-gray-200">
                                    <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                        Permis de colportage ? {{ $colportageRows->count() }} enregistrement(s)
                                    </span>
                                    <input type="text"
                                           placeholder="Rechercher?"
                                           class="colportage-search px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-emerald-500 w-32"
                                           data-table="colportage-tbody-{{ $article->id }}">
                                </div>

                                @if($colportageRows->isEmpty())
                                    <div class="px-4 py-6 text-center text-sm text-gray-400">
                                        <i class="fas fa-truck text-2xl mb-2 block opacity-30"></i>
                                        Aucun permis de colportage pour ce permis d'enlever.
                                    </div>
                                @else
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-xs divide-y divide-gray-100">
                                            <thead class="bg-emerald-50 text-emerald-800 uppercase font-semibold">
                                                <tr>
                                                    <th class="px-3 py-2 text-left">N? Permis</th>
                                                    <th class="px-3 py-2 text-left">Date d?but</th>
                                                    <th class="px-3 py-2 text-left">Date fin</th>
                                                    <th class="px-3 py-2 text-left">V?hicule</th>
                                                    <th class="px-3 py-2 text-left">Consommation</th>
                                                </tr>
                                            </thead>
                                            <tbody id="colportage-tbody-{{ $article->id }}" class="bg-white divide-y divide-gray-50">
                                                @foreach($colportageRows as $cr)
                                                @php
                                                    $crVol = (float)($cr->volume ?? 0);
                                                    $crPct = $peVolume > 0 ? round(($crVol / $peVolume) * 100, 1) : 0;
                                                @endphp
                                                <tr class="hover:bg-emerald-50 transition-colors">
                                                    <td class="px-3 py-2 font-medium text-gray-900">{{ $cr->numero_permis ?? '?' }}</td>
                                                    <td class="px-3 py-2 text-gray-700">{{ $cr->date_debut ? $cr->date_debut->format('d/m/Y H:i') : '?' }}</td>
                                                    <td class="px-3 py-2 text-gray-700">{{ $cr->date_fin ? $cr->date_fin->format('d/m/Y H:i') : '?' }}</td>
                                                    <td class="px-3 py-2 text-gray-700">{{ $cr->vehicule_immatriculation ?? '?' }}</td>
                                                    <td class="px-3 py-2">
                                                        @if($peVolume > 0)
                                                            <div class="flex items-center gap-2">
                                                                <div class="w-20 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                                                    <div class="h-1.5 rounded-full {{ $crPct >= 100 ? 'bg-red-500' : 'bg-emerald-500' }}"
                                                                         style="width: {{ min($crPct, 100) }}%"></div>
                                                                </div>
                                                                <span class="font-semibold text-emerald-700 whitespace-nowrap">{{ $crPct }}%</span>
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif

                    {{-- Prorogations --}}
                    @if($contractVente && $prorogations->isNotEmpty())
                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Prorogations</p>
                            <div class="space-y-2 mb-2">
                                @foreach($prorogations as $pror)
                                    <div class="flex items-center gap-3 text-xs p-2 bg-gray-50 rounded border border-gray-100">
                                        <span class="font-medium">+{{ $pror->duration_months }} mois</span>
                                        <span class="text-gray-500">{{ $pror->motif }}</span>
                                        <span class="ml-auto px-1.5 py-0.5 rounded-full font-medium
                                            @if($pror->status === 'approved') bg-emerald-100 text-emerald-700
                                            @elseif($pror->status === 'rejected') bg-red-100 text-red-600
                                            @else bg-amber-100 text-amber-700 @endif">
                                            {{ ['pending' => 'En attente', 'approved' => 'Approuv?e', 'rejected' => 'Refus?e'][$pror->status] ?? $pror->status }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('workflow.prorogation.create', $article) }}"
                               class="inline-flex items-center gap-1 text-xs text-emerald-700 hover:underline">
                                <i class="fas fa-calendar-plus"></i> Nouvelle demande de prorogation
                            </a>
                        </div>
                    @endif

                {{-- ============================================================
                     Step 14 ? RECOLEMENT_PENDING
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
                                    @elseif($recolement->status === 'mainlevee_issued') <span class="text-emerald-600">Mainlev?e ?mise</span>
                                    @elseif($recolement->status === 'closed') <span class="text-gray-600">Cl?tur?</span>
                                    @else <span class="text-amber-600">En attente</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-4">Aucun PV de r?colement soumis.</p>
                    @endif
                    <a href="{{ route('workflow.recolement.create', $article) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-plus"></i> {{ $recolement ? 'Modifier le PV' : 'Soumettre le PV de r?colement' }}
                    </a>

                {{-- ============================================================
                     Step 15 ? MAINLEVEE_DONE
                ============================================================ --}}
                @elseif($state === WF::MAINLEVEE_DONE)
                    @if($recolement?->date_mainlevee)
                        <div class="info-tiles grid grid-cols-2 gap-3 text-sm mb-4">
                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-500 mb-0.5">N? mainlev?e</p>
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
                                <i class="fas fa-file-pdf text-red-500"></i> Voir le fichier mainlev?e
                            </a>
                        @endif
                    @else
                        <p class="text-sm text-gray-600 mb-4">Mainlev?e non encore ?mise.</p>
                        @if($recolement?->status === 'pv_submitted')
                        <form action="{{ route('workflow.mainlevee.issue', $article) }}" method="POST"
                              enctype="multipart/form-data" class="space-y-3 max-w-md">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">N? mainlev?e <span class="text-red-500">*</span></label>
                                <input type="text" name="num_mainlevee" required
                                       class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Date mainlev?e <span class="text-red-500">*</span></label>
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
                                <i class="fas fa-stamp"></i> ?mettre la mainlev?e
                            </button>
                        </form>
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
                                <p class="text-sm font-semibold text-gray-700">Dossier cl?tur?</p>
                                <p class="text-xs text-gray-500">Ce dossier est d?finitivement archiv?.</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-4">Cl?turez d?finitivement ce dossier apr?s ?mission de la mainlev?e.</p>
                        @if($wfState === WF::MAINLEVEE_DONE)
                        <form action="{{ route('workflow.close', $article) }}" method="POST"
                              onsubmit="return confirm('?tes-vous s?r de vouloir cl?turer d?finitivement ce dossier ?')">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                                <i class="fas fa-archive"></i> Cl?turer le dossier
                            </button>
                        </form>
                        @endif
                    @endif
                @endif

                @php
                    $transitionStateKeys = array_keys($allStepDefs);
                    $transitionIndex = array_search($state, $transitionStateKeys, true);
                    $workflowStateIndex = array_search($wfState, $transitionStateKeys, true);
                    $nextTransitionState = $transitionIndex !== false && $transitionIndex < count($transitionStateKeys) - 1
                        ? $transitionStateKeys[$transitionIndex + 1]
                        : null;
                    $transitionTargetState = $isActive
                        ? (($workflowStateIndex !== false && $transitionIndex !== false && $transitionIndex > $workflowStateIndex)
                            ? $state
                            : $nextTransitionState)
                        : null;
                    $showTransitionButton = $isActive
                        && $transitionTargetState !== null
                        // DRAFT_ARTICLE uses its own explicit validation form
                        && $state !== WF::DRAFT_ARTICLE;
                    $transitionTargetDef = $transitionTargetState ? (($visibleStepDefs[$transitionTargetState] ?? $allStepDefs[$transitionTargetState]) ?? null) : null;
                    $isValidatingCurrentStep = $transitionTargetState === $state;
                @endphp

                @if($showTransitionButton)
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        @if($validateBlocked)
                            {{-- Blocked: prerequisite not met --}}
                            <div class="flex items-center gap-3 p-4 rounded-xl"
                                 style="background:rgba(254,243,199,0.6);border:1px solid rgba(217,119,6,0.25);">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-amber-100">
                                    <i class="fas fa-lock text-amber-600 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-amber-800 mb-0.5">Validation bloqu?e</p>
                                    <p class="text-xs text-amber-700">{{ $validateBlockedReason }}</p>
                                </div>
                                <button type="button" disabled
                                        class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg cursor-not-allowed opacity-50"
                                        style="background:#9ca3af;">
                                    <i class="fas fa-lock"></i>
                                    Valider
                                </button>
                            </div>
                        @else
                            {{-- Ready: show validate card --}}
                            <div class="flex items-center gap-3 p-4 rounded-xl"
                                 style="background:linear-gradient(135deg,rgba(5,150,105,0.06) 0%,rgba(16,185,129,0.04) 100%);border:1px solid rgba(5,150,105,0.2);">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background:linear-gradient(135deg,#059669,#047857);">
                                    <i class="fas fa-arrow-right text-white text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-emerald-800 mb-0.5">
                                        {{ $isValidatingCurrentStep ? '?tape ? valider' : 'Prochaine ?tape' }}
                                    </p>
                                    <p class="text-xs text-emerald-700 truncate">
                                        ?tape {{ $transitionTargetDef['badge'] ?? '' }} ? {{ $transitionTargetDef['title'] ?? '' }}
                                    </p>
                                </div>
                                <form action="{{ route('workflow.transition', $article) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    <input type="hidden" name="state" value="{{ $transitionTargetState }}">
                                    <button type="submit"
                                            onclick="return confirm(@if($state === \App\Services\ArticleWorkflowService::CAUTION_PAID)'Confirmez-vous la validation du paiement de la caution ?\n\nCette action est irréversible.'@else'Attention : cette action est irréversible. Confirmez-vous ?'@endif)"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all duration-150 hover:shadow-md active:scale-95"
                                            style="background:linear-gradient(135deg,#059669,#047857);box-shadow:0 2px 8px rgba(5,150,105,0.3);">
                                        <i class="fas fa-check-circle"></i>
                                        Valider
                                    </button>
                                </form>
                            </div>
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

            </div>{{-- end panel body --}}

            {{-- Panel footer: progress indicator --}}
            <div class="px-4 sm:px-5 py-2.5 border-t border-gray-100 bg-gray-50 flex items-center justify-between text-xs text-gray-400">
                @php $allKeys = array_keys($visibleStepDefs); $pos = array_search($state, $allKeys) + 1; @endphp
                <span>?tape {{ $pos }} / {{ count($visibleStepDefs) }}</span>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // �f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬�f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬ Permis d'Enlever selector in Step 13: reload page with selected PE �f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬�f�'�,¢�f¢â�?s¬�,�f¢â�,�š�,¬
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

</div>
