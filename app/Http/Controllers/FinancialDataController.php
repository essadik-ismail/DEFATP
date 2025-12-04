<?php

namespace App\Http\Controllers;

use App\Models\ProvinceAnnualShare;
use App\Models\RegionalBudget;
use App\Models\MonthlyRevenue;
use App\Models\NationalSummary;
use App\Models\SituationAdministrative;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FinancialDataController extends Controller
{
    /**
     * Display the financial data management page with tabs
     */
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'province-annual-shares');

        // Province Annual Shares
        $provinceAnnualShares = ProvinceAnnualShare::with('situationAdministrative')
            ->when($request->filled('province_search'), function($query) use ($request) {
                $query->where('year', 'like', '%' . $request->province_search . '%')
                      ->orWhereHas('situationAdministrative', function($q) use ($request) {
                          $q->where('province', 'like', '%' . $request->province_search . '%');
                      });
            })
            ->orderBy('year', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'province_page');

        // Regional Budgets
        $regionalBudgets = RegionalBudget::with('situationAdministrative')
            ->when($request->filled('regional_search'), function($query) use ($request) {
                $query->where('year', 'like', '%' . $request->regional_search . '%')
                      ->orWhereHas('situationAdministrative', function($q) use ($request) {
                          $q->where('region', 'like', '%' . $request->regional_search . '%');
                      });
            })
            ->orderBy('year', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'regional_page');

        // Monthly Revenues
        $monthlyRevenues = MonthlyRevenue::with('situationAdministrative')
            ->when($request->filled('monthly_search'), function($query) use ($request) {
                $query->where('year', 'like', '%' . $request->monthly_search . '%')
                      ->orWhere('month', 'like', '%' . $request->monthly_search . '%')
                      ->orWhereHas('situationAdministrative', function($q) use ($request) {
                          $q->where('region', 'like', '%' . $request->monthly_search . '%');
                      });
            })
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'monthly_page');

        // National Summaries
        $nationalSummaries = NationalSummary::query()
            ->when($request->filled('national_search'), function($query) use ($request) {
                $query->where('year', 'like', '%' . $request->national_search . '%');
            })
            ->orderBy('year', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'national_page');

        $situationsAdministratives = SituationAdministrative::orderBy('province')->get();

        return view('financial-data.index', compact(
            'provinceAnnualShares',
            'regionalBudgets',
            'monthlyRevenues',
            'nationalSummaries',
            'situationsAdministratives',
            'tab'
        ));
    }

    // ==================== Province Annual Shares ====================

    public function createProvinceAnnualShare(): View
    {
        $situationsAdministratives = SituationAdministrative::orderBy('province')->get();
        return view('financial-data.province-annual-shares.create', compact('situationsAdministratives'));
    }

    public function storeProvinceAnnualShare(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
            'year' => 'required|integer|min:1900|max:2100',
            'llege' => 'nullable|numeric|min:0',
            'bols_charbon_tanin' => 'nullable|numeric|min:0',
            'alfa' => 'nullable|numeric|min:0',
            'produits_divers' => 'nullable|numeric|min:0',
            'interets_retard' => 'nullable|numeric|min:0',
            'total_province' => 'nullable|numeric|min:0',
        ]);

        ProvinceAnnualShare::create($validated);

        return redirect()->route('financial-data.index', ['tab' => 'province-annual-shares'])
            ->with('success', 'Part annuelle de province créée avec succès.');
    }

    public function editProvinceAnnualShare(ProvinceAnnualShare $provinceAnnualShare): View
    {
        $situationsAdministratives = SituationAdministrative::orderBy('province')->get();
        return view('financial-data.province-annual-shares.edit', compact('provinceAnnualShare', 'situationsAdministratives'));
    }

    public function updateProvinceAnnualShare(Request $request, ProvinceAnnualShare $provinceAnnualShare): RedirectResponse
    {
        $validated = $request->validate([
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
            'year' => 'required|integer|min:1900|max:2100',
            'llege' => 'nullable|numeric|min:0',
            'bols_charbon_tanin' => 'nullable|numeric|min:0',
            'alfa' => 'nullable|numeric|min:0',
            'produits_divers' => 'nullable|numeric|min:0',
            'interets_retard' => 'nullable|numeric|min:0',
            'total_province' => 'nullable|numeric|min:0',
        ]);

        $provinceAnnualShare->update($validated);

        return redirect()->route('financial-data.index', ['tab' => 'province-annual-shares'])
            ->with('success', 'Part annuelle de province mise à jour avec succès.');
    }

    public function destroyProvinceAnnualShare(ProvinceAnnualShare $provinceAnnualShare): RedirectResponse
    {
        $provinceAnnualShare->delete();

        return redirect()->route('financial-data.index', ['tab' => 'province-annual-shares'])
            ->with('success', 'Part annuelle de province supprimée avec succès.');
    }

    // ==================== Regional Budgets ====================

    public function createRegionalBudget(): View
    {
        $situationsAdministratives = SituationAdministrative::orderBy('province')->get();
        return view('financial-data.regional-budgets.create', compact('situationsAdministratives'));
    }

    public function storeRegionalBudget(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
            'year' => 'required|integer|min:1900|max:2100',
            'taxe_adjudication_1_6' => 'nullable|numeric|min:0',
            'taxe_reconnaissance_interets' => 'nullable|numeric|min:0',
            'ta_saisie_caution' => 'nullable|numeric|min:0',
            'budget_fmf' => 'nullable|numeric|min:0',
            'remboursement_drs' => 'nullable|numeric|min:0',
            'remboursement_fmf_autres' => 'nullable|numeric|min:0',
            'taxe_fmf_20' => 'nullable|numeric|min:0',
            'taxe_mise_en_charge' => 'nullable|numeric|min:0',
            'chasse_peche' => 'nullable|numeric|min:0',
            'taxe_12_bois_importes' => 'nullable|numeric|min:0',
        ]);

        RegionalBudget::create($validated);

        return redirect()->route('financial-data.index', ['tab' => 'regional-budgets'])
            ->with('success', 'Budget régional créé avec succès.');
    }

    public function editRegionalBudget(RegionalBudget $regionalBudget): View
    {
        $situationsAdministratives = SituationAdministrative::orderBy('province')->get();
        return view('financial-data.regional-budgets.edit', compact('regionalBudget', 'situationsAdministratives'));
    }

    public function updateRegionalBudget(Request $request, RegionalBudget $regionalBudget): RedirectResponse
    {
        $validated = $request->validate([
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
            'year' => 'required|integer|min:1900|max:2100',
            'taxe_adjudication_1_6' => 'nullable|numeric|min:0',
            'taxe_reconnaissance_interets' => 'nullable|numeric|min:0',
            'ta_saisie_caution' => 'nullable|numeric|min:0',
            'budget_fmf' => 'nullable|numeric|min:0',
            'remboursement_drs' => 'nullable|numeric|min:0',
            'remboursement_fmf_autres' => 'nullable|numeric|min:0',
            'taxe_fmf_20' => 'nullable|numeric|min:0',
            'taxe_mise_en_charge' => 'nullable|numeric|min:0',
            'chasse_peche' => 'nullable|numeric|min:0',
            'taxe_12_bois_importes' => 'nullable|numeric|min:0',
        ]);

        $regionalBudget->update($validated);

        return redirect()->route('financial-data.index', ['tab' => 'regional-budgets'])
            ->with('success', 'Budget régional mis à jour avec succès.');
    }

    public function destroyRegionalBudget(RegionalBudget $regionalBudget): RedirectResponse
    {
        $regionalBudget->delete();

        return redirect()->route('financial-data.index', ['tab' => 'regional-budgets'])
            ->with('success', 'Budget régional supprimé avec succès.');
    }

    // ==================== Monthly Revenues ====================

    public function createMonthlyRevenue(): View
    {
        $situationsAdministratives = SituationAdministrative::orderBy('province')->get();
        return view('financial-data.monthly-revenues.create', compact('situationsAdministratives'));
    }

    public function storeMonthlyRevenue(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
            'year' => 'required|integer|min:1900|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'llege' => 'nullable|numeric|min:0',
            'bols_charbon_tanin' => 'nullable|numeric|min:0',
            'alfa' => 'nullable|numeric|min:0',
            'produits_divers' => 'nullable|numeric|min:0',
            'interets_retard' => 'nullable|numeric|min:0',
            'total_part_province' => 'nullable|numeric|min:0',
        ]);

        MonthlyRevenue::create($validated);

        return redirect()->route('financial-data.index', ['tab' => 'monthly-revenues'])
            ->with('success', 'Revenu mensuel créé avec succès.');
    }

    public function editMonthlyRevenue(MonthlyRevenue $monthlyRevenue): View
    {
        $situationsAdministratives = SituationAdministrative::orderBy('province')->get();
        return view('financial-data.monthly-revenues.edit', compact('monthlyRevenue', 'situationsAdministratives'));
    }

    public function updateMonthlyRevenue(Request $request, MonthlyRevenue $monthlyRevenue): RedirectResponse
    {
        $validated = $request->validate([
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
            'year' => 'required|integer|min:1900|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'llege' => 'nullable|numeric|min:0',
            'bols_charbon_tanin' => 'nullable|numeric|min:0',
            'alfa' => 'nullable|numeric|min:0',
            'produits_divers' => 'nullable|numeric|min:0',
            'interets_retard' => 'nullable|numeric|min:0',
            'total_part_province' => 'nullable|numeric|min:0',
        ]);

        $monthlyRevenue->update($validated);

        return redirect()->route('financial-data.index', ['tab' => 'monthly-revenues'])
            ->with('success', 'Revenu mensuel mis à jour avec succès.');
    }

    public function destroyMonthlyRevenue(MonthlyRevenue $monthlyRevenue): RedirectResponse
    {
        $monthlyRevenue->delete();

        return redirect()->route('financial-data.index', ['tab' => 'monthly-revenues'])
            ->with('success', 'Revenu mensuel supprimé avec succès.');
    }

    // ==================== National Summaries ====================

    public function createNationalSummary(): View
    {
        return view('financial-data.national-summaries.create');
    }

    public function storeNationalSummary(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:2100|unique:national_summaries,year',
            'budget_general_frais_adjudication' => 'nullable|numeric|min:0',
            'budget_general_ta' => 'nullable|numeric|min:0',
            'budget_general_taxe_reconnaissance' => 'nullable|numeric|min:0',
            'budget_general_total' => 'nullable|numeric|min:0',
            'part_etat' => 'nullable|numeric|min:0',
            'cas_fmf_total' => 'nullable|numeric|min:0',
            'cas_chasse_peche_total' => 'nullable|numeric|min:0',
            'communes_bois_tanin' => 'nullable|numeric|min:0',
            'communes_liege' => 'nullable|numeric|min:0',
            'communes_pam_produits_divers' => 'nullable|numeric|min:0',
            'communes_redevances_parcours' => 'nullable|numeric|min:0',
            'communes_occupations_temporaires' => 'nullable|numeric|min:0',
            'communes_autres_taxes' => 'nullable|numeric|min:0',
            'communes_total' => 'nullable|numeric|min:0',
            'provinces_bois_tanin' => 'nullable|numeric|min:0',
            'provinces_liege' => 'nullable|numeric|min:0',
            'provinces_pam_produits_divers' => 'nullable|numeric|min:0',
            'provinces_interets_retard' => 'nullable|numeric|min:0',
            'provinces_total' => 'nullable|numeric|min:0',
            'total_general' => 'nullable|numeric|min:0',
        ]);

        NationalSummary::create($validated);

        return redirect()->route('financial-data.index', ['tab' => 'national-summaries'])
            ->with('success', 'Résumé national créé avec succès.');
    }

    public function editNationalSummary(NationalSummary $nationalSummary): View
    {
        return view('financial-data.national-summaries.edit', compact('nationalSummary'));
    }

    public function updateNationalSummary(Request $request, NationalSummary $nationalSummary): RedirectResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:2100|unique:national_summaries,year,' . $nationalSummary->id,
            'budget_general_frais_adjudication' => 'nullable|numeric|min:0',
            'budget_general_ta' => 'nullable|numeric|min:0',
            'budget_general_taxe_reconnaissance' => 'nullable|numeric|min:0',
            'budget_general_total' => 'nullable|numeric|min:0',
            'part_etat' => 'nullable|numeric|min:0',
            'cas_fmf_total' => 'nullable|numeric|min:0',
            'cas_chasse_peche_total' => 'nullable|numeric|min:0',
            'communes_bois_tanin' => 'nullable|numeric|min:0',
            'communes_liege' => 'nullable|numeric|min:0',
            'communes_pam_produits_divers' => 'nullable|numeric|min:0',
            'communes_redevances_parcours' => 'nullable|numeric|min:0',
            'communes_occupations_temporaires' => 'nullable|numeric|min:0',
            'communes_autres_taxes' => 'nullable|numeric|min:0',
            'communes_total' => 'nullable|numeric|min:0',
            'provinces_bois_tanin' => 'nullable|numeric|min:0',
            'provinces_liege' => 'nullable|numeric|min:0',
            'provinces_pam_produits_divers' => 'nullable|numeric|min:0',
            'provinces_interets_retard' => 'nullable|numeric|min:0',
            'provinces_total' => 'nullable|numeric|min:0',
            'total_general' => 'nullable|numeric|min:0',
        ]);

        $nationalSummary->update($validated);

        return redirect()->route('financial-data.index', ['tab' => 'national-summaries'])
            ->with('success', 'Résumé national mis à jour avec succès.');
    }

    public function destroyNationalSummary(NationalSummary $nationalSummary): RedirectResponse
    {
        $nationalSummary->delete();

        return redirect()->route('financial-data.index', ['tab' => 'national-summaries'])
            ->with('success', 'Résumé national supprimé avec succès.');
    }
}

