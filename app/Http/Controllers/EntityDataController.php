<?php

namespace App\Http\Controllers;

use App\Models\Essence;
use App\Models\Foret;
use App\Models\Localisation;
use App\Models\SituationAdministrative;
use App\Models\NatureDeCoupe;
use App\Models\Exploitant;
use App\Models\Espece;
use App\Models\Coperative;
use App\Models\Vocation;
use App\Models\OdfEntite;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EntityDataController extends Controller
{
    /**
     * Display unified entity data management page
     */
    public function index(Request $request): View
    {
        // Articles entities
        $essences = Essence::where('is_deleted', false)
            ->when($request->filled('essence_search'), function($query) use ($request) {
                $query->where('essence', 'like', '%' . $request->essence_search . '%');
            })
            ->orderBy('essence')
            ->paginate(10, ['*'], 'essences_page');

        $forets = Foret::where('is_deleted', false)
            ->when($request->filled('foret_search'), function($query) use ($request) {
                $query->where('foret', 'like', '%' . $request->foret_search . '%');
            })
            ->orderBy('foret')
            ->paginate(10, ['*'], 'forets_page');

        $localisations = Localisation::where('is_deleted', false)
            ->when($request->filled('localisation_search'), function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('CODE', 'like', '%' . $request->localisation_search . '%')
                      ->orWhere('DRANEF', 'like', '%' . $request->localisation_search . '%')
                      ->orWhere('ENTITE', 'like', '%' . $request->localisation_search . '%');
                });
            })
            ->orderBy('CODE')
            ->paginate(10, ['*'], 'localisations_page');

        $situationsAdministratives = SituationAdministrative::all();

        $natureDeCoupes = NatureDeCoupe::where('is_deleted', false)
            ->when($request->filled('nature_search'), function($query) use ($request) {
                $query->where('nature_de_coupe', 'like', '%' . $request->nature_search . '%');
            })
            ->orderBy('nature_de_coupe')
            ->paginate(10, ['*'], 'natures_page');

        $exploitants = Exploitant::where('is_deleted', false)
            ->when($request->filled('exploitant_search'), function($query) use ($request) {
                $query->where('nom_complet', 'like', '%' . $request->exploitant_search . '%');
            })
            ->orderBy('nom_complet')
            ->paginate(10, ['*'], 'exploitants_page');

        // Contracts entities
        $especes = Espece::when($request->filled('espece_search'), function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->espece_search . '%');
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'especes_page');

        $coperatives = Coperative::with('vocation')
            ->when($request->filled('coperative_search'), function($query) use ($request) {
                $query->where('nom', 'like', '%' . $request->coperative_search . '%');
            })
            ->orderBy('nom')
            ->paginate(10, ['*'], 'coperatives_page');

        $vocations = Vocation::when($request->filled('vocation_search'), function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->vocation_search . '%');
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'vocations_page');

        $odfEntites = OdfEntite::with(['localisation', 'situationAdministrative'])
            ->when($request->filled('odf_entite_search'), function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->odf_entite_search . '%');
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'odf_entites_page');

        return view('entity-data.index', compact(
            'essences',
            'forets',
            'localisations',
            'situationsAdministratives',
            'natureDeCoupes',
            'exploitants',
            'especes',
            'coperatives',
            'vocations',
            'odfEntites'
        ));
    }
}
