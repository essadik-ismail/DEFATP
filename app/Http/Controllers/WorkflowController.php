<?php

namespace App\Http\Controllers;

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
        $request->validate(['state' => 'required|string']);

        try {
            $this->workflow->transition($article, $request->state, Auth::id());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['workflow' => $e->getMessage()]);
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

        $article->refresh();
        $current = $article->workflow_state ?? ArticleWorkflowService::DRAFT_ARTICLE;

        if ($current === ArticleWorkflowService::CONTRACT_CREATED) {
            try {
                $this->workflow->transition($article, ArticleWorkflowService::LETTER_SIGNED_UPLOADED, Auth::id());
            } catch (\RuntimeException $e) {
                return back()->withErrors(['workflow' => $e->getMessage()]);
            }
        }

        return redirect()->route('articles.show', $article)
            ->with('success', 'Lettre signée importée avec succès. Statut mis à jour : Lettre d\'adjudicataire validée.');
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
        ]);

        $contract = $article->contractVentes()->latest()->firstOrFail();

        // Block if a prorogation is already pending
        if ($contract->prorogations()->where('status', Prorogation::STATUS_PENDING)->exists()) {
            return back()->withErrors(['prorogation' => 'Une prorogation est déjà en attente d\'approbation.']);
        }

        $contract->prorogations()->create([
            'duration_months'     => $request->duration_months,
            'motif'               => $request->motif,
            'status'              => Prorogation::STATUS_PENDING,
            'original_expiry_date'=> $contract->date_expiration,
            'requested_by'        => Auth::id(),
        ]);

        return redirect()->route('articles.show', $article)
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

        $recolement->fill([
            'date_pv'      => $request->date_pv,
            'num_pv'       => $request->num_pv,
            'observations' => $request->observations,
            'fichier_pv'   => $filePath,
            'submitted_by' => Auth::id(),
            'status'       => Recolement::STATUS_PV_SUBMITTED,
        ])->save();

        try {
            $this->workflow->transition($article, ArticleWorkflowService::RECOLEMENT_PENDING, Auth::id());
        } catch (\RuntimeException $e) {
            return redirect()->route('articles.show', $article)
                ->with('warning', 'PV soumis, mais la transition de statut a échoué : ' . $e->getMessage());
        }

        return redirect()->route('articles.show', $article)
            ->with('success', 'PV de récolement soumis.');
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

        $contract = $article->contractVentes()->latest()->first();
        if (!$contract) {
            return back()->withErrors(['workflow' => 'Aucun contrat trouvé.']);
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
