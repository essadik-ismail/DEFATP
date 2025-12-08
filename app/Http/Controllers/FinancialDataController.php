<?php

namespace App\Http\Controllers;

use App\Models\NationalSummary;
use App\Models\SituationAdministrative;
use App\Models\Localisation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FinancialDataController extends Controller
{
    /**
     * Display the financial data management page
     */
    public function index(Request $request): View
    {
        $nationalSummaries = NationalSummary::with(['situationAdministrative', 'localisation'])
            ->when($request->filled('search'), function($query) use ($request) {
                $search = $request->search;
                $query->where('year', 'like', '%' . $search . '%')
                      ->orWhere('month', 'like', '%' . $search . '%')
                      ->orWhereHas('situationAdministrative', function($q) use ($search) {
                          $q->where('commune', 'like', '%' . $search . '%')
                            ->orWhere('province', 'like', '%' . $search . '%')
                            ->orWhere('region', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('localisation', function($q) use ($search) {
                          $q->where('CODE', 'like', '%' . $search . '%')
                            ->orWhere('DRANEF', 'like', '%' . $search . '%')
                            ->orWhere('DPANEF', 'like', '%' . $search . '%')
                            ->orWhere('ENTITE', 'like', '%' . $search . '%');
                      });
            })
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(25);

        $situationsAdministratives = SituationAdministrative::orderBy('province')->get();
        $localisations = Localisation::orderBy('CODE')->get();

        return view('financial-data.index', compact(
            'nationalSummaries',
            'situationsAdministratives',
            'localisations'
        ));
    }

    /**
     * Show the form for creating a new national summary.
     */
    public function create(): View
    {
        $situationsAdministratives = SituationAdministrative::orderBy('province')->get();
        $localisations = Localisation::orderBy('CODE')->get();
        return view('financial-data.create', compact('situationsAdministratives', 'localisations'));
    }

    /**
     * Store a newly created national summary.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
            'budget_general_frais_adjudication' => 'nullable|numeric|min:0',
            'budget_general_taj' => 'nullable|numeric|min:0',
            'budget_general_taxe_reconnaissance' => 'nullable|numeric|min:0',
            'budget_general_total' => 'nullable|numeric|min:0',
            'part_etat' => 'nullable|numeric|min:0',
            'cas_fnf_total' => 'nullable|numeric|min:0',
            'cas_chasse_peche_total' => 'nullable|numeric|min:0',
            'communes_bois_tanin' => 'nullable|numeric|min:0',
            'communes_liege' => 'nullable|numeric|min:0',
            'communes_pam_produits_divers' => 'nullable|numeric|min:0',
            'communes_redevances_parcours' => 'nullable|numeric|min:0',
            'communes_occupations_temporaires' => 'nullable|numeric|min:0',
            'communes_autres_taxes' => 'nullable|numeric|min:0',
            'communes_total' => 'nullable|numeric|min:0',
            'provinces_liege' => 'nullable|numeric|min:0',
            'provinces_bois_tanin' => 'nullable|numeric|min:0',
            'provinces_Alfa' => 'nullable|numeric|min:0',
            'provinces_pam_produits_divers' => 'nullable|numeric|min:0',
            'provinces_interets_retard' => 'nullable|numeric|min:0',
            'provinces_total' => 'nullable|numeric|min:0',
            'total_general' => 'nullable|numeric|min:0',
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
            'localisation_id' => 'nullable|exists:localisations,id',
        ]);

        NationalSummary::create($validated);

        return redirect()->route('financial-data.index')
            ->with('success', 'Résumé national créé avec succès.');
    }

    /**
     * Show the form for editing the specified national summary.
     */
    public function edit(NationalSummary $nationalSummary): View
    {
        $situationsAdministratives = SituationAdministrative::orderBy('province')->get();
        $localisations = Localisation::orderBy('CODE')->get();
        return view('financial-data.edit', compact('nationalSummary', 'situationsAdministratives', 'localisations'));
    }

    /**
     * Update the specified national summary.
     */
    public function update(Request $request, NationalSummary $nationalSummary): RedirectResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
            'budget_general_frais_adjudication' => 'nullable|numeric|min:0',
            'budget_general_taj' => 'nullable|numeric|min:0',
            'budget_general_taxe_reconnaissance' => 'nullable|numeric|min:0',
            'budget_general_total' => 'nullable|numeric|min:0',
            'part_etat' => 'nullable|numeric|min:0',
            'cas_fnf_total' => 'nullable|numeric|min:0',
            'cas_chasse_peche_total' => 'nullable|numeric|min:0',
            'communes_bois_tanin' => 'nullable|numeric|min:0',
            'communes_liege' => 'nullable|numeric|min:0',
            'communes_pam_produits_divers' => 'nullable|numeric|min:0',
            'communes_redevances_parcours' => 'nullable|numeric|min:0',
            'communes_occupations_temporaires' => 'nullable|numeric|min:0',
            'communes_autres_taxes' => 'nullable|numeric|min:0',
            'communes_total' => 'nullable|numeric|min:0',
            'provinces_liege' => 'nullable|numeric|min:0',
            'provinces_bois_tanin' => 'nullable|numeric|min:0',
            'provinces_Alfa' => 'nullable|numeric|min:0',
            'provinces_pam_produits_divers' => 'nullable|numeric|min:0',
            'provinces_interets_retard' => 'nullable|numeric|min:0',
            'provinces_total' => 'nullable|numeric|min:0',
            'total_general' => 'nullable|numeric|min:0',
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
            'localisation_id' => 'nullable|exists:localisations,id',
        ]);

        $nationalSummary->update($validated);

        return redirect()->route('financial-data.index')
            ->with('success', 'Résumé national mis à jour avec succès.');
    }

    /**
     * Remove the specified national summary.
     */
    public function destroy(NationalSummary $nationalSummary): RedirectResponse
    {
        $nationalSummary->delete();

        return redirect()->route('financial-data.index')
            ->with('success', 'Résumé national supprimé avec succès.');
    }
}
