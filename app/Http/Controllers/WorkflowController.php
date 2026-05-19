<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Models\Alert;
use App\Models\Article;
use App\Models\ContractVente;
use App\Models\Prorogation;
use App\Models\Recolement;
use App\Services\AlertService;
use App\Services\ArticleWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkflowController extends Controller
{
    public function __construct(
        private readonly ArticleWorkflowService $workflow,
        private readonly AlertService $alertService,
    ) {}

    // -------------------------------------------------------------------------
    // Workflow state transition (generic — called by specific action handlers)
    // -------------------------------------------------------------------------

    /**
     * Manually advance workflow state (admin/debug use; production actions
     * should use the specific action methods below).
     */
    public function transition(Request $request, Article $article): RedirectResponse
    {
        $this->authorize('view', $article);
        $request->validate(['state' => 'required|string']);

        try {
            $this->workflow->transition($article, $request->state, Auth::id());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['workflow' => $e->getMessage()]);
        }

        // Lock the contract when step 2 is explicitly validated via the generic endpoint
        if ($request->state === ArticleWorkflowService::CONTRACT_CREATED) {
            $contract = $article->contractVentes()->latest()->first();
            if ($contract && !$contract->is_validated) {
                $contract->update(['is_validated' => true]);
            }
        }

        return back()->with('success', 'État du dossier mis à jour.');
    }

    // -------------------------------------------------------------------------
    // Letter management
    // -------------------------------------------------------------------------

    /**
     * Explicitly validate the article creation step (DRAFT_ARTICLE → ARTICLE_READY).
     */
    public function validateArticle(Article $article): RedirectResponse
    {
        $this->authorize('update', $article);
        try {
            $this->workflow->transition($article, ArticleWorkflowService::ARTICLE_READY, Auth::id());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['workflow' => $e->getMessage()]);
        }

        return back()->with('success', 'Article validé. Vous pouvez maintenant créer le contrat de vente.');
    }

    /**
     * Upload the signed adjudication letter.
     */
    public function uploadSignedLetter(Request $request, Article $article): RedirectResponse
    {
        $this->authorize(Permission::ADJUDICATAIRE_LETTER_UPLOAD_SIGNED);

        if ($this->workflow->isAtOrPast($article, ArticleWorkflowService::LETTER_SIGNED_UPLOADED)) {
            return back()->withErrors(['workflow' => 'Cette étape est déjà validée. La modification du document n\'est plus autorisée.']);
        }

        $request->validate([
            'signed_letter' => 'required|file|mimes:pdf|max:10240',
        ], [
            'signed_letter.mimes' => 'Seuls les fichiers PDF sont acceptés.',
        ]);

        $contract = $article->contractVentes()->latest()->firstOrFail();

        // Delete old file if it exists
        if ($contract->letter_signed_file && Storage::disk('public')->exists($contract->letter_signed_file)) {
            Storage::disk('public')->delete($contract->letter_signed_file);
        }

        $path = $request->file('signed_letter')->store('letters/signed', 'public');
        $contract->update([
            'letter_signed_file' => $path,
            'letter_signed_at'   => now(),
        ]);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Lettre signée importée avec succès. Validez manuellement pour passer à l\'étape suivante.');
    }

    /**
     * Manually validate the adjudication letter step (CONTRACT_CREATED → LETTER_SIGNED_UPLOADED).
     */
    public function validateSignedLetter(Article $article): RedirectResponse
    {
        $this->authorize(Permission::ADJUDICATAIRE_LETTER_UPLOAD_SIGNED);

        $contract = $article->contractVentes()->latest()->firstOrFail();

        if (!$contract->letter_signed_file) {
            return back()->withErrors(['workflow' => 'La lettre adjudicataire signée doit être importée avant de valider.']);
        }

        $current = $article->workflow_state ?? ArticleWorkflowService::DRAFT_ARTICLE;
        $order = ArticleWorkflowService::STATE_ORDER;
        if (($order[$current] ?? 0) < ($order[ArticleWorkflowService::CONTRACT_CREATED] ?? 0)) {
            return back()->withErrors(['workflow' => 'Le contrat de vente doit être validé avant cette étape.']);
        }
        if (($order[$current] ?? 0) > ($order[ArticleWorkflowService::CONTRACT_CREATED] ?? 0)) {
            return back()->with('info', 'Cette étape a déjà été validée.');
        }

        try {
            $this->workflow->transition($article, ArticleWorkflowService::LETTER_SIGNED_UPLOADED, Auth::id());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['workflow' => $e->getMessage()]);
        }

        return redirect()->route('articles.show', $article)
            ->with('success', 'Lettre adjudicataire validée. Statut mis à jour.');
    }

    public function viewSignedLetter(Article $article): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
    {
        $contract = $article->contractVentes()->latest()->firstOrFail();

        if (!$contract->letter_signed_file || !Storage::disk('public')->exists($contract->letter_signed_file)) {
            return back()->withErrors(['letter' => 'Fichier introuvable.']);
        }

        $mimeType = Storage::disk('public')->mimeType($contract->letter_signed_file) ?: 'application/pdf';

        return Storage::disk('public')->response(
            $contract->letter_signed_file,
            null,
            ['Content-Type' => $mimeType, 'Content-Disposition' => 'inline']
        );
    }

    // -------------------------------------------------------------------------
    // PV d'installation
    // -------------------------------------------------------------------------

    /**
     * Upload the signed PV d'installation file.
     */
    public function uploadSignedPv(Request $request, Article $article): RedirectResponse
    {
        $this->authorize(Permission::INSTALLATION_REPORT_UPLOAD_SIGNED);

        if ($this->workflow->isAtOrPast($article, ArticleWorkflowService::PV_INSTALLATION_DONE)) {
            return back()->withErrors(['workflow' => 'Cette étape est déjà validée. La modification du document n\'est plus autorisée.']);
        }

        $request->validate([
            'fichier_pv_signe' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'fichier_pv_signe.mimes' => 'Seuls les fichiers PDF, JPG et PNG sont acceptés.',
        ]);

        $contract = $article->contractVentes()->latest()->firstOrFail();
        $pvInstallation = $contract->pvInstallations()->latest()->firstOrFail();

        if ($pvInstallation->fichier_pv_signe && Storage::disk('public')->exists($pvInstallation->fichier_pv_signe)) {
            Storage::disk('public')->delete($pvInstallation->fichier_pv_signe);
        }

        $path = $request->file('fichier_pv_signe')->store('pv-installations/signes', 'public');
        $pvInstallation->update([
            'fichier_pv_signe' => $path,
            'pv_signed_at'     => now(),
        ]);

        return redirect()->route('articles.show', $article)
            ->with('success', 'PV signé importé avec succès. Validez manuellement pour passer à l\'étape suivante.');
    }

    public function viewSignedPv(Article $article): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
    {
        $contract = $article->contractVentes()->latest()->firstOrFail();
        $pvInstallation = $contract->pvInstallations()->latest()->firstOrFail();

        if (!$pvInstallation->fichier_pv_signe || !Storage::disk('public')->exists($pvInstallation->fichier_pv_signe)) {
            return back()->withErrors(['pv' => 'Fichier introuvable.']);
        }

        $mimeType = Storage::disk('public')->mimeType($pvInstallation->fichier_pv_signe) ?: 'application/pdf';

        return Storage::disk('public')->response(
            $pvInstallation->fichier_pv_signe,
            null,
            ['Content-Type' => $mimeType, 'Content-Disposition' => 'inline']
        );
    }

    // -------------------------------------------------------------------------
    // Prorogation
    // -------------------------------------------------------------------------

    public function createProrogation(Article $article): \Illuminate\View\View
    {
        $this->authorize('prorogation.request');
        $contract = $article->contractVentes()->latest()->firstOrFail();

        return view('workflow.prorogation.create', compact('article', 'contract'));
    }

    public function storeProrogation(Request $request, Article $article): RedirectResponse
    {
        $this->authorize('prorogation.request');
        $request->validate([
            'duration_months' => 'required|integer|min:1|max:60',
            'motif'           => 'required|string|max:1000',
            'document'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $contract = $article->contractVentes()->latest()->firstOrFail();

        // Block if a prorogation is already pending
        if ($contract->prorogations()->where('status', Prorogation::STATUS_PENDING)->exists()) {
            return back()->withErrors(['prorogation' => 'Une prorogation est déjà en attente d\'approbation.']);
        }

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('prorogations', 'public');
        }

        $contract->prorogations()->create([
            'duration_months'     => $request->duration_months,
            'motif'               => $request->motif,
            'status'              => Prorogation::STATUS_PENDING,
            'original_expiry_date'=> $contract->date_expiration,
            'requested_by'        => Auth::id(),
            'document'            => $documentPath,
        ]);

        return redirect()->route('cessions.show', $article->groupe_cession_id)
            ->with('success', 'Demande de prorogation soumise.');
    }

    public function approveProrogation(Request $request, Prorogation $prorogation): RedirectResponse
    {
        $this->authorize('prorogation.approve');
        $request->validate(['decision_note' => 'nullable|string|max:500']);

        if (!$prorogation->isPending()) {
            return back()->withErrors(['prorogation' => 'Cette prorogation a déjà été traitée.']);
        }

        $contract = $prorogation->contractVente;
        $newExpiry = ($contract->date_expiration ?? now()->toDate())
            ->copy()
            ->addMonths($prorogation->duration_months);

        $prorogation->update([
            'status'        => Prorogation::STATUS_APPROVED,
            'new_expiry_date'=> $newExpiry,
            'decided_by'    => Auth::id(),
            'decided_at'    => now(),
            'decision_note' => $request->decision_note,
        ]);

        $contract->update(['date_expiration' => $newExpiry]);

        // Resolve any expiration alert since expiry date pushed forward
        $this->alertService->resolve(
            Alert::TYPE_EXPIRATION_CONTRAT,
            Article::class,
            $contract->article_id,
            'Prorogation approuvée',
            Auth::id()
        );

        $article = $contract->article;

        return redirect()->route('articles.show', $article)
            ->with('success', 'Prorogation approuvée. Nouvelle date d\'expiration : ' . $newExpiry->format('d/m/Y'));
    }

    public function rejectProrogation(Request $request, Prorogation $prorogation): RedirectResponse
    {
        $this->authorize('prorogation.approve');
        $request->validate(['decision_note' => 'required|string|max:500']);

        if (!$prorogation->isPending()) {
            return back()->withErrors(['prorogation' => 'Cette prorogation a déjà été traitée.']);
        }

        $prorogation->update([
            'status'       => Prorogation::STATUS_REJECTED,
            'decided_by'   => Auth::id(),
            'decided_at'   => now(),
            'decision_note'=> $request->decision_note,
        ]);

        $article = $prorogation->contractVente->article;
        try {
            $this->workflow->transition($article, ArticleWorkflowService::TRANCHES_IN_PROGRESS, Auth::id());
        } catch (\RuntimeException) {}

        return redirect()->route('articles.show', $article)
            ->with('info', 'Prorogation refusée.');
    }

    // -------------------------------------------------------------------------
    // Récolement & Mainlevée
    // -------------------------------------------------------------------------

    public function createRecolement(Article $article): \Illuminate\View\View
    {
        $this->authorize('recolement.submit');
        $contract = $article->contractVentes()->latest()->firstOrFail();
        $recolement = $contract->recolement ?? new Recolement(['status' => Recolement::STATUS_PENDING_PV]);

        return view('workflow.recolement.create', compact('article', 'contract', 'recolement'));
    }

    public function storeRecolement(Request $request, Article $article): RedirectResponse
    {
        $this->authorize('recolement.submit');
        $request->validate([
            'date_recolement'          => 'nullable|date',
            'adjudication'             => 'nullable|string|max:120',
            'num_marche'               => 'nullable|string|max:80',
            'commission'               => 'nullable|array',
            'commission.*.nom_prenom'  => 'nullable|string|max:120',
            'commission.*.fonction'    => 'nullable|string|max:120',
            'commission.*.entite'      => 'nullable|string|max:120',
            'marteau'                  => 'nullable|string|max:80',
            'marque'                   => 'nullable|string|max:80',
            'souches_reserves'                      => 'nullable|array',
            'souches_reserves.*.essence'            => 'nullable|string|max:120',
            'souches_reserves.*.avec_empreinte'     => 'nullable|numeric|min:0',
            'souches_reserves.*.sans_empreinte'     => 'nullable|numeric|min:0',
            'souches_reserves.*.total'              => 'nullable|numeric|min:0',
            'souches_reserves.*.nombre_pv'          => 'nullable|numeric|min:0',
            'la_coupe'                 => 'nullable|string|max:120',
            'les_limites'              => 'nullable|string|max:120',
            'le_vidange'               => 'nullable|string|max:120',
            'nettoyage_coupe'          => 'nullable|string|max:120',
            'le_recru'                 => 'nullable|string|max:120',
            'travaux_imposes'          => 'nullable|string|max:120',
            'fourniture_mise_en_charge'=> 'nullable|string|max:120',
            'delits_constates'         => 'nullable|string|max:120',
            'bois_oeuvre'              => 'nullable|numeric|min:0',
            'bois_industrie'           => 'nullable|numeric|min:0',
            'bois_service'             => 'nullable|numeric|min:0',
            'bois_chauffage'           => 'nullable|numeric|min:0',
            'brins_cedre'              => 'nullable|integer|min:0',
            'liege_male'               => 'nullable|numeric|min:0',
            'liege_reproduction'       => 'nullable|numeric|min:0',
            'ecorce_tanin'             => 'nullable|numeric|min:0',
            'bois_carboniser'          => 'nullable|numeric|min:0',
            'produits_abandonnes'             => 'nullable|array',
            'produits_abandonnes.*.nature'    => 'nullable|string|max:120',
            'produits_abandonnes.*.quantite'  => 'nullable|string|max:80',
            'date_pv'      => 'required|date',
            'num_pv'       => 'required|string|max:80',
            'observations' => 'nullable|string',
            'fichier_pv'   => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $contract = $article->contractVentes()->latest()->firstOrFail();

        $recolement = $contract->recolement ?? new Recolement(['contract_vente_id' => $contract->id]);

        $filePath = $recolement->fichier_pv;
        if ($request->hasFile('fichier_pv')) {
            $filePath = $request->file('fichier_pv')->store('recolements', 'public');
        }

        $commission = collect($request->input('commission', []))
            ->filter(fn($r) => !empty($r['nom_prenom']) || !empty($r['fonction']) || !empty($r['entite']))
            ->values()->all();

        $souchesReserves = collect($request->input('souches_reserves', []))
            ->filter(fn($r) => !empty($r['essence']))
            ->values()->all();

        $produitsAbandonnes = collect($request->input('produits_abandonnes', []))
            ->filter(fn($r) => !empty($r['nature']))
            ->values()->all();

        $recolement->fill([
            'date_recolement'           => $request->date_recolement ?: null,
            'adjudication'              => $request->adjudication,
            'num_marche'                => $request->num_marche,
            'commission'                => $commission ?: null,
            'marteau'                   => $request->marteau,
            'marque'                    => $request->marque,
            'souches_reserves'          => $souchesReserves ?: null,
            'la_coupe'                  => $request->la_coupe,
            'les_limites'               => $request->les_limites,
            'le_vidange'                => $request->le_vidange,
            'nettoyage_coupe'           => $request->nettoyage_coupe,
            'le_recru'                  => $request->le_recru,
            'travaux_imposes'           => $request->travaux_imposes,
            'fourniture_mise_en_charge' => $request->fourniture_mise_en_charge,
            'delits_constates'          => $request->delits_constates,
            'bois_oeuvre'               => $request->bois_oeuvre,
            'bois_industrie'            => $request->bois_industrie,
            'bois_service'              => $request->bois_service,
            'bois_chauffage'            => $request->bois_chauffage,
            'brins_cedre'               => $request->brins_cedre,
            'liege_male'                => $request->liege_male,
            'liege_reproduction'        => $request->liege_reproduction,
            'ecorce_tanin'              => $request->ecorce_tanin,
            'bois_carboniser'           => $request->bois_carboniser,
            'produits_abandonnes'       => $produitsAbandonnes ?: null,
            'date_pv'      => $request->date_pv,
            'num_pv'       => $request->num_pv,
            'observations' => $request->observations,
            'fichier_pv'   => $filePath,
            'submitted_by' => Auth::id(),
            'status'       => Recolement::STATUS_PV_SUBMITTED,
        ])->save();

        return redirect()->route('articles.show', $article)
            ->with('success', 'PV de récolement soumis. Validez manuellement pour passer à l\'étape suivante.');
    }

    public function issueMainlevee(Request $request, Article $article): RedirectResponse
    {
        $this->authorize('mainlevee.issue');
        $request->validate([
            'num_mainlevee'   => 'required|string|max:80',
            'date_mainlevee'  => 'required|date',
            'fichier_mainlevee'=> 'nullable|file|mimes:pdf|max:10240',
        ]);

        $contract = $article->contractVentes()->latest()->firstOrFail();
        $recolement = $contract->recolement;
        if (!$recolement || $recolement->status !== Recolement::STATUS_PV_SUBMITTED) {
            return back()->withErrors(['recolement' => 'Le PV de récolement doit être soumis avant d\'émettre la mainlevée.']);
        }

        $filePath = null;
        if ($request->hasFile('fichier_mainlevee')) {
            $filePath = $request->file('fichier_mainlevee')->store('mainlevees', 'public');
        }

        $recolement->update([
            'num_mainlevee'       => $request->num_mainlevee,
            'date_mainlevee'      => $request->date_mainlevee,
            'fichier_mainlevee'   => $filePath,
            'mainlevee_issued_by' => Auth::id(),
            'status'              => Recolement::STATUS_MAINLEVEE_ISSUED,
        ]);

        try {
            $this->workflow->transition($article, ArticleWorkflowService::MAINLEVEE_DONE, Auth::id());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['workflow' => $e->getMessage()]);
        }

        return redirect()->route('articles.show', $article)
            ->with('success', 'Mainlevée émise avec succès.');
    }

    public function cautionDecheance(Article $article): RedirectResponse
    {
        $this->authorize('forfeiture.create');

        $contract = $article->contractVentes()->with('chargeApayer.payments')->latest()->first();
        if (!$contract) {
            return back()->withErrors(['workflow' => 'Aucun contrat trouvé.']);
        }

        // Check caution is NOT already paid
        $cautionCharge = $contract->chargeApayer
            ->first(fn($c) => str_contains(strtolower($c->nom), 'caution'));
        $cautionPaid = (bool) $cautionCharge?->payments?->first()?->is_paye;
        if ($cautionPaid) {
            return back()->withErrors(['workflow' => 'La caution a déjà été payée. La mise en déchéance n\'est pas applicable.']);
        }

        // Check that the caution deadline has actually passed
        if ($contract->date_de_decheance && \Carbon\Carbon::parse($contract->date_de_decheance)->isFuture()) {
            return back()->withErrors(['workflow' => 'La date limite de paiement de la caution (' . \Carbon\Carbon::parse($contract->date_de_decheance)->format('d/m/Y') . ') n\'est pas encore dépassée.']);
        }

        $contract->update(['Current_state' => 'Déchu']);

        \App\Services\ActivityLogger::log('update', 'Mise en déchéance de la caution', Article::class, $article->id);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Le dossier a été mis en déchéance.');
    }

    public function closeDossier(Article $article): RedirectResponse
    {
        $this->authorize('mainlevee.issue');

        try {
            $this->workflow->transition($article, ArticleWorkflowService::CLOSED, Auth::id());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['workflow' => $e->getMessage()]);
        }

        return redirect()->route('articles.show', $article)
            ->with('success', 'Dossier clôturé définitivement.');
    }

    public function resilierContrat(Request $request, Article $article): RedirectResponse
    {
        $this->authorize('termination.create');

        $contractVente = $article->contractVentes()->first();
        if (!$contractVente) {
            return back()->with('error', 'Aucun contrat de vente trouvé.');
        }

        $contractVente->update([
            'is_resiliation' => true,
            'date_de_resiliation' => now(),
            'Current_state' => 'Résilié',
        ]);

        $notifService = app(\App\Services\NotificationService::class);
        $notifService->sendToAllUsers('resiliation', 'Contrat résilié', "Le contrat de l'article {$article->numero} a été résilié.", [], [
            'action_url' => route('articles.show', $article),
            'icon' => 'fas fa-ban',
            'color' => 'danger',
            'priority' => 'high',
        ]);

        \App\Services\ActivityLogger::log('update', 'Contrat résilié', ContractVente::class, $contractVente->id);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Contrat résilié avec succès.');
    }

    // -------------------------------------------------------------------------
    // Alerts
    // -------------------------------------------------------------------------

    /**
     * Global alerts dashboard — all active alerts across all contracts.
     */
    public function alertsIndex(Request $request): \Illuminate\View\View
    {
        $this->authorize('alert.view');

        $severity = $request->query('severity'); // optional filter: critical|warning|info
        $type     = $request->query('type');     // optional filter by alert type

        $query = Alert::active()
            ->with([])
            ->orderByRaw("FIELD(severity, 'critical', 'warning', 'info')")
            ->orderBy('created_at', 'desc');

        if ($severity) {
            $query->where('severity', $severity);
        }
        if ($type) {
            $query->where('type', $type);
        }

        $alerts   = $query->paginate(25)->withQueryString();
        $counts   = Alert::active()
            ->selectRaw("severity, count(*) as total")
            ->groupBy('severity')
            ->pluck('total', 'severity');

        return view('workflow.alerts.global', compact('alerts', 'counts', 'severity', 'type'));
    }

    public function alerts(Article $article): \Illuminate\View\View
    {
        $this->authorize('alert.view');
        $alerts = $this->alertService->getActiveAlertsForArticle($article);

        return view('workflow.alerts.index', compact('article', 'alerts'));
    }

    public function archiveAlert(Request $request, Alert $alert): RedirectResponse
    {
        $this->authorize('alert.archive');
        $request->validate(['reason' => 'nullable|string|max:255']);
        $alert->archive($request->reason ?? 'Archivé manuellement', Auth::id());

        return back()->with('success', 'Alerte archivée.');
    }
}
