<?php

namespace App\Http\Controllers;

use App\Models\Essence;
use App\Models\Foret;
use App\Models\NatureDeCoupe;
use App\Models\SituationAdministrative;
use App\Models\SituationForestiere;
use App\Models\Exploitant;

use App\Models\ZDTF;
use App\Models\DPANEF;
use App\Models\DRANEF;
use App\Models\Annee;
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

use App\Http\Requests\StoreZdtfRequest;
use App\Http\Requests\UpdateZdtfRequest;
use App\Http\Requests\StoreDpanefRequest;
use App\Http\Requests\UpdateDpanefRequest;
use App\Http\Requests\StoreDranefRequest;
use App\Http\Requests\UpdateDranefRequest;
use App\Http\Requests\StoreSituationForestiereRequest;
use App\Http\Requests\UpdateSituationForestiereRequest;
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

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
    public function forets(Request $request): View
    {
        $query = Foret::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('foret', 'like', "%{$search}%");
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
        $sortField = $request->get('sort', 'foret');
        $sortDirection = $request->get('direction', 'asc');
        
        $allowedSortFields = ['id', 'foret', 'province', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $forets = $query->paginate($perPage);

        // Get statistics for the current filtered results
        $stats = [
            'total' => $query->count(),
            'active' => $query->where('is_deleted', false)->count(),
            'recent' => $query->where('created_at', '>=', now()->subDays(30))->count(),
            'unique' => $query->distinct('foret')->count(),
        ];

        return view('settings.forets.index', compact('forets', 'stats'));
    }

    public function storeForet(StoreForetRequest $request): RedirectResponse
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

    public function storeNatureDeCoupe(StoreNatureDeCoupeRequest $request): RedirectResponse
    {
        NatureDeCoupe::create($request->only('nature_de_coupe'));
        return redirect()->route('settings.nature-de-coupes')->with('success', 'Nature de coupe ajoutée avec succès.');
    }

    public function editNatureDeCoupe(NatureDeCoupe $natureDeCoupe): View
    {
        return view('settings.nature-de-coupes.edit', compact('natureDeCoupe'));
    }

    public function updateNatureDeCoupe(UpdateNatureDeCoupeRequest $request, NatureDeCoupe $natureDeCoupe): RedirectResponse
    {
        $natureDeCoupe->update($request->only('nature_de_coupe'));
        return redirect()->route('settings.nature-de-coupes')->with('success', 'Nature de coupe mise à jour avec succès.');
    }

    public function destroyNatureDeCoupe(NatureDeCoupe $natureDeCoupe): RedirectResponse
    {
        $natureDeCoupe->update(['is_deleted' => true]);
        return redirect()->route('settings.nature-de-coupes')->with('success', 'Nature de coupe supprimée avec succès.');
    }

    // Situation Administratives Management
    public function situationAdministratives(Request $request): View
    {
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

    public function storeSituationAdministrative(StoreSituationAdministrativeRequest $request): RedirectResponse
    {
        SituationAdministrative::create($request->only(['commune', 'province']));
        return redirect()->route('settings.situation-administratives')->with('success', 'Situation administrative ajoutée avec succès.');
    }

    public function editSituationAdministrative(SituationAdministrative $situationAdministrative): View
    {
        return view('settings.situation-administratives.edit', compact('situationAdministrative'));
    }

    public function updateSituationAdministrative(UpdateSituationAdministrativeRequest $request, SituationAdministrative $situationAdministrative): RedirectResponse
    {
        $situationAdministrative->update($request->only(['commune', 'province']));
        return redirect()->route('settings.situation-administratives')->with('success', 'Situation administrative mise à jour avec succès.');
    }

    public function destroySituationAdministrative(SituationAdministrative $situationAdministrative): RedirectResponse
    {
        $situationAdministrative->update(['is_deleted' => true]);
        return redirect()->route('settings.situation-administratives')->with('success', 'Situation administrative supprimée avec succès.');
    }

    // Exploitants Management
    public function exploitants(Request $request): View
    {
        $query = Exploitant::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nom_complet', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
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

        // Get statistics for the current filtered results
        $stats = [
            'total' => $query->count(),
            'active' => $query->where('is_deleted', false)->count(),
            'recent' => $query->where('created_at', '>=', now()->subDays(30))->count(),
            'unique' => $query->distinct('nom_complet')->count(),
        ];

        return view('settings.exploitants.index', compact('exploitants', 'stats'));
    }

    public function storeExploitant(StoreExploitantRequest $request): RedirectResponse
    {
        Exploitant::create($request->all());
        return redirect()->route('settings.exploitants')->with('success', 'Exploitant ajouté avec succès.');
    }

    public function editExploitant(Exploitant $exploitant): View
    {
        return view('settings.exploitants.edit', compact('exploitant'));
    }

    public function updateExploitant(UpdateExploitantRequest $request, Exploitant $exploitant): RedirectResponse
    {
        $exploitant->update($request->all());
        return redirect()->route('settings.exploitants')->with('success', 'Exploitant mis à jour avec succès.');
    }

    public function destroyExploitant(Exploitant $exploitant): RedirectResponse
    {
        $exploitant->update(['is_deleted' => true]);
        return redirect()->route('settings.exploitants')->with('success', 'Exploitant supprimé avec succès.');
    }

    // Localisations Management
    public function localisations(Request $request): View
    {
        $query = Localisation::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('commune', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
            });
        }

        // Province filter
        if ($request->filled('province')) {
            $query->where('province', $request->get('province'));
        }

        // Region filter
        if ($request->filled('region')) {
            $query->where('region', $request->get('region'));
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
        
        $allowedSortFields = ['id', 'commune', 'province', 'region', 'created_at', 'updated_at'];
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
            'unique' => $query->distinct('commune')->count(),
        ];

        return view('settings.localisations.index', compact('localisations', 'stats'));
    }

    // ZDTFs Management
    public function zdtfs(): View
    {
        $zdtfs = ZDTF::orderBy('nom')->paginate(20);
        return view('settings.zdtfs.index', compact('zdtfs'));
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
        SituationForestiere::create($request->all());
        return redirect()->route('settings.situation-forestieres')->with('success', 'Situation forestière ajoutée avec succès.');
    }

    public function updateSituationForestiere(UpdateSituationForestiereRequest $request, SituationForestiere $situationForestiere): RedirectResponse
    {
        $situationForestiere->update($request->all());
        return redirect()->route('settings.situation-forestieres')->with('success', 'Situation forestière mise à jour avec succès.');
    }

    public function destroySituationForestiere(SituationForestiere $situationForestiere): RedirectResponse
    {
        $situationForestiere->delete();
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
            Excel::import(new EssencesImport, $request->file('file'));
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
            Excel::import(new ForetsImport, $request->file('file'));
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
            Excel::import(new NatureDeCoupesImport, $request->file('file'));
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
            Excel::import(new SituationAdministrativesImport, $request->file('file'));
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
            Excel::import(new ExploitantsImport, $request->file('file'));
            return redirect()->route('settings.exploitants')->with('success', 'Exploitants importés avec succès.');
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
            Excel::import(new LocalisationsImport, $request->file('file'));
            return redirect()->route('settings.localisations')->with('success', 'Localisations importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }


} 