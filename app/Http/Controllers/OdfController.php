<?php

namespace App\Http\Controllers;

use App\Models\Odf;
use App\Models\OdfEntite;
use App\Models\Member;
use App\Models\Activity;
use App\Models\OdfEtap;
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
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $localisations = \App\Models\Localisation::orderBy('CODE')->get();
        $situationAdministratives = \App\Models\SituationAdministrative::orderBy('commune')->get();
        $odfEntites = OdfEntite::orderBy('name')->get();
        
        return view('odfs.create', compact('localisations', 'situationAdministratives', 'odfEntites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'odf_entite_id' => 'nullable|exists:odf_entites,id',
            'constitution' => 'nullable|boolean',
            'date_depot_odf' => 'nullable|date',
            'fichier_joint_depot_odf' => 'nullable|string|max:255',
            'date_reçu_du_définition' => 'nullable|date',
            'fichier_joint_reçu_du_définition' => 'nullable|string|max:255',
            'commentaire' => 'nullable|string',
            'localisation_id' => 'nullable|exists:localisations,id',
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
        ]);

        // If date_reçu_du_définition is filled, automatically set constitution to true
        if (!empty($validated['date_reçu_du_définition'])) {
            $validated['constitution'] = true;
        }

        $odf = Odf::create($validated);

        ActivityLogger::log('create', 'Création d\'une nouvelle ODF', Odf::class, $odf->id);

        // If constitution is false (non), redirect to edit page to add ODF steps
        if (isset($validated['constitution']) && ($validated['constitution'] == false || $validated['constitution'] == '0' || $validated['constitution'] == 0)) {
            return redirect()->route('odfs.edit', $odf)
                ->with('success', 'ODF créée avec succès. Veuillez ajouter les étapes ODF.');
        }

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
     */
    public function edit(Odf $odf): View
    {
        $localisations = \App\Models\Localisation::orderBy('CODE')->get();
        $situationAdministratives = \App\Models\SituationAdministrative::orderBy('commune')->get();
        $odfEntites = OdfEntite::orderBy('name')->get();
        $odf->load(['odfEtaps', 'members']);
        
        return view('odfs.edit', compact('odf', 'localisations', 'situationAdministratives', 'odfEntites'));
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
