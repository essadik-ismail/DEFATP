<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEssenceRequest;
use App\Http\Requests\UpdateEssenceRequest;
use App\Http\Requests\StoreForetRequest;
use App\Http\Requests\UpdateForetRequest;
use App\Http\Requests\StoreNatureDeCoupeRequest;
use App\Http\Requests\UpdateNatureDeCoupeRequest;
use App\Http\Requests\StoreSituationAdministrativeRequest;
use App\Http\Requests\UpdateSituationAdministrativeRequest;
use App\Http\Requests\StoreExploitantRequest;
use App\Http\Requests\UpdateExploitantRequest;
use App\Http\Requests\StoreLocalisationRequest;
use App\Http\Requests\UpdateLocalisationRequest;
use App\Models\Essence;
use App\Models\Foret;
use App\Models\NatureDeCoupe;
use App\Models\SituationAdministrative;
use App\Models\Exploitant;
use App\Models\Localisation;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EssencesExport;
use App\Exports\ForetsExport;
use App\Exports\NatureDeCoupesExport;
use App\Exports\SituationAdministrativesExport;
use App\Exports\ExploitantsExport;
use App\Exports\LocalisationsExport;
use App\Imports\EssencesImport;
use App\Imports\ForetsImport;
use App\Imports\NatureDeCoupesImport;
use App\Imports\SituationAdministrativesImport;
use App\Imports\ExploitantsImport;
use App\Imports\LocalisationsImport;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('settings.index');
    }



    // Essences Management
    public function essences(Request $request): View
    {
        $query = Essence::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('essence', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->get('status')) {
                case 'active':
                    $query->where('is_deleted', false);
                    break;
                case 'deleted':
                    $query->where('is_deleted', true);
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->get('date_to') . ' 23:59:59');
        }

        // Sorting
        $sortField = $request->get('sort', 'essence');
        $sortDirection = $request->get('direction', 'asc');
        
        $allowedSortFields = ['id', 'essence', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $essences = $query->paginate($perPage);

        // Get statistics for the current filtered results
        $stats = [
            'total' => $query->count(),
            'active' => $query->where('is_deleted', false)->count(),
            'recent' => $query->where('created_at', '>=', now()->subDays(30))->count(),
            'unique' => $query->distinct('essence')->count(),
        ];

        return view('settings.essences.index', compact('essences', 'stats'));
    }

    public function createEssence(): View
    {
        return view('settings.essences.create');
    }

    public function storeEssence(StoreEssenceRequest $request): RedirectResponse
    {
        Essence::create($request->only('essence'));
        return redirect()->route('settings.essences')->with('success', 'Essence ajoutée avec succès.');
    }

    public function editEssence(Essence $essence): View
    {
        return view('settings.essences.edit', compact('essence'));
    }

    public function updateEssence(UpdateEssenceRequest $request, Essence $essence): RedirectResponse
    {
        $essence->update($request->only('essence'));
        return redirect()->route('settings.essences')->with('success', 'Essence mise à jour avec succès.');
    }

    public function destroyEssence(Essence $essence): RedirectResponse
    {
        $essence->update(['is_deleted' => true]);
        return redirect()->route('settings.essences')->with('success', 'Essence supprimée avec succès.');
    }

    // Forets Management
    public function forets(): View
    {
        $forets = Foret::where('is_deleted', false)->orderBy('foret')->paginate(15);
        return view('settings.forets.index', compact('forets'));
    }

    public function foretsMap(): View
    {
        // Log forest map view
        ActivityLogger::log('view', 'Consultation de la carte des forêts', Foret::class);
        
        // Get all forests with coordinates
        $forests = Foret::where('is_deleted', false)
            ->where('lat', '!=', '0')
            ->where('log', '!=', '0')
            ->whereNotNull('lat')
            ->whereNotNull('log')
            ->orderBy('foret')
            ->get();
            
        // Get statistics
        $totalForests = Foret::where('is_deleted', false)->count();
        $geolocatedForests = $forests->count();
        $nonGeolocatedForests = $totalForests - $geolocatedForests;
        
        $stats = [
            'total' => $totalForests,
            'geolocated' => $geolocatedForests,
            'non_geolocated' => $nonGeolocatedForests,
            'percentage' => $totalForests > 0 ? round(($geolocatedForests / $totalForests) * 100, 1) : 0
        ];
        
        return view('settings.forets.map', compact('forests', 'stats'));
    }

    public function createForet(): View
    {
        return view('settings.forets.create');
    }

    public function storeForet(Request $request): RedirectResponse
    {
        Foret::create($request->only(['foret', 'lat', 'log', 'province']));
        return redirect()->route('settings.forets')->with('success', 'Forêt ajoutée avec succès.');
    }

    public function editForet(Foret $foret): View
    {
        return view('settings.forets.edit', compact('foret'));
    }

    public function updateForet(UpdateForetRequest $request, Foret $foret): RedirectResponse
    {
        $foret->update($request->only(['foret', 'lat', 'log', 'province']));
        return redirect()->route('settings.forets')->with('success', 'Forêt mise à jour avec succès.');
    }

    public function destroyForet(Foret $foret): RedirectResponse
    {
        $foret->update(['is_deleted' => true]);
        return redirect()->route('settings.forets')->with('success', 'Forêt supprimée avec succès.');
    }

    // Nature de Coupes Management
    public function natureDeCoupes(Request $request): View
    {
        $query = NatureDeCoupe::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('nature_de_coupe', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->get('status')) {
                case 'active':
                    $query->where('is_deleted', false);
                    break;
                case 'deleted':
                    $query->where('is_deleted', true);
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->get('date_to') . ' 23:59:59');
        }

        // Sorting
        $sortField = $request->get('sort', 'nature_de_coupe');
        $sortDirection = $request->get('direction', 'asc');
        
        $allowedSortFields = ['id', 'nature_de_coupe', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $natureDeCoupes = $query->paginate($perPage);

        // Get statistics for the current filtered results
        $stats = [
            'total' => $query->count(),
            'active' => $query->where('is_deleted', false)->count(),
            'recent' => $query->where('created_at', '>=', now()->subDays(30))->count(),
            'unique' => $query->distinct('nature_de_coupe')->count(),
        ];

        return view('settings.nature-de-coupes.index', compact('natureDeCoupes', 'stats'));
    }

    public function createNatureDeCoupe(): View
    {
        return view('settings.nature-de-coupes.create');
    }

    public function storeNatureDeCoupe(StoreNatureDeCoupeRequest $request): RedirectResponse
    {
        $natureDeCoupe = NatureDeCoupe::create($request->only('nature_de_coupe'));
        
        // Log nature de coupe creation
        ActivityLogger::logCreate(
            NatureDeCoupe::class,
            $natureDeCoupe->id,
            "Nature de coupe {$natureDeCoupe->nature_de_coupe}",
            $request
        );
        
        return redirect()->route('settings.nature-de-coupes')->with('success', 'Nature de coupe ajoutée avec succès.');
    }

    public function editNatureDeCoupe(NatureDeCoupe $natureDeCoupe): View
    {
        // Log nature de coupe edit view
        ActivityLogger::logView(
            NatureDeCoupe::class,
            $natureDeCoupe->id,
            "Nature de coupe {$natureDeCoupe->nature_de_coupe}",
            request()
        );
        
        return view('settings.nature-de-coupes.edit', compact('natureDeCoupe'));
    }

    public function updateNatureDeCoupe(UpdateNatureDeCoupeRequest $request, NatureDeCoupe $natureDeCoupe): RedirectResponse
    {
        $oldData = $natureDeCoupe->only(['nature_de_coupe']);
        $natureDeCoupe->update($request->only('nature_de_coupe'));
        
        // Log nature de coupe update
        $changes = array_diff_assoc($natureDeCoupe->fresh()->only(['nature_de_coupe']), $oldData);
        ActivityLogger::logUpdate(
            NatureDeCoupe::class,
            $natureDeCoupe->id,
            "Nature de coupe {$natureDeCoupe->nature_de_coupe}",
            $changes,
            $request
        );
        
        return redirect()->route('settings.nature-de-coupes')->with('success', 'Nature de coupe mise à jour avec succès.');
    }

    public function destroyNatureDeCoupe(NatureDeCoupe $natureDeCoupe): RedirectResponse
    {
        $natureName = $natureDeCoupe->nature_de_coupe;
        $natureDeCoupe->update(['is_deleted' => true]);
        
        // Log nature de coupe deletion
        ActivityLogger::logDelete(
            NatureDeCoupe::class,
            $natureDeCoupe->id,
            "Nature de coupe {$natureName}",
            request()
        );
        
        return redirect()->route('settings.nature-de-coupes')->with('success', 'Nature de coupe supprimée avec succès.');
    }

    // Situation Administratives Management
    public function situationAdministratives(Request $request): View
    {
        // Log situation administratives view
        ActivityLogger::log('view', 'Consultation de la liste des situations administratives', SituationAdministrative::class);
        
        $query = SituationAdministrative::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('commune', 'like', "%{$search}%");
        }

        // Province filter
        if ($request->filled('province')) {
            $query->where('province', $request->get('province'));
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->get('status')) {
                case 'active':
                    $query->where('is_deleted', false);
                    break;
                case 'deleted':
                    $query->where('is_deleted', true);
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->get('date_to') . ' 23:59:59');
        }

        // Sorting
        $sortField = $request->get('sort', 'commune');
        $sortDirection = $request->get('direction', 'asc');
        
        $allowedSortFields = ['id', 'commune', 'province', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $situationAdministratives = $query->paginate($perPage);

        // Get statistics for the current filtered results
        $stats = [
            'total' => $query->count(),
            'active' => $query->where('is_deleted', false)->count(),
            'recent' => $query->where('created_at', '>=', now()->subDays(30))->count(),
            'unique' => $query->distinct('commune')->count(),
        ];

        return view('settings.situation-administratives.index', compact('situationAdministratives', 'stats'));
    }

    public function createSituationAdministrative(): View
    {
        return view('settings.situation-administratives.create');
    }

    public function storeSituationAdministrative(StoreSituationAdministrativeRequest $request): RedirectResponse
    {
        $situationAdministrative = SituationAdministrative::create($request->only(['commune', 'province']));
        
        // Log situation administrative creation
        ActivityLogger::logCreate(
            SituationAdministrative::class,
            $situationAdministrative->id,
            "Situation administrative {$situationAdministrative->commune}",
            $request
        );
        
        return redirect()->route('settings.situation-administratives')->with('success', 'Situation administrative ajoutée avec succès.');
    }

    public function editSituationAdministrative(SituationAdministrative $situationAdministrative): View
    {
        // Log situation administrative edit view
        ActivityLogger::logView(
            SituationAdministrative::class,
            $situationAdministrative->id,
            "Situation administrative {$situationAdministrative->commune}",
            request()
        );
        
        return view('settings.situation-administratives.edit', compact('situationAdministrative'));
    }

    public function updateSituationAdministrative(UpdateSituationAdministrativeRequest $request, SituationAdministrative $situationAdministrative): RedirectResponse
    {
        $oldData = $situationAdministrative->only(['commune', 'province']);
        $situationAdministrative->update($request->only(['commune', 'province']));
        
        // Log situation administrative update
        $changes = array_diff_assoc($situationAdministrative->fresh()->only(['commune', 'province']), $oldData);
        ActivityLogger::logUpdate(
            SituationAdministrative::class,
            $situationAdministrative->id,
            "Situation administrative {$situationAdministrative->commune}",
            $changes,
            $request
        );
        
        return redirect()->route('settings.situation-administratives')->with('success', 'Situation administrative mise à jour avec succès.');
    }

    public function destroySituationAdministrative(SituationAdministrative $situationAdministrative): RedirectResponse
    {
        $communeName = $situationAdministrative->commune;
        $situationAdministrative->update(['is_deleted' => true]);
        
        // Log situation administrative deletion
        ActivityLogger::logDelete(
            SituationAdministrative::class,
            $situationAdministrative->id,
            "Situation administrative {$communeName}",
            request()
        );
        
        return redirect()->route('settings.situation-administratives')->with('success', 'Situation administrative supprimée avec succès.');
    }

    // Exploitants Management
    public function exploitants(Request $request): View
    {
        // Log exploitants view
        ActivityLogger::log('view', 'Consultation de la liste des exploitants', Exploitant::class);
        
        // Get date filters from request
        $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : null;
        
        $query = Exploitant::query();
        
        // Apply date filtering if provided
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nom_complet', 'like', "%{$search}%")
                  ->orWhere('raison_sociale', 'like', "%{$search}%")
                  ->orWhere('n_cin', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('numero', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->get('categorie'));
        }

        // Activity filter
        if ($request->filled('activite')) {
            $query->where('activite', $request->get('activite'));
        }

        // Exclusion filter
        if ($request->filled('exclusion')) {
            if ($request->get('exclusion') === 'active') {
                $query->where('exclusion', false);
            } elseif ($request->get('exclusion') === 'excluded') {
                $query->where('exclusion', true);
            }
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->get('status')) {
                case 'active':
                    $query->where('is_deleted', false);
                    break;
                case 'deleted':
                    $query->where('is_deleted', true);
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        // Legacy date range filter (for backward compatibility)
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->get('date_to') . ' 23:59:59');
        }

        // Sorting
        $sortField = $request->get('sort', 'nom_complet');
        $sortDirection = $request->get('direction', 'asc');
        
        $allowedSortFields = ['id', 'nom_complet', 'telephone', 'email', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $exploitants = $query->paginate($perPage);

        // Calculate statistics based on the same filtered query as the exploitants
        $filteredQuery = Exploitant::query();
        
        // Apply the same filters as the main query
        if ($startDate && $endDate) {
            $filteredQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        if ($request->filled('search')) {
            $search = $request->get('search');
            $filteredQuery->where(function($q) use ($search) {
                $q->where('nom_complet', 'like', "%{$search}%")
                  ->orWhere('raison_sociale', 'like', "%{$search}%")
                  ->orWhere('n_cin', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('numero', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('categorie')) {
            $filteredQuery->where('categorie', $request->get('categorie'));
        }
        
        if ($request->filled('activite')) {
            $filteredQuery->where('activite', $request->get('activite'));
        }
        
        if ($request->filled('exclusion')) {
            if ($request->get('exclusion') === 'active') {
                $filteredQuery->where('exclusion', false);
            } elseif ($request->get('exclusion') === 'excluded') {
                $filteredQuery->where('exclusion', true);
            }
        }
        
        if ($request->filled('status')) {
            switch ($request->get('status')) {
                case 'active':
                    $filteredQuery->where('is_deleted', false);
                    break;
                case 'deleted':
                    $filteredQuery->where('is_deleted', true);
                    break;
                case 'recent':
                    $filteredQuery->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        // Get statistics for the current filtered results
        $stats = [
            'total' => $exploitants->total(),
            'active' => (clone $filteredQuery)->where('is_deleted', false)->count(),
            'deleted' => (clone $filteredQuery)->where('is_deleted', true)->count(),
            'recent' => (clone $filteredQuery)->where('created_at', '>=', now()->subDays(30))->count(),
            'unique' => (clone $filteredQuery)->distinct('nom_complet')->count(),
            'excluded' => (clone $filteredQuery)->where('exclusion', true)->count(),
            'categories' => (clone $filteredQuery)->distinct('categorie')->count(),
            'activities' => (clone $filteredQuery)->distinct('activite')->count(),
        ];

        return view('settings.exploitants.index', compact('exploitants', 'stats'));
    }

    public function showExploitant(Exploitant $exploitant): View
    {
        return view('settings.exploitants.show', compact('exploitant'));
    }

    public function carteProfessionnelle(Exploitant $exploitant): View
    {
        return view('settings.exploitants.carte-professionnelle', compact('exploitant'));
    }

    public function verifyExploitant(Exploitant $exploitant): View
    {
        return view('settings.exploitants.verify', compact('exploitant'));
    }


    public function createExploitant(): View
    {
        $localisations = Localisation::where('is_deleted', false)->orderBy('CODE')->get();
        return view('settings.exploitants.create', compact('localisations'));
    }

    public function storeExploitant(StoreExploitantRequest $request)
    {
        try {
            $validated = $request->validated();
            $exploitant = Exploitant::create($validated);
            
            // Log exploitant creation
            ActivityLogger::logCreate(
                Exploitant::class,
                $exploitant->id,
                "Exploitant {$exploitant->nom_complet}",
                $request
            );
            
            // Check if this is an AJAX request
            if ($request->ajax()) {
                $isCreateAndNext = $request->input('action') === 'create_and_next';
                
                return response()->json([
                    'success' => true,
                    'message' => 'Exploitant ajouté avec succès.',
                    'create_and_next' => $isCreateAndNext,
                    'exploitant' => [
                        'id' => $exploitant->id,
                        'numero' => $exploitant->numero,
                        'nom_complet' => $exploitant->nom_complet,
                        'cin' => $exploitant->cin
                    ]
                ]);
            }
            
            // Handle create_and_next action for regular requests
            if ($request->input('action') === 'create_and_next') {
                return redirect()->route('exploitants.create')
                    ->with('success', 'Exploitant ajouté avec succès. Vous pouvez créer un autre exploitant.');
            }
            
            return redirect()->route('exploitants.index')->with('success', 'Exploitant ajouté avec succès.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'exploitant: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'exploitant: ' . $e->getMessage());
        }
    }

    public function editExploitant(Exploitant $exploitant): View
    {
        $localisations = Localisation::where('is_deleted', false)->orderBy('CODE')->get();
        return view('settings.exploitants.edit', compact('exploitant', 'localisations'));
    }

    public function updateExploitant(UpdateExploitantRequest $request, Exploitant $exploitant): RedirectResponse
    {
        $oldData = $exploitant->only(['nom_complet', 'telephone', 'email']);
        $exploitant->update($request->all());
        
        // Log exploitant update
        $changes = array_diff_assoc($exploitant->fresh()->only(['nom_complet', 'telephone', 'email']), $oldData);
        ActivityLogger::logUpdate(
            Exploitant::class,
            $exploitant->id,
            "Exploitant {$exploitant->nom_complet}",
            $changes,
            $request
        );
        
        return redirect()->route('exploitants.index')->with('success', 'Exploitant mis à jour avec succès.');
    }

    public function destroyExploitant(Exploitant $exploitant): RedirectResponse
    {
        $exploitantName = $exploitant->nom_complet;
        $exploitant->update(['is_deleted' => true]);
        
        // Log exploitant deletion
        ActivityLogger::logDelete(
            Exploitant::class,
            $exploitant->id,
            "Exploitant {$exploitantName}",
            request()
        );
        
        return redirect()->route('exploitants.index')->with('success', 'Exploitant supprimé avec succès.');
    }

    // Localisations Management
    public function localisations(Request $request): View
    {
        // Log localisations view
        ActivityLogger::log('view', 'Consultation de la liste des localisations', Localisation::class);
        
        $query = Localisation::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('CODE', 'like', "%{$search}%")
                  ->orWhere('DRANEF', 'like', "%{$search}%")
                  ->orWhere('ENTITE', 'like', "%{$search}%");
            });
        }

        // Province filter
        if ($request->filled('province')) {
            $query->where('DRANEF', $request->get('province'));
        }

        // Region filter
        if ($request->filled('region')) {
            $query->where('DRANEF', 'like', $request->get('region') . '%');
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->get('status')) {
                case 'active':
                    $query->where('is_deleted', false);
                    break;
                case 'deleted':
                    $query->where('is_deleted', true);
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->get('date_to') . ' 23:59:59');
        }

        // Sorting
        $sortField = $request->get('sort', 'CODE');
        $sortDirection = $request->get('direction', 'asc');
        
        $allowedSortFields = ['id', 'CODE', 'DRANEF', 'ENTITE', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $localisations = $query->paginate($perPage);

        // Get statistics for the current filtered results
        $stats = [
            'total' => $query->count(),
            'active' => $query->where('is_deleted', false)->count(),
            'recent' => $query->where('created_at', '>=', now()->subDays(30))->count(),
            'unique' => $query->distinct('CODE')->count(),
        ];

        return view('settings.localisations.index', compact('localisations', 'stats'));
    }

    public function createLocalisation(): View
    {
        return view('settings.localisations.create');
    }

    public function storeLocalisation(Request $request): RedirectResponse
    {
        $request->validate([
            'CODE' => 'required|string|max:255',
            'DRANEF' => 'required|string|max:255',
            'ENTITE' => 'required|string|max:255',
        ]);

        $localisation = Localisation::create([
            'CODE' => $request->CODE,
            'DRANEF' => $request->DRANEF,
            'ENTITE' => $request->ENTITE,
        ]);
        
        // Log localisation creation
        ActivityLogger::logCreate(
            Localisation::class,
            $localisation->id,
            "Localisation {$localisation->CODE}",
            $request
        );

        return redirect()->route('settings.localisations')->with('success', 'Localisation ajoutée avec succès.');
    }

    public function editLocalisation(Localisation $localisation): View
    {
        // Log localisation edit view
        ActivityLogger::logView(
            Localisation::class,
            $localisation->id,
            "Localisation {$localisation->CODE}",
            request()
        );
        
        return view('settings.localisations.edit', compact('localisation'));
    }

    public function updateLocalisation(Request $request, Localisation $localisation): RedirectResponse
    {
        $oldData = $localisation->only(['CODE', 'DRANEF', 'ENTITE']);
        $localisation->update([
            'CODE' => $request->CODE,
            'DRANEF' => $request->DRANEF,
            'ENTITE' => $request->ENTITE,
        ]);
        
        // Log localisation update
        $changes = array_diff_assoc($localisation->fresh()->only(['CODE', 'DRANEF', 'ENTITE']), $oldData);
        ActivityLogger::logUpdate(
            Localisation::class,
            $localisation->id,
            "Localisation {$localisation->CODE}",
            $changes,
            $request
        );
        
        return redirect()->route('settings.localisations')->with('success', 'Localisation mise à jour avec succès.');
    }

    public function destroyLocalisation(Localisation $localisation): RedirectResponse
    {
        $localisationCode = $localisation->CODE;
        $localisation->update(['is_deleted' => true]);
        
        // Log localisation deletion
        ActivityLogger::logDelete(
            Localisation::class,
            $localisation->id,
            "Localisation {$localisationCode}",
            request()
        );
        
        return redirect()->route('settings.localisations')->with('success', 'Localisation supprimée avec succès.');
    }

    // ZDTF Management
    public function zdtfs(Request $request): View
    {
        // Log ZDTF view
        ActivityLogger::log('view', 'Consultation de la liste des ZDTF', null);
        
        $query = ZDTF::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('zdtf', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->get('status')) {
                case 'active':
                    $query->where('is_deleted', false);
                    break;
                case 'deleted':
                    $query->where('is_deleted', true);
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->get('date_to') . ' 23:59:59');
        }

        // Sorting
        $sortField = $request->get('sort', 'zdtf');
        $sortDirection = $request->get('direction', 'asc');
        
        $allowedSortFields = ['id', 'zdtf', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $zdtfs = $query->paginate($perPage);

        // Get statistics for the current filtered results
        $stats = [
            'total' => $query->count(),
            'active' => $query->where('is_deleted', false)->count(),
            'recent' => $query->where('created_at', '>=', now()->subDays(30))->count(),
            'unique' => $query->distinct('zdtf')->count(),
        ];

        return view('settings.zdtfs.index', compact('zdtfs', 'stats'));
    }

    public function storeZdtf(StoreZdtfRequest $request): RedirectResponse
    {
        ZDTF::create($request->only('zdtf'));
        return redirect()->route('settings.zdtfs')->with('success', 'ZDTF ajoutée avec succès.');
    }

    public function updateZdtf(UpdateZdtfRequest $request, ZDTF $zdtf): RedirectResponse
    {
        $zdtf->update($request->only('zdtf'));
        return redirect()->route('settings.zdtfs')->with('success', 'ZDTF mise à jour avec succès.');
    }

    public function destroyZdtf(ZDTF $zdtf): RedirectResponse
    {
        $zdtf->delete();
        return redirect()->route('settings.zdtfs')->with('success', 'ZDTF supprimée avec succès.');
    }

    // DPANEFs Management
    public function dpanefs(): View
    {
        $dpanefs = DPANEF::orderBy('nom')->paginate(20);
        return view('settings.dpanefs.index', compact('dpanefs'));
    }

    public function storeDpanef(StoreDpanefRequest $request): RedirectResponse
    {
        DPANEF::create($request->only('dpanef'));
        return redirect()->route('settings.dpanefs')->with('success', 'DPANEF ajoutée avec succès.');
    }

    public function updateDpanef(UpdateDpanefRequest $request, DPANEF $dpanef): RedirectResponse
    {
        $dpanef->update($request->only('dpanef'));
        return redirect()->route('settings.dpanefs')->with('success', 'DPANEF mise à jour avec succès.');
    }

    public function destroyDpanef(DPANEF $dpanef): RedirectResponse
    {
        $dpanef->delete();
        return redirect()->route('settings.dpanefs')->with('success', 'DPANEF supprimée avec succès.');
    }

    // DRANEFs Management
    public function dranefs(): View
    {
        $dranefs = DRANEF::orderBy('nom')->paginate(20);
        return view('settings.dranefs.index', compact('dranefs'));
    }

    public function storeDranef(StoreDranefRequest $request): RedirectResponse
    {
        DRANEF::create($request->only('dranef'));
        return redirect()->route('settings.dranefs')->with('success', 'DRANEF ajoutée avec succès.');
    }

    public function updateDranef(UpdateDranefRequest $request, DRANEF $dranef): RedirectResponse
    {
        $dranef->update($request->only('dranef'));
        return redirect()->route('settings.dranefs')->with('success', 'DRANEF mise à jour avec succès.');
    }

    public function destroyDranef(DRANEF $dranef): RedirectResponse
    {
        $dranef->delete();
        return redirect()->route('settings.dranefs')->with('success', 'DRANEF supprimée avec succès.');
    }

    // Situation Forestieres Management
    public function situationForestieres(): View
    {
        $situationForestieres = SituationForestiere::with(['annee', 'zdtf', 'dpanef', 'dranef'])->paginate(20);
        $annees = Annee::getYearsForSelect();
        $zdtfs = ZDTF::orderBy('nom')->get();
        $dpanefs = DPANEF::orderBy('nom')->get();
        $dranefs = DRANEF::orderBy('nom')->get();
        
        return view('settings.situation-forestieres.index', compact('situationForestieres', 'annees', 'zdtfs', 'dpanefs', 'dranefs'));
    }

    public function storeSituationForestiere(StoreSituationForestiereRequest $request): RedirectResponse
    {
        $situationForestiere = SituationForestiere::create($request->all());
        
        // Log situation forestiere creation
        ActivityLogger::logCreate(
            SituationForestiere::class,
            $situationForestiere->id,
            "Situation forestière {$situationForestiere->annee->annee}",
            $request
        );
        
        return redirect()->route('settings.situation-forestieres')->with('success', 'Situation forestière ajoutée avec succès.');
    }

    public function updateSituationForestiere(UpdateSituationForestiereRequest $request, SituationForestiere $situationForestiere): RedirectResponse
    {
        $oldData = $situationForestiere->only(['annee_id', 'zdtf_id', 'dpanef_id', 'dranef_id']);
        $situationForestiere->update($request->all());
        
        // Log situation forestiere update
        $changes = array_diff_assoc($situationForestiere->fresh()->only(['annee_id', 'zdtf_id', 'dpanef_id', 'dranef_id']), $oldData);
        ActivityLogger::logUpdate(
            SituationForestiere::class,
            $situationForestiere->id,
            "Situation forestière {$situationForestiere->annee->annee}",
            $changes,
            $request
        );
        
        return redirect()->route('settings.situation-forestieres')->with('success', 'Situation forestière mise à jour avec succès.');
    }

    public function destroySituationForestiere(SituationForestiere $situationForestiere): RedirectResponse
    {
        $situationName = $situationForestiere->annee->annee;
        $situationForestiere->delete();
        
        // Log situation forestiere deletion
        ActivityLogger::logDelete(
            SituationForestiere::class,
            $situationForestiere->id,
            "Situation forestière {$situationName}",
            request()
        );
        
        return redirect()->route('settings.situation-forestieres')->with('success', 'Situation forestière supprimée avec succès.');
    }

    // Export methods
    public function exportEssences(Request $request)
    {
        $filters = $request->only(['search', 'status', 'date_from', 'date_to', 'sort', 'direction']);
        return Excel::download(new EssencesExport($filters), 'essences_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportForets(Request $request)
    {
        $filters = $request->only(['search', 'province', 'status', 'date_from', 'date_to', 'sort', 'direction']);
        return Excel::download(new ForetsExport($filters), 'forets_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportNatureDeCoupes(Request $request)
    {
        $filters = $request->only(['search', 'status', 'date_from', 'date_to', 'sort', 'direction']);
        return Excel::download(new NatureDeCoupesExport($filters), 'nature_de_coupes_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportSituationAdministratives(Request $request)
    {
        $filters = $request->only(['search', 'province', 'status', 'date_from', 'date_to', 'sort', 'direction']);
        return Excel::download(new SituationAdministrativesExport($filters), 'situation_administratives_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportExploitants(Request $request)
    {
        $filters = $request->only(['search', 'status', 'date_from', 'date_to', 'sort', 'direction']);
        return Excel::download(new ExploitantsExport($filters), 'exploitants_' . date('Y-m-d_H-i-s') . '.xlsx');
    }



    public function exportLocalisations(Request $request)
    {
        $filters = $request->only(['search', 'province', 'region', 'status', 'date_from', 'date_to', 'sort', 'direction']);
        return Excel::download(new LocalisationsExport($filters), 'localisations_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    // Import methods
    public function importEssences(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new EssencesImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log import action
            ActivityLogger::logImport(
                'Essences',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->route('settings.essences')->with('success', 'Essences importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importForets(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new ForetsImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log import action
            ActivityLogger::logImport(
                'Forêts',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->route('settings.forets')->with('success', 'Forêts importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importNatureDeCoupes(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new NatureDeCoupesImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log import action
            ActivityLogger::logImport(
                'Natures de Coupes',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->route('settings.nature-de-coupes')->with('success', 'Natures de coupe importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importSituationAdministratives(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new SituationAdministrativesImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log import action
            ActivityLogger::logImport(
                'Situations Administratives',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->route('settings.situation-administratives')->with('success', 'Situations administratives importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importExploitants(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new ExploitantsImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log import action
            ActivityLogger::logImport(
                'Exploitants',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->route('exploitants.index')->with('success', 'Exploitants importés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importLocalisations(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new LocalisationsImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log import action
            ActivityLogger::logImport(
                'Localisations',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->route('settings.localisations')->with('success', 'Localisations importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }


} 