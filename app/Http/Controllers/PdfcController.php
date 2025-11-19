<?php

namespace App\Http\Controllers;

use App\Models\Pdfc;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PdfcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Log PDFCs view
        ActivityLogger::log('view', 'Consultation de la liste des PDFCs', Pdfc::class);
        
        $query = Pdfc::with(['user', 'localisation', 'situationAdministrative']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('etat', 'like', "%{$search}%");
            });
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
        
        // Date range filter
        if ($request->filled('start_date')) {
            $query->where('date_de_début', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('date_de_fin', '<=', $request->end_date);
        }
        
        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $allowedSortFields = ['id', 'date_de_début', 'date_de_fin', 'etat', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }
        
        $pdfcs = $query->paginate($perPage);
        
        return view('pdfcs.index', compact('pdfcs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::where('is_deleted', false)->orderBy('name')->get();
        $localisations = \App\Models\Localisation::orderBy('CODE')->get();
        $situationAdministratives = \App\Models\SituationAdministrative::orderBy('commune')->get();
        
        return view('pdfcs.create', compact('users', 'localisations', 'situationAdministratives'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date_de_début' => 'required|date',
            'date_de_fin' => 'required|date|after_or_equal:date_de_début',
            'user_id' => 'nullable|exists:users,id',
            'localisation_id' => 'nullable|exists:localisations,id',
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
        ]);

        // Set default state - always start with "Non élaboré"
        $validated['etat'] = 'Non élaboré';

        $pdfc = Pdfc::create($validated);

        // Auto-transition: If dates are set, move to "élaboré"
        if ($pdfc->date_de_début && $pdfc->date_de_fin && $pdfc->etat == 'Non élaboré') {
            $pdfc->update(['etat' => 'élaboré']);
            ActivityLogger::log('update', "PDFC {$pdfc->id} : transition automatique vers 'élaboré'", Pdfc::class, $pdfc->id, null, $request);
        }

        ActivityLogger::logCreate(
            Pdfc::class,
            $pdfc->id,
            "PDFC créé",
            $request
        );

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'PDFC créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pdfc $pdfc): View
    {
        $pdfc->load('user', 'phases.etapes');
        
        ActivityLogger::logView(
            Pdfc::class,
            $pdfc->id,
            "PDFC {$pdfc->id}",
            request()
        );

        return view('pdfcs.show', compact('pdfc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pdfc $pdfc): View
    {
        $users = User::where('is_deleted', false)->orderBy('name')->get();
        $localisations = \App\Models\Localisation::orderBy('CODE')->get();
        $situationAdministratives = \App\Models\SituationAdministrative::orderBy('commune')->get();
        
        return view('pdfcs.edit', compact('pdfc', 'users', 'localisations', 'situationAdministratives'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pdfc $pdfc): RedirectResponse
    {
        $validated = $request->validate([
            'date_de_début' => 'required|date',
            'date_de_fin' => 'required|date|after_or_equal:date_de_début',
            'user_id' => 'nullable|exists:users,id',
            'localisation_id' => 'nullable|exists:localisations,id',
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
        ]);

        $oldValues = $pdfc->only(['date_de_début', 'date_de_fin', 'etat', 'user_id', 'localisation_id', 'situation_administrative_id']);
        $oldEtat = $pdfc->etat;
        
        // Don't update etat from form - it's managed automatically
        // Keep the current etat value
        $validated['etat'] = $pdfc->etat;
        
        $pdfc->update($validated);
        
        // Auto-transition: If dates are set and state is "Non élaboré", move to "élaboré"
        if ($pdfc->date_de_début && $pdfc->date_de_fin && $pdfc->etat == 'Non élaboré') {
            $pdfc->update(['etat' => 'élaboré']);
            ActivityLogger::log('update', "PDFC {$pdfc->id} : transition automatique vers 'élaboré'", Pdfc::class, $pdfc->id, null, $request);
        }

        $changes = array_diff_assoc($pdfc->only(['date_de_début', 'date_de_fin', 'etat', 'user_id', 'localisation_id', 'situation_administrative_id']), $oldValues);
        
        ActivityLogger::logUpdate(
            Pdfc::class,
            $pdfc->id,
            "PDFC {$pdfc->id}",
            $changes,
            $request
        );

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'PDFC mis à jour avec succès.');
    }

    /**
     * Transition PDFC to next state in cycle de vie.
     */
    public function transitionState(Request $request, Pdfc $pdfc): RedirectResponse
    {
        if (!$pdfc->canTransitionToNextState()) {
            return redirect()->route('pdfcs.show', $pdfc)
                ->with('error', 'Impossible de passer à l\'état suivant. Vérifiez que toutes les conditions sont remplies.');
        }

        $nextState = $pdfc->getNextState();
        $oldState = $pdfc->etat;
        
        $pdfc->update(['etat' => $nextState]);

        ActivityLogger::log('update', "PDFC {$pdfc->id} : transition de '{$oldState}' à '{$nextState}'", Pdfc::class, $pdfc->id, null, $request);

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', "PDFC passé à l'état '{$nextState}' avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pdfc $pdfc): RedirectResponse
    {
        $pdfcId = $pdfc->id;
        $pdfc->delete();

        ActivityLogger::logDelete(
            Pdfc::class,
            $pdfcId,
            "PDFC {$pdfcId}",
            request()
        );

        return redirect()->route('pdfcs.index')
            ->with('success', 'PDFC supprimé avec succès.');
    }
}
