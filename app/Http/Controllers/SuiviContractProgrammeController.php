<?php

namespace App\Http\Controllers;

use App\Models\SuiviContractProgramme;
use App\Models\Partenariat;
use App\Models\Localisation;
use App\Models\Foret;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SuiviContractProgrammeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $partenariatId = $request->get('partenariat_id');
        $partenariat = $partenariatId ? Partenariat::findOrFail($partenariatId) : null;
        
        $localisations = Localisation::orderBy('CODE')->get();
        $forets = Foret::orderBy('foret')->get();
        $partenariats = Partenariat::orderBy('nom_association')->get();
        
        return view('suivi-contract-programmes.create', compact('localisations', 'forets', 'partenariats', 'partenariat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'partenariat_id' => 'required|exists:partenariats,id',
            'localisation_id' => 'nullable|exists:localisations,id',
            'foret_id' => 'nullable|exists:forets,id',
            'CT' => 'nullable|string|max:255',
            'DPF' => 'nullable|string|max:255',
            'Parcelle' => 'nullable|string|max:255',
            'Projet_CP' => 'nullable|string|max:255',
            'Année' => 'nullable|integer|min:1900|max:2100',
            'Superficie_prévue_CP_ha' => 'nullable|numeric|min:0',
            'Montant_prévu_CP_dh' => 'nullable|numeric|min:0',
            'Superficie_engagée_ha' => 'nullable|numeric|min:0',
            'Montant_engagé_dh' => 'nullable|numeric|min:0',
            'Superficie_payée_ha' => 'nullable|numeric|min:0',
            'Montant_payé_dh' => 'nullable|numeric|min:0',
            'Superficie_non_payée' => 'nullable|numeric|min:0',
            'Motif_du_Non_paiement' => 'nullable|string',
        ]);

        SuiviContractProgramme::create($validated);

        return redirect()->route('partenariats.show', $validated['partenariat_id'])
            ->with('success', 'Suivi Contract Programme créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SuiviContractProgramme $suiviContractProgramme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuiviContractProgramme $suiviContractProgramme): View
    {
        $localisations = Localisation::orderBy('CODE')->get();
        $forets = Foret::orderBy('foret')->get();
        $partenariats = Partenariat::orderBy('nom_association')->get();
        
        return view('suivi-contract-programmes.edit', compact('suiviContractProgramme', 'localisations', 'forets', 'partenariats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuiviContractProgramme $suiviContractProgramme): RedirectResponse
    {
        $validated = $request->validate([
            'partenariat_id' => 'required|exists:partenariats,id',
            'localisation_id' => 'nullable|exists:localisations,id',
            'foret_id' => 'nullable|exists:forets,id',
            'CT' => 'nullable|string|max:255',
            'DPF' => 'nullable|string|max:255',
            'Parcelle' => 'nullable|string|max:255',
            'Projet_CP' => 'nullable|string|max:255',
            'Année' => 'nullable|integer|min:1900|max:2100',
            'Superficie_prévue_CP_ha' => 'nullable|numeric|min:0',
            'Montant_prévu_CP_dh' => 'nullable|numeric|min:0',
            'Superficie_engagée_ha' => 'nullable|numeric|min:0',
            'Montant_engagé_dh' => 'nullable|numeric|min:0',
            'Superficie_payée_ha' => 'nullable|numeric|min:0',
            'Montant_payé_dh' => 'nullable|numeric|min:0',
            'Superficie_non_payée' => 'nullable|numeric|min:0',
            'Motif_du_Non_paiement' => 'nullable|string',
        ]);

        $suiviContractProgramme->update($validated);

        return redirect()->route('partenariats.show', $validated['partenariat_id'])
            ->with('success', 'Suivi Contract Programme mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuiviContractProgramme $suiviContractProgramme): RedirectResponse
    {
        $partenariatId = $suiviContractProgramme->partenariat_id;
        $suiviContractProgramme->delete();

        return redirect()->route('partenariats.show', $partenariatId)
            ->with('success', 'Suivi Contract Programme supprimé avec succès.');
    }
}
