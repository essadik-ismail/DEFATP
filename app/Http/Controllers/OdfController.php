<?php

namespace App\Http\Controllers;

use App\Models\Odf;
use App\Models\OdfEntite;
use App\Models\Member;
use App\Models\Activity;
use App\Models\OdfEtap;
use App\Models\OdfDiagnostic;
use App\Models\Constitution;
use App\Models\ContractOdf;
use App\Models\OdfModification;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class OdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Log ODFs view
        ActivityLogger::log('view', 'Consultation de la liste des ODFs', Odf::class);
        
        $query = Odf::with(['odfEntite.localisation', 'odfEntite.situationAdministrative']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('commentaire', 'like', "%{$search}%")
                  ->orWhereHas('odfEntite', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Date filters
        $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : null;
        
        if ($startDate || $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('created_at', '>=', $startDate);
            } elseif ($endDate) {
                $query->where('created_at', '<=', $endDate);
            }
        }
        
        // ODF Entite filter
        if ($request->filled('odf_entite_id')) {
            $query->where('odf_entite_id', $request->odf_entite_id);
        }
        
        // Constitution filter
        if ($request->filled('constitution')) {
            $constitution = $request->get('constitution');
            if ($constitution === '1') {
                $query->where('constitution', true);
            } elseif ($constitution === '0') {
                $query->where('constitution', false);
            }
        }
        
        // Status filter
        if ($request->filled('status')) {
            switch ($request->get('status')) {
                case 'active':
                    $query->whereNull('deleted_at');
                    break;
                case 'deleted':
                    $query->onlyTrashed();
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }
        
        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $allowedSortFields = ['id', 'constitution', 'date_depot_odf', 'date_reçu_du_définition', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }
        
        $odfs = $query->paginate($perPage);
        
        // Calculate statistics
        $stats = [
            'total' => Odf::count(),
            'active' => Odf::whereNull('deleted_at')->count(),
            'deleted' => Odf::onlyTrashed()->count(),
            'recent' => Odf::where('created_at', '>=', now()->subDays(30))->count(),
        ];
        
        // Get filter options
        $localisations = \App\Models\Localisation::orderBy('CODE')->get();
        $situationAdministratives = \App\Models\SituationAdministrative::orderBy('commune')->get();
        $odfEntites = OdfEntite::orderBy('name')->get();
        
        return view('odfs.index', compact('odfs', 'stats', 'localisations', 'situationAdministratives', 'odfEntites'));
    }

    /**
     * Step 1: Show base information form.
     */
    public function createStep1(): View
    {
        $localisations = \App\Models\Localisation::orderBy('CODE')->get();
        $situationAdministratives = \App\Models\SituationAdministrative::orderBy('commune')->get();
        $odfEntites = OdfEntite::orderBy('name')->get();
        
        return view('odfs.create-step1', compact('localisations', 'situationAdministratives', 'odfEntites'));
    }

    /**
     * Step 1: Store base information and create ODF.
     */
    public function storeStep1(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'odf_entite_id' => 'required|exists:odf_entites,id',
            'commentaire'   => 'nullable|string',
        ]);

        $odf = Odf::create([
            'odf_entite_id' => $validated['odf_entite_id'],
            'commentaire'   => $validated['commentaire'] ?? null,
            'constitution'  => false,
        ]);

        ActivityLogger::log('create', 'Création d\'une nouvelle ODF (étape 1)', Odf::class, $odf->id);

        return redirect()->route('odfs.create.step2', $odf)
            ->with('success', 'Étape 1 enregistrée. Veuillez ajouter le diagnostic.');
    }

    /**
     * Step 2: Diagnostics form.
     */
    public function createStep2(Odf $odf): View
    {
        return view('odfs.create-step2', compact('odf'));
    }

    /**
     * Step 2: Store diagnostics.
     */
    public function storeStep2(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'diagnostics'             => 'nullable|array',
            'diagnostics.*.type'      => 'required|in:associations,coopératives,titulaires_amodiations,nouabs des collectivités ethniques,autre',
            'diagnostics.*.nom'       => 'nullable|string|max:255',
            'diagnostics.*.activité'  => 'nullable|string|max:255',
            'diagnostics.*.présidente'=> 'nullable|string|max:255',
            'diagnostics.*.nombre_de_membres' => 'nullable|integer|min:0',
        ]);

        $diagnosticIds = [];
        if (!empty($validated['diagnostics'])) {
            foreach ($validated['diagnostics'] as $index => $diagnosticData) {
                $diagnostic = OdfDiagnostic::create([
                    'odf_id' => $odf->id,
                    'type' => $diagnosticData['type'],
                    'nom' => $diagnosticData['nom'] ?? null,
                    'activité' => $diagnosticData['activité'] ?? null,
                    'présidente' => $diagnosticData['présidente'] ?? null,
                    'nombre_de_membres' => $diagnosticData['nombre_de_membres'] ?? null,
                ]);
                // Store diagnostic ID by index (1-based)
                $diagnosticIds[$index + 1] = $diagnostic->id;
            }
        }
        ActivityLogger::log('update', 'Ajout de diagnostics à une ODF (étape 2)', Odf::class, $odf->id);

        return redirect()->route('odfs.create.step3', $odf)
            ->with('success', 'Étape 2 enregistrée. Veuillez ajouter les membres.');
    }

    /**
     * Step 3: Members form.
     */
    public function createStep3(Odf $odf): View
    {
        $diagnostics = $odf->odfDiagnostics()->get();
        return view('odfs.create-step3', compact('odf', 'diagnostics'));
    }

    /**
     * Step 3: Store members.
     */
    public function storeStep3(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'members'                        => 'nullable|array',
            'members.*.type'                 => 'required|string|max:255',
            'members.*.odf_diagnostic_id'    => 'nullable|exists:odf_diagnostic,id',
        ]);

        if (!empty($validated['members'])) {
            foreach ($validated['members'] as $memberData) {
                Member::create([
                    'odf_id'            => $odf->id,
                    'type'              => $memberData['type'],
                    'odf_diagnostic_id' => $memberData['odf_diagnostic_id'] ?? null,
                ]);
            }
        }

        ActivityLogger::log('update', 'Ajout de membres à une ODF (étape 3)', Odf::class, $odf->id);

        return redirect()->route('odfs.create.step4', $odf)
            ->with('success', 'Étape 3 enregistrée. Veuillez ajouter les étapes et la constitution.');
    }

    /**
     * Step 4: Etaps + constitution form.
     */
    public function createStep4(Odf $odf): View
    {
        $diagnostics = $odf->odfDiagnostics()->get();
        return view('odfs.create-step4', compact('odf', 'diagnostics'));
    }

    /**
     * Step 4: Store etaps only.
     */
    public function storeStep4(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'odf_etaps'                 => 'nullable|array',
            'odf_etaps.*.objet'         => 'nullable|string|max:255',
            'odf_etaps.*.lieu'          => 'nullable|string|max:255',
            'odf_etaps.*.date'          => 'nullable|date',
            'odf_etaps.*.type'          => 'nullable|string|max:255',
            'odf_etaps.*.description'   => 'nullable|string',
            'odf_etaps.*.resultat'      => 'nullable|string',
            'odf_etaps.*.participants'  => 'nullable|array',
        ]);

        // Etaps
        if (!empty($validated['odf_etaps'])) {
            foreach ($validated['odf_etaps'] as $etapData) {
                OdfEtap::create([
                    'odf_id'      => $odf->id,
                    'objet'       => $etapData['objet'] ?? null,
                    'lieu'        => $etapData['lieu'] ?? null,
                    'date'        => $etapData['date'] ?? null,
                    'type'        => $etapData['type'] ?? null,
                    'description' => $etapData['description'] ?? null,
                    'resultat'    => $etapData['resultat'] ?? null,
                    'participants'=> !empty($etapData['participants']) ? json_encode($etapData['participants']) : null,
                ]);
            }
        }

        ActivityLogger::log('update', 'Ajout d\'étapes de négociation à une ODF (étape 4)', Odf::class, $odf->id);

        return redirect()->route('odfs.create.step5', $odf)
            ->with('success', 'Étape 4 enregistrée. Veuillez renseigner les informations de constitution.');
    }

    /**
     * Step 5: Constitution form.
     */
    public function createStep5(Odf $odf): View
    {
        return view('odfs.create-step5', compact('odf'));
    }

    /**
     * Step 5: Store constitution and mark ODF as constituted.
     */
    public function storeStep5(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'constitution'                      => 'nullable|array',
            'constitution.date'                 => 'nullable|date',
            'constitution.lieu'                 => 'nullable|string|max:255',
            'constitution.participant'          => 'nullable|string',
            'constitution.date_depot_odf'       => 'nullable|date',
            'constitution.fichier_joint_depot_odf' => 'nullable|string|max:255',
            'constitution.lieu_depot_odf'       => 'nullable|string|max:255',
            'constitution.date_reçu_définitive' => 'nullable|date',
            'constitution.fichier_joint_reçu_définitive' => 'nullable|string|max:255',
            'constitution.lieu_reçu_définitive' => 'nullable|string|max:255',
        ]);

        // Constitution
        $constitutionData = $validated['constitution'] ?? [];
        if (!empty($constitutionData)) {
            Constitution::create([
                'odf_id'                     => $odf->id,
                'date'                       => $constitutionData['date'] ?? null,
                'lieu'                       => $constitutionData['lieu'] ?? null,
                'participant'                => $constitutionData['participant'] ?? null,
                'date_depot_odf'             => $constitutionData['date_depot_odf'] ?? null,
                'fichier_joint_depot_odf'    => $constitutionData['fichier_joint_depot_odf'] ?? null,
                'lieu_depot_odf'             => $constitutionData['lieu_depot_odf'] ?? null,
                'date_reçu_définitive'      => $constitutionData['date_reçu_définitive'] ?? null,
                'fichier_joint_reçu_définitive' => $constitutionData['fichier_joint_reçu_définitive'] ?? null,
                'lieu_reçu_définitive'      => $constitutionData['lieu_reçu_définitive'] ?? null,
            ]);

            $odf->update(['constitution' => true]);
        }

        ActivityLogger::log('update', 'Finalisation de la création d\'une ODF (étape 5)', Odf::class, $odf->id);

        return redirect()->route('odfs.index')
            ->with('success', 'ODF créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Odf $odf): View
    {
        $odf->load([
            'odfEntite',
            'members',
            'activities',
            'odfEtaps',
            'contractOdf',
            'odfModifications',
            'localisation',
            'situationAdministrative'
        ]);
        
        ActivityLogger::log('view', 'Consultation des détails de l\'ODF', Odf::class, $odf->id);
        
        return view('odfs.show', compact('odf'));
    }

    /**
     * Show the form for editing the specified resource.
     * Redirects to the last completed step.
     */
    public function edit(Odf $odf): RedirectResponse
    {
        $lastStep = $this->getLastCompletedStep($odf);
        return redirect()->route('odfs.edit.step' . $lastStep, $odf);
    }

    /**
     * Determine the last completed step for an ODF.
     */
    private function getLastCompletedStep(Odf $odf): int
    {
        // Step 5: Constitution exists
        if ($odf->constitution()->exists()) {
            return 5;
        }
        
        // Step 4: ODF Etaps exist
        if ($odf->odfEtaps()->exists()) {
            return 4;
        }
        
        // Step 3: Members exist
        if ($odf->members()->exists()) {
            return 3;
        }
        
        // Step 2: Diagnostics exist
        if ($odf->odfDiagnostics()->exists()) {
            return 2;
        }
        
        // Step 1: ODF exists (always at least step 1)
        return 1;
    }

    /**
     * Edit Step 1: Base information.
     */
    public function editStep1(Odf $odf): View
    {
        $odfEntites = OdfEntite::orderBy('name')->get();
        return view('odfs.edit-step1', compact('odf', 'odfEntites'));
    }

    /**
     * Update Step 1: Base information.
     */
    public function updateStep1(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'odf_entite_id' => 'required|exists:odf_entites,id',
            'commentaire' => 'nullable|string',
        ]);

        $odf->update($validated);

        ActivityLogger::log('update', 'Modification des informations de base de l\'ODF (étape 1)', Odf::class, $odf->id);

        $nextStep = $this->getLastCompletedStep($odf) >= 2 ? 2 : 2;
        return redirect()->route('odfs.edit.step' . $nextStep, $odf)
            ->with('success', 'Étape 1 mise à jour. Continuez avec l\'étape suivante.');
    }

    /**
     * Edit Step 2: Diagnostics.
     */
    public function editStep2(Odf $odf): View
    {
        $diagnostics = $odf->odfDiagnostics()->get();
        return view('odfs.edit-step2', compact('odf', 'diagnostics'));
    }

    /**
     * Update Step 2: Diagnostics.
     */
    public function updateStep2(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'diagnostics' => 'nullable|array',
            'diagnostics.*.type' => 'required|string|in:associations,coopératives,titulaires_amodiations,nouabs des collectivités ethniques,autre',
            'diagnostics.*.nom' => 'nullable|string|max:255',
            'diagnostics.*.activité' => 'nullable|string|max:255',
            'diagnostics.*.présidente' => 'nullable|string|max:255',
            'diagnostics.*.nombre_de_membres' => 'nullable|integer|min:0',
            'diagnostics_to_delete' => 'nullable|array',
            'diagnostics_to_delete.*' => 'exists:odf_diagnostic,id',
        ]);

        // Delete removed diagnostics
        if (!empty($validated['diagnostics_to_delete'])) {
            foreach ($validated['diagnostics_to_delete'] as $diagnosticId) {
                $diagnostic = OdfDiagnostic::find($diagnosticId);
                if ($diagnostic && $diagnostic->odf_id === $odf->id) {
                    $diagnostic->delete();
                }
            }
        }

        // Update or create diagnostics
        if (!empty($validated['diagnostics'])) {
            foreach ($validated['diagnostics'] as $index => $diagnosticData) {
                if (isset($diagnosticData['id'])) {
                    // Update existing
                    $diagnostic = OdfDiagnostic::find($diagnosticData['id']);
                    if ($diagnostic && $diagnostic->odf_id === $odf->id) {
                        $diagnostic->update($diagnosticData);
                    }
                } else {
                    // Create new
                    OdfDiagnostic::create(array_merge($diagnosticData, ['odf_id' => $odf->id]));
                }
            }
        }

        ActivityLogger::log('update', 'Modification des diagnostics de l\'ODF (étape 2)', Odf::class, $odf->id);

        $nextStep = $this->getLastCompletedStep($odf) >= 3 ? 3 : 3;
        return redirect()->route('odfs.edit.step' . $nextStep, $odf)
            ->with('success', 'Étape 2 mise à jour. Continuez avec l\'étape suivante.');
    }

    /**
     * Edit Step 3: Members.
     */
    public function editStep3(Odf $odf): View
    {
        $diagnostics = $odf->odfDiagnostics()->get();
        $members = $odf->members()->get();
        return view('odfs.edit-step3', compact('odf', 'diagnostics', 'members'));
    }

    /**
     * Update Step 3: Members.
     */
    public function updateStep3(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'members' => 'nullable|array',
            'members.*.type' => 'required|string|max:255',
            'members.*.odf_diagnostic_id' => 'nullable|exists:odf_diagnostic,id',
            'members_to_delete' => 'nullable|array',
            'members_to_delete.*' => 'exists:members,id',
        ]);

        // Delete removed members
        if (!empty($validated['members_to_delete'])) {
            foreach ($validated['members_to_delete'] as $memberId) {
                $member = Member::find($memberId);
                if ($member && $member->odf_id === $odf->id) {
                    $member->delete();
                }
            }
        }

        // Update or create members
        if (!empty($validated['members'])) {
            foreach ($validated['members'] as $memberData) {
                if (isset($memberData['id'])) {
                    // Update existing
                    $member = Member::find($memberData['id']);
                    if ($member && $member->odf_id === $odf->id) {
                        $member->update($memberData);
                    }
                } else {
                    // Create new
                    Member::create(array_merge($memberData, ['odf_id' => $odf->id]));
                }
            }
        }

        ActivityLogger::log('update', 'Modification des membres de l\'ODF (étape 3)', Odf::class, $odf->id);

        $nextStep = $this->getLastCompletedStep($odf) >= 4 ? 4 : 4;
        return redirect()->route('odfs.edit.step' . $nextStep, $odf)
            ->with('success', 'Étape 3 mise à jour. Continuez avec l\'étape suivante.');
    }

    /**
     * Edit Step 4: Etaps.
     */
    public function editStep4(Odf $odf): View
    {
        $diagnostics = $odf->odfDiagnostics()->get();
        $etaps = $odf->odfEtaps()->get();
        return view('odfs.edit-step4', compact('odf', 'diagnostics', 'etaps'));
    }

    /**
     * Update Step 4: Etaps.
     */
    public function updateStep4(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'odf_etaps' => 'nullable|array',
            'odf_etaps.*.objet' => 'nullable|string|max:255',
            'odf_etaps.*.lieu' => 'nullable|string|max:255',
            'odf_etaps.*.date' => 'nullable|date',
            'odf_etaps.*.type' => 'nullable|string|max:255',
            'odf_etaps.*.description' => 'nullable|string',
            'odf_etaps.*.resultat' => 'nullable|string',
            'odf_etaps.*.participants' => 'nullable|array',
            'etaps_to_delete' => 'nullable|array',
            'etaps_to_delete.*' => 'exists:odf_etaps,id',
        ]);

        // Delete removed etaps
        if (!empty($validated['etaps_to_delete'])) {
            foreach ($validated['etaps_to_delete'] as $etapId) {
                $etap = OdfEtap::find($etapId);
                if ($etap && $etap->odf_id === $odf->id) {
                    $etap->delete();
                }
            }
        }

        // Update or create etaps
        if (!empty($validated['odf_etaps'])) {
            foreach ($validated['odf_etaps'] as $etapData) {
                if (isset($etapData['id'])) {
                    // Update existing
                    $etap = OdfEtap::find($etapData['id']);
                    if ($etap && $etap->odf_id === $odf->id) {
                        $etap->update([
                            'objet' => $etapData['objet'] ?? null,
                            'lieu' => $etapData['lieu'] ?? null,
                            'date' => $etapData['date'] ?? null,
                            'type' => $etapData['type'] ?? null,
                            'description' => $etapData['description'] ?? null,
                            'resultat' => $etapData['resultat'] ?? null,
                            'participants' => !empty($etapData['participants']) ? json_encode($etapData['participants']) : null,
                        ]);
                    }
                } else {
                    // Create new
                    OdfEtap::create([
                        'odf_id' => $odf->id,
                        'objet' => $etapData['objet'] ?? null,
                        'lieu' => $etapData['lieu'] ?? null,
                        'date' => $etapData['date'] ?? null,
                        'type' => $etapData['type'] ?? null,
                        'description' => $etapData['description'] ?? null,
                        'resultat' => $etapData['resultat'] ?? null,
                        'participants' => !empty($etapData['participants']) ? json_encode($etapData['participants']) : null,
                    ]);
                }
            }
        }

        ActivityLogger::log('update', 'Modification des étapes de négociation de l\'ODF (étape 4)', Odf::class, $odf->id);

        $nextStep = $this->getLastCompletedStep($odf) >= 5 ? 5 : 5;
        return redirect()->route('odfs.edit.step' . $nextStep, $odf)
            ->with('success', 'Étape 4 mise à jour. Continuez avec l\'étape suivante.');
    }

    /**
     * Edit Step 5: Constitution.
     */
    public function editStep5(Odf $odf): View
    {
        $constitution = $odf->constitution()->first();
        return view('odfs.edit-step5', compact('odf', 'constitution'));
    }

    /**
     * Update Step 5: Constitution.
     */
    public function updateStep5(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'constitution' => 'nullable|array',
            'constitution.date' => 'nullable|date',
            'constitution.lieu' => 'nullable|string|max:255',
            'constitution.participant' => 'nullable|string',
            'constitution.date_depot_odf' => 'nullable|date',
            'constitution.fichier_joint_depot_odf' => 'nullable|string|max:255',
            'constitution.lieu_depot_odf' => 'nullable|string|max:255',
            'constitution.date_reçu_définitive' => 'nullable|date',
            'constitution.fichier_joint_reçu_définitive' => 'nullable|string|max:255',
            'constitution.lieu_reçu_définitive' => 'nullable|string|max:255',
        ]);

        $constitutionData = $validated['constitution'] ?? [];
        $constitution = $odf->constitution()->first();

        if (!empty($constitutionData)) {
            if ($constitution) {
                // Update existing
                $constitution->update($constitutionData);
            } else {
                // Create new
                Constitution::create(array_merge($constitutionData, ['odf_id' => $odf->id]));
            }
            $odf->update(['constitution' => true]);
        } else {
            // If constitution data is empty and exists, delete it
            if ($constitution) {
                $constitution->delete();
            }
            $odf->update(['constitution' => false]);
        }

        ActivityLogger::log('update', 'Modification de la constitution de l\'ODF (étape 5)', Odf::class, $odf->id);

        return redirect()->route('odfs.index')
            ->with('success', 'ODF mise à jour avec succès.');
    }

    /**
     * Get ODF Entité information via API
     */
    public function getOdfEntite(OdfEntite $odfEntite)
    {
        $odfEntite->load(['localisation', 'situationAdministrative']);
        
        return response()->json([
            'id' => $odfEntite->id,
            'name' => $odfEntite->name,
            'localisation' => $odfEntite->localisation ? [
                'id' => $odfEntite->localisation->id,
                'code' => $odfEntite->localisation->CODE,
                'dranef' => $odfEntite->localisation->DRANEF,
                'entite' => $odfEntite->localisation->ENTITE,
            ] : null,
            'situation_administrative' => $odfEntite->situationAdministrative ? [
                'id' => $odfEntite->situationAdministrative->id,
                'commune' => $odfEntite->situationAdministrative->commune,
                'province' => $odfEntite->situationAdministrative->province,
            ] : null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'odf_entite_id' => 'nullable|exists:odf_entites,id',
            'constitution' => 'nullable|boolean',
            'date_depot_odf' => 'nullable|date',
            'fichier_joint_depot_odf' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'date_reçu_du_définition' => 'nullable|date',
            'fichier_joint_reçu_du_définition' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'commentaire' => 'nullable|string',
            'localisation_id' => 'nullable|exists:localisations,id',
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
        ]);

        // Handle file uploads
        if ($request->hasFile('fichier_joint_depot_odf')) {
            $file = $request->file('fichier_joint_depot_odf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('odf/depot', $filename, 'public');
            $validated['fichier_joint_depot_odf'] = $path;
        }

        if ($request->hasFile('fichier_joint_reçu_du_définition')) {
            $file = $request->file('fichier_joint_reçu_du_définition');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('odf/recu', $filename, 'public');
            $validated['fichier_joint_reçu_du_définition'] = $path;
        }

        // If date_reçu_du_définition is filled, automatically set constitution to true
        if (!empty($validated['date_reçu_du_définition'])) {
            $validated['constitution'] = true;
        }

        $odf->update($validated);

        ActivityLogger::log('update', 'Modification de l\'ODF', Odf::class, $odf->id);

        return redirect()->route('odfs.edit', $odf)
            ->with('success', 'ODF mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Odf $odf): RedirectResponse
    {
        $odfId = $odf->id;
        $odf->delete();

        ActivityLogger::log('delete', 'Suppression de l\'ODF', Odf::class, $odfId);

        return redirect()->route('odfs.index')
            ->with('success', 'ODF supprimée avec succès.');
    }

    /**
     * Store a new member for the ODF.
     */
    public function storeMember(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'type_membre' => 'nullable|in:présidente,vice_présidente,trésorière,membre',
            'nom' => 'required|string|max:255',
            'n_cin' => 'nullable|string|max:255',
            'tel' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'type_odf' => 'nullable|in:Association,Coopérative,Entreprise,Élu,Citoyen',
            'type_odf_domaine_activite' => 'nullable|string|max:255',
            'type_odf_nombre_de_membres' => 'nullable|integer|min:0',
            'commentaire' => 'nullable|string',
        ]);

        $member = $odf->members()->create($validated);

        ActivityLogger::log('create', 'Ajout d\'un membre à l\'ODF', Member::class, $member->id);

        return redirect()->route('odfs.edit', $odf)
            ->with('success', 'Membre ajouté avec succès.');
    }

    /**
     * Update a member.
     */
    public function updateMember(Request $request, Odf $odf, Member $member): RedirectResponse
    {
        $validated = $request->validate([
            'type_membre' => 'nullable|in:présidente,vice_présidente,trésorière,membre',
            'nom' => 'required|string|max:255',
            'n_cin' => 'nullable|string|max:255',
            'tel' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'type_odf' => 'nullable|in:Association,Coopérative,Entreprise,Élu,Citoyen',
            'type_odf_domaine_activite' => 'nullable|string|max:255',
            'type_odf_nombre_de_membres' => 'nullable|integer|min:0',
            'commentaire' => 'nullable|string',
        ]);

        $member->update($validated);

        ActivityLogger::log('update', 'Modification d\'un membre de l\'ODF', Member::class, $member->id);

        return redirect()->route('odfs.edit', $odf)
            ->with('success', 'Membre mis à jour avec succès.');
    }

    /**
     * Delete a member.
     */
    public function destroyMember(Odf $odf, Member $member): RedirectResponse
    {
        $memberId = $member->id;
        $member->delete();

        ActivityLogger::log('delete', 'Suppression d\'un membre de l\'ODF', Member::class, $memberId);

        return redirect()->route('odfs.edit', $odf)
            ->with('success', 'Membre supprimé avec succès.');
    }

    /**
     * Store a new activity for the ODF.
     */
    public function storeActivity(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'description' => 'nullable|string',
            'participants' => 'nullable|string',
            'lieu' => 'nullable|string|max:255',
            'date' => 'required|date',
            'fichier_joint' => 'nullable|string|max:255',
        ]);

        $activity = $odf->activities()->create($validated);

        ActivityLogger::log('create', 'Ajout d\'une activité à l\'ODF', Activity::class, $activity->id);

        return redirect()->route('odfs.show', $odf)
            ->with('success', 'Activité ajoutée avec succès.');
    }

    /**
     * Update an activity.
     */
    public function updateActivity(Request $request, Odf $odf, Activity $activity): RedirectResponse
    {
        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'description' => 'nullable|string',
            'participants' => 'nullable|string',
            'lieu' => 'nullable|string|max:255',
            'date' => 'required|date',
            'fichier_joint' => 'nullable|string|max:255',
        ]);

        $activity->update($validated);

        ActivityLogger::log('update', 'Modification d\'une activité de l\'ODF', Activity::class, $activity->id);

        return redirect()->route('odfs.show', $odf)
            ->with('success', 'Activité mise à jour avec succès.');
    }

    /**
     * Delete an activity.
     */
    public function destroyActivity(Odf $odf, Activity $activity): RedirectResponse
    {
        $activityId = $activity->id;
        $activity->delete();

        ActivityLogger::log('delete', 'Suppression d\'une activité de l\'ODF', Activity::class, $activityId);

        return redirect()->route('odfs.show', $odf)
            ->with('success', 'Activité supprimée avec succès.');
    }

    /**
     * Get a single ODF etap for editing.
     */
    public function getOdfEtap(Odf $odf, OdfEtap $odfEtap)
    {
        return response()->json($odfEtap);
    }

    /**
     * Store a new ODF etap for the ODF.
     */
    public function storeOdfEtap(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'participant' => 'nullable|string',
            'description' => 'nullable|string',
            'resultat' => 'nullable|string',
            'fichierjoin' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('fichierjoin')) {
            $file = $request->file('fichierjoin');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('odf/etaps', $filename, 'public');
            $validated['fichierjoin'] = $path;
        } else {
            unset($validated['fichierjoin']);
        }

        $validated['odf_id'] = $odf->id;
        $odfEtap = OdfEtap::create($validated);

        ActivityLogger::log('create', 'Ajout d\'une étape à l\'ODF', OdfEtap::class, $odfEtap->id);

        return redirect()->route('odfs.edit', $odf)
            ->with('success', 'Étape ajoutée avec succès.');
    }

    /**
     * Update an ODF etap.
     */
    public function updateOdfEtap(Request $request, Odf $odf, OdfEtap $odfEtap): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'participant' => 'nullable|string',
            'description' => 'nullable|string',
            'resultat' => 'nullable|string',
            'fichierjoin' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('fichierjoin')) {
            // Delete old file if exists
            if ($odfEtap->fichierjoin && Storage::disk('public')->exists($odfEtap->fichierjoin)) {
                Storage::disk('public')->delete($odfEtap->fichierjoin);
            }
            
            $file = $request->file('fichierjoin');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('odf/etaps', $filename, 'public');
            $validated['fichierjoin'] = $path;
        } else {
            unset($validated['fichierjoin']);
        }

        $odfEtap->update($validated);

        ActivityLogger::log('update', 'Modification d\'une étape de l\'ODF', OdfEtap::class, $odfEtap->id);

        return redirect()->route('odfs.edit', $odf)
            ->with('success', 'Étape mise à jour avec succès.');
    }

    /**
     * Delete an ODF etap.
     */
    public function destroyOdfEtap(Odf $odf, OdfEtap $odfEtap): RedirectResponse
    {
        $odfEtapId = $odfEtap->id;
        $odfEtap->delete();

        ActivityLogger::log('delete', 'Suppression d\'une étape de l\'ODF', OdfEtap::class, $odfEtapId);

        return redirect()->route('odfs.edit', $odf)
            ->with('success', 'Étape supprimée avec succès.');
    }

    /**
     * Store a new contract ODF for the ODF.
     */
    public function storeContractOdf(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'signature1_nom' => 'nullable|string|max:255',
            'signature2_nom' => 'nullable|string|max:255',
            'signature1_type' => 'nullable|in:présidente,vice_présidente,trésorière,membre',
            'signature2_type' => 'nullable|in:dranef,dpanef,autre',
            'fichier_join' => 'nullable|string|max:255',
            'commentaire' => 'nullable|string',
        ]);

        $validated['odf_id'] = $odf->id;
        $contractOdf = ContractOdf::create($validated);

        ActivityLogger::log('create', 'Ajout d\'un contrat à l\'ODF', ContractOdf::class, $contractOdf->id);

        return redirect()->route('odfs.show', $odf)
            ->with('success', 'Contrat ajouté avec succès.');
    }

    /**
     * Update a contract ODF.
     */
    public function updateContractOdf(Request $request, Odf $odf, ContractOdf $contractOdf): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'signature1_nom' => 'nullable|string|max:255',
            'signature2_nom' => 'nullable|string|max:255',
            'signature1_type' => 'nullable|in:présidente,vice_présidente,trésorière,membre',
            'signature2_type' => 'nullable|in:dranef,dpanef,autre',
            'fichier_join' => 'nullable|string|max:255',
            'commentaire' => 'nullable|string',
        ]);

        $contractOdf->update($validated);

        ActivityLogger::log('update', 'Modification d\'un contrat de l\'ODF', ContractOdf::class, $contractOdf->id);

        return redirect()->route('odfs.show', $odf)
            ->with('success', 'Contrat mis à jour avec succès.');
    }

    /**
     * Delete a contract ODF.
     */
    public function destroyContractOdf(Odf $odf, ContractOdf $contractOdf): RedirectResponse
    {
        $contractOdfId = $contractOdf->id;
        $contractOdf->delete();

        ActivityLogger::log('delete', 'Suppression d\'un contrat de l\'ODF', ContractOdf::class, $contractOdfId);

        return redirect()->route('odfs.show', $odf)
            ->with('success', 'Contrat supprimé avec succès.');
    }

    /**
     * Store a new ODF modification for the ODF.
     */
    public function storeOdfModification(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'modification' => 'nullable|string',
            'actions' => 'nullable|string',
            'commentaire' => 'nullable|string',
        ]);

        $validated['odf_id'] = $odf->id;
        $odfModification = OdfModification::create($validated);

        ActivityLogger::log('create', 'Ajout d\'une modification à l\'ODF', OdfModification::class, $odfModification->id);

        return redirect()->route('odfs.show', $odf)
            ->with('success', 'Modification ajoutée avec succès.');
    }

    /**
     * Update an ODF modification.
     */
    public function updateOdfModification(Request $request, Odf $odf, OdfModification $odfModification): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'modification' => 'nullable|string',
            'actions' => 'nullable|string',
            'commentaire' => 'nullable|string',
        ]);

        $odfModification->update($validated);

        ActivityLogger::log('update', 'Modification d\'une modification de l\'ODF', OdfModification::class, $odfModification->id);

        return redirect()->route('odfs.show', $odf)
            ->with('success', 'Modification mise à jour avec succès.');
    }

    /**
     * Delete an ODF modification.
     */
    public function destroyOdfModification(Odf $odf, OdfModification $odfModification): RedirectResponse
    {
        $odfModificationId = $odfModification->id;
        $odfModification->delete();

        ActivityLogger::log('delete', 'Suppression d\'une modification de l\'ODF', OdfModification::class, $odfModificationId);

        return redirect()->route('odfs.show', $odf)
            ->with('success', 'Modification supprimée avec succès.');
    }
}
