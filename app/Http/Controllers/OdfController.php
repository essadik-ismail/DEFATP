<?php

namespace App\Http\Controllers;

use App\Models\Odf;
use App\Models\Member;
use App\Models\Activity;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Log ODFs view
        ActivityLogger::log('view', 'Consultation de la liste des ODFs', Odf::class);
        
        $query = Odf::with(['user', 'localisation', 'situationAdministrative']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('présidente', 'like', "%{$search}%")
                  ->orWhere('vice_présidente', 'like', "%{$search}%")
                  ->orWhere('trésorière', 'like', "%{$search}%");
            });
        }
        
        // Date filters
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }
        
        // Localisation filter
        if ($request->filled('localisation_id')) {
            $query->where('localisation_id', $request->localisation_id);
        }
        
        // Situation Administrative filter
        if ($request->filled('situation_administrative_id')) {
            $query->where('situation_administrative_id', $request->situation_administrative_id);
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
        
        $allowedSortFields = ['id', 'présidente', 'vice_présidente', 'trésorière', 'created_at', 'updated_at'];
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
        
        return view('odfs.index', compact('odfs', 'stats', 'localisations', 'situationAdministratives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $localisations = \App\Models\Localisation::orderBy('CODE')->get();
        $situationAdministratives = \App\Models\SituationAdministrative::orderBy('commune')->get();
        
        return view('odfs.create', compact('localisations', 'situationAdministratives'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'présidente' => 'nullable|string|max:255',
            'vice_présidente' => 'nullable|string|max:255',
            'trésorière' => 'nullable|string|max:255',
            'reçu_du_dépôt' => 'nullable|string',
            'constitution' => 'nullable|string',
            'localisation_id' => 'nullable|exists:localisations,id',
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
        ]);

        $odf = Odf::create([
            'présidente' => $validated['présidente'] ?? null,
            'vice_présidente' => $validated['vice_présidente'] ?? null,
            'trésorière' => $validated['trésorière'] ?? null,
            'reçu_du_dépôt' => $validated['reçu_du_dépôt'] ?? null,
            'constitution' => $validated['constitution'] ?? null,
            'localisation_id' => $validated['localisation_id'] ?? null,
            'situation_administrative_id' => $validated['situation_administrative_id'] ?? null,
            'user_id' => auth()->id(),
        ]);

        ActivityLogger::log('create', 'Création d\'une nouvelle ODF', Odf::class, $odf->id);

        return redirect()->route('odfs.index')
            ->with('success', 'ODF créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Odf $odf): View
    {
        $odf->load(['user', 'members', 'activities', 'localisation', 'situationAdministrative']);
        
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
        
        return view('odfs.edit', compact('odf', 'localisations', 'situationAdministratives'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Odf $odf): RedirectResponse
    {
        $validated = $request->validate([
            'présidente' => 'nullable|string|max:255',
            'vice_présidente' => 'nullable|string|max:255',
            'trésorière' => 'nullable|string|max:255',
            'reçu_du_dépôt' => 'nullable|string',
            'constitution' => 'nullable|string',
            'localisation_id' => 'nullable|exists:localisations,id',
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
        ]);

        $odf->update([
            'présidente' => $validated['présidente'] ?? null,
            'vice_présidente' => $validated['vice_présidente'] ?? null,
            'trésorière' => $validated['trésorière'] ?? null,
            'reçu_du_dépôt' => $validated['reçu_du_dépôt'] ?? null,
            'constitution' => $validated['constitution'] ?? null,
            'localisation_id' => $validated['localisation_id'] ?? null,
            'situation_administrative_id' => $validated['situation_administrative_id'] ?? null,
        ]);

        ActivityLogger::log('update', 'Modification de l\'ODF', Odf::class, $odf->id);

        return redirect()->route('odfs.index')
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
            'nom' => 'required|string|max:255',
            'tel' => 'nullable|string|max:255',
            'type' => 'nullable|in:Association,Coopérative,Entreprise,Élu,Citoyen',
        ]);

        $member = $odf->members()->create($validated);

        ActivityLogger::log('create', 'Ajout d\'un membre à l\'ODF', Member::class, $member->id);

        return redirect()->route('odfs.show', $odf)
            ->with('success', 'Membre ajouté avec succès.');
    }

    /**
     * Update a member.
     */
    public function updateMember(Request $request, Odf $odf, Member $member): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'tel' => 'nullable|string|max:255',
            'type' => 'nullable|in:Association,Coopérative,Entreprise,Élu,Citoyen',
        ]);

        $member->update($validated);

        ActivityLogger::log('update', 'Modification d\'un membre de l\'ODF', Member::class, $member->id);

        return redirect()->route('odfs.show', $odf)
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

        return redirect()->route('odfs.show', $odf)
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
}
