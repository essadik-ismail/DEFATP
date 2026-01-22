<?php

namespace App\Http\Controllers;

use App\Models\Essence;
use App\Models\Foret;
use App\Models\SituationAdministrative;
use App\Models\NatureDeCoupe;
use App\Models\Exploitant;
use App\Models\Coperative;
use App\Models\Vocation;
use App\Models\Product;
use App\Models\Prestation;
use App\Models\ModeExploitation;
use App\Models\Dranef;
use App\Models\Dpanef;
use App\Models\Zdtf;
use App\Models\Canton;
use App\Models\Parcelle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EntityDataController extends Controller
{
    /**
     * Display unified entity data management page
     */
    public function index(Request $request): View
    {
        $perPage = $request->get('per_page', 15);
        
        // Articles entities
        $essences = Essence::where('is_deleted', false)
            ->when($request->filled('essence_search'), function($query) use ($request) {
                $query->where('essence', 'like', '%' . $request->essence_search . '%');
            })
            ->orderBy('essence')
            ->paginate($perPage, ['*'], 'essences_page')
            ->appends($request->except('essences_page'));

        $forets = Foret::where('is_deleted', false)
            ->when($request->filled('foret_search'), function($query) use ($request) {
                $query->where('foret', 'like', '%' . $request->foret_search . '%');
            })
            ->orderBy('foret')
            ->paginate($perPage, ['*'], 'forets_page')
            ->appends($request->except('forets_page'));


        $situationsAdministratives = SituationAdministrative::all();

        $natureDeCoupes = NatureDeCoupe::where('is_deleted', false)
            ->when($request->filled('nature_search'), function($query) use ($request) {
                $query->where('nature_de_coupe', 'like', '%' . $request->nature_search . '%');
            })
            ->orderBy('nature_de_coupe')
            ->paginate($perPage, ['*'], 'natures_page')
            ->appends($request->except('natures_page'));

        $exploitants = Exploitant::where('is_deleted', false)
            ->when($request->filled('exploitant_search'), function($query) use ($request) {
                $query->where('nom_complet', 'like', '%' . $request->exploitant_search . '%');
            })
            ->orderBy('nom_complet')
            ->paginate($perPage, ['*'], 'exploitants_page')
            ->appends($request->except('exploitants_page'));

        // Contracts entities (essences are already loaded above)

        $coperatives = Coperative::with('vocation')
            ->when($request->filled('coperative_search'), function($query) use ($request) {
                $query->where('nom', 'like', '%' . $request->coperative_search . '%');
            })
            ->orderBy('nom')
            ->paginate($perPage, ['*'], 'coperatives_page')
            ->appends($request->except('coperatives_page'));

        $vocations = Vocation::when($request->filled('vocation_search'), function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->vocation_search . '%');
            })
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'vocations_page')
            ->appends($request->except('vocations_page'));

        $products = Product::with(['articles', 'contracts', 'avenants'])
            ->when($request->filled('product_search'), function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->product_search . '%');
            })
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'products_page')
            ->appends($request->except('products_page'));

        $prestations = Prestation::with(['contracts', 'avenants'])
            ->when($request->filled('prestation_search'), function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->prestation_search . '%');
            })
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'prestations_page')
            ->appends($request->except('prestations_page'));

        $modeExploitations = ModeExploitation::when($request->filled('mode_exploitation_search'), function($query) use ($request) {
                $query->where('mode_exploiattion', 'like', '%' . $request->mode_exploitation_search . '%');
            })
            ->orderBy('mode_exploiattion')
            ->paginate(10, ['*'], 'mode_exploitations_page');

        $dranefs = Dranef::when($request->filled('dranef_search'), function($query) use ($request) {
                $query->where('dranef', 'like', '%' . $request->dranef_search . '%');
            })
            ->orderBy('dranef')
            ->paginate(10, ['*'], 'dranefs_page');

        $dpanefs = Dpanef::with('dranef')
            ->when($request->filled('dpanef_search'), function($query) use ($request) {
                $query->where('dpanef', 'like', '%' . $request->dpanef_search . '%');
            })
            ->orderBy('dpanef')
            ->paginate(10, ['*'], 'dpanefs_page');

        $zdtfs = Zdtf::with('dpanef.dranef')
            ->when($request->filled('zdtf_search'), function($query) use ($request) {
                $query->where('sdtf', 'like', '%' . $request->zdtf_search . '%');
            })
            ->orderBy('sdtf')
            ->paginate(10, ['*'], 'zdtfs_page');

        $cantons = Canton::with('foret')
            ->when($request->filled('canton_search'), function($query) use ($request) {
                $query->where('canton', 'like', '%' . $request->canton_search . '%');
            })
            ->orderBy('canton')
            ->paginate(10, ['*'], 'cantons_page');

        $parcelles = Parcelle::with(['foret', 'canton'])
            ->when($request->filled('parcelle_search'), function($query) use ($request) {
                $query->where('parcelle', 'like', '%' . $request->parcelle_search . '%');
            })
            ->orderBy('parcelle')
            ->paginate(10, ['*'], 'parcelles_page');

        return view('entity-data.index', compact(
            'essences',
            'forets',
            'situationsAdministratives',
            'natureDeCoupes',
            'exploitants',
            'coperatives',
            'vocations',
            'products',
            'prestations',
            'modeExploitations',
            'dranefs',
            'dpanefs',
            'zdtfs',
            'cantons',
            'parcelles'
        ));
    }
}
