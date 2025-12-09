<?php

namespace App\Http\Controllers;

use App\Models\Partenariat;
use App\Models\Localisation;
use App\Models\Essence;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PartenariatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $partenariats = Partenariat::with(['localisation', 'essence'])
            ->when($request->filled('search'), function($query) use ($request) {
                $search = $request->search;
                $query->where('nom_association', 'like', '%' . $search . '%')
                      ->orWhere('num_contract', 'like', '%' . $search . '%')
                      ->orWhere('nom_périmètre', 'like', '%' . $search . '%')
                      ->orWhereHas('localisation', function($q) use ($search) {
                          $q->where('CODE', 'like', '%' . $search . '%')
                            ->orWhere('ENTITE', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('essence', function($q) use ($search) {
                          $q->where('essence', 'like', '%' . $search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('partenariats.index', compact('partenariats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $localisations = Localisation::orderBy('CODE')->get();
        $essences = Essence::orderBy('essence')->get();
        return view('partenariats.create', compact('localisations', 'essences'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'localisation_id' => 'nullable|exists:localisations,id',
            'nom_association' => 'nullable|string|max:255',
            'nombre_adherents_association' => 'nullable|integer|min:0',
            'date_creation_association' => 'nullable|date',
            'superficie' => 'nullable|numeric|min:0',
            'nom_périmètre' => 'nullable|string|max:255',
            'essence_id' => 'nullable|exists:essences,id',
            'object_cmd' => 'nullable|string',
            'num_contract' => 'nullable|string|max:255',
            'date_signature_contract' => 'nullable|date',
            'num_avenant' => 'nullable|string|max:255',
            'nombre_avenant' => 'nullable|integer|min:0',
            'date_signature_avenant' => 'nullable|date',
            'Superficie_Contrat_avenant' => 'nullable|numeric|min:0',
            'Date_PV_etat_des_lieux' => 'nullable|date',
            'Superficie_ha' => 'nullable|numeric|min:0',
            'Taux_de_réussite' => 'nullable|numeric|min:0|max:100',
            'Etat_de_la_clôture' => 'nullable|string|max:255',
            'PV_évaluation' => 'nullable|string',
            'observations' => 'nullable|string',
            'Etat_peuplement' => 'nullable|string|max:255',
            'Contraintes' => 'nullable|string',
        ]);

        Partenariat::create($validated);

        return redirect()->route('partenariats.index')
            ->with('success', 'Partenariat créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Partenariat $partenariat): View
    {
        $partenariat->load(['localisation', 'essence', 'suiviContractProgrammes.foret', 'suiviContractProgrammes.localisation']);
        return view('partenariats.show', compact('partenariat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partenariat $partenariat): View
    {
        $localisations = Localisation::orderBy('CODE')->get();
        $essences = Essence::orderBy('essence')->get();
        return view('partenariats.edit', compact('partenariat', 'localisations', 'essences'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partenariat $partenariat): RedirectResponse
    {
        $validated = $request->validate([
            'localisation_id' => 'nullable|exists:localisations,id',
            'nom_association' => 'nullable|string|max:255',
            'nombre_adherents_association' => 'nullable|integer|min:0',
            'date_creation_association' => 'nullable|date',
            'superficie' => 'nullable|numeric|min:0',
            'nom_périmètre' => 'nullable|string|max:255',
            'essence_id' => 'nullable|exists:essences,id',
            'object_cmd' => 'nullable|string',
            'num_contract' => 'nullable|string|max:255',
            'date_signature_contract' => 'nullable|date',
            'num_avenant' => 'nullable|string|max:255',
            'nombre_avenant' => 'nullable|integer|min:0',
            'date_signature_avenant' => 'nullable|date',
            'Superficie_Contrat_avenant' => 'nullable|numeric|min:0',
            'Date_PV_etat_des_lieux' => 'nullable|date',
            'Superficie_ha' => 'nullable|numeric|min:0',
            'Taux_de_réussite' => 'nullable|numeric|min:0|max:100',
            'Etat_de_la_clôture' => 'nullable|string|max:255',
            'PV_évaluation' => 'nullable|string',
            'observations' => 'nullable|string',
            'Etat_peuplement' => 'nullable|string|max:255',
            'Contraintes' => 'nullable|string',
        ]);

        $partenariat->update($validated);

        return redirect()->route('partenariats.index')
            ->with('success', 'Partenariat mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partenariat $partenariat): RedirectResponse
    {
        $partenariat->delete();

        return redirect()->route('partenariats.index')
            ->with('success', 'Partenariat supprimé avec succès.');
    }
}
