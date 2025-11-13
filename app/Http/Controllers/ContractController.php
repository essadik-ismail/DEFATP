<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Localisation;
use App\Models\SituationAdministrative;
use App\Models\Espece;
use App\Models\Exploitant;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function index(Request $request): View
    {
        // Log view action
        ActivityLogger::log('view', 'Consultation de la liste des contrats', Contract::class);
        
        // Get contracts with relationships
        $contracts = Contract::with(['localisation', 'situationAdministrative', 'especes'])
            ->when($request->filled('search'), function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('contarct', 'like', '%' . $request->search . '%')
                      ->orWhere('annee', 'like', '%' . $request->search . '%')
                      ->orWhereHas('localisation', function($locQuery) use ($request) {
                          $locQuery->where('CODE', 'like', '%' . $request->search . '%')
                                   ->orWhere('DRANEF', 'like', '%' . $request->search . '%');
                      })
                      ->orWhereHas('situationAdministrative', function($sitQuery) use ($request) {
                          $sitQuery->where('commune', 'like', '%' . $request->search . '%')
                                   ->orWhere('province', 'like', '%' . $request->search . '%');
                      })
                      ->orWhereHas('especes', function($espQuery) use ($request) {
                          $espQuery->where('name', 'like', '%' . $request->search . '%');
                      });
                });
            })
            ->when($request->filled('year'), function($query) use ($request) {
                $query->where('annee', $request->year);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        // Provide especes list for the especes table section
        $especes = Espece::when($request->filled('espece_search'), function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->espece_search . '%');
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'especes_page');

        // Provide avenants list for the avenants table section
        $avenants = \App\Models\Avenant::with(['coperative'])
            ->when($request->filled('avenant_search'), function($query) use ($request) {
                $query->where('annee', 'like', '%' . $request->avenant_search . '%')
                      ->orWhereHas('coperative', function($coopQuery) use ($request) {
                          $coopQuery->where('nom', 'like', '%' . $request->avenant_search . '%');
                      });
            })
            ->orderBy('date', 'desc')
            ->paginate(10, ['*'], 'avenants_page');

        // Provide coperatives list for the coperatives table section
        $coperatives = \App\Models\Coperative::when($request->filled('coperative_search'), function($query) use ($request) {
                $query->where('nom', 'like', '%' . $request->coperative_search . '%');
            })
            ->orderBy('nom')
            ->paginate(10, ['*'], 'coperatives_page');

        return view('contracts.index', compact('contracts', 'especes', 'avenants', 'coperatives'));
    }

    public function create(): View
    {
        $localisations = Localisation::orderBy('CODE')->get();
        $situationAdministratives = SituationAdministrative::orderBy('commune')->get();
        $especes = Espece::orderBy('name')->get();

        return view('contracts.create', compact(
            'localisations',
            'situationAdministratives',
            'especes'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'annee' => 'required|integer',
            'contarct' => 'required|string|max:255',
            'localisation_id' => 'required|exists:localisations,id',
            'situation_administrative_id' => 'required|exists:situation_administratives,id',
            'especes' => 'required|array|min:1',
            'especes.*' => 'exists:especes,id',
            'superficie' => 'nullable|string',
            'gardiennage' => 'nullable|string',
            'elagage' => 'nullable|string',
            'eclaircie' => 'nullable|string',
            'rajeunissement_romarin' => 'nullable|string',
            'valeurs_des_produits' => 'nullable|string',
            'valeur_des_prestations' => 'nullable|string',
            'redevances' => 'nullable|string',
            'taxes' => 'nullable|string',
            'total_avenant' => 'nullable|string',
            'bo_m3' => 'nullable|integer',
            'bi_m3' => 'nullable|integer',
            'bf_st' => 'nullable|integer',
            'tanin_t' => 'nullable|integer',
            'fleur_acacia_t' => 'nullable|integer',
            'caroube_t' => 'nullable|integer',
            'romarin_t' => 'nullable|integer',
            'ps_t' => 'nullable|integer',
            'liége_st' => 'nullable|integer',
            'charbon_bois_ox' => 'nullable|integer',
            'attribute13' => 'nullable|string',
            'attribute14' => 'nullable|string',
            'attribute15' => 'nullable|string',
            'attribute16' => 'nullable|string',
            'attribute17' => 'nullable|string',
        ]);

        try {
            $especes = $validated['especes'];
            unset($validated['especes']);
            
            $contract = Contract::create($validated);
            
            // Attach especes to the contract
            $contract->especes()->attach($especes);

            ActivityLogger::logCreate(
                Contract::class,
                $contract->id,
                "Contrat {$contract->contarct} ({$contract->annee})",
                $request
            );

            return redirect()->route('contracts.index')
                ->with('success', 'Contrat créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du contrat: ' . $e->getMessage());
        }
    }

    public function show(Contract $contract): View
    {
        $contract->load(['localisation', 'situationAdministrative', 'especes']);
        
        // Load related avenants by year
        $avenants = \App\Models\Avenant::where('annee', $contract->annee)
            ->with(['coperative', 'produits'])
            ->orderBy('date', 'desc')
            ->get();
        
        ActivityLogger::log('view', "Consultation du contrat {$contract->contarct}", Contract::class);

        return view('contracts.show', compact('contract', 'avenants'));
    }

    public function edit(Contract $contract): View
    {
        $contract->load('especes');
        $localisations = Localisation::orderBy('CODE')->get();
        $situationAdministratives = SituationAdministrative::orderBy('commune')->get();
        $especes = Espece::orderBy('name')->get();

        return view('contracts.edit', compact(
            'contract',
            'localisations',
            'situationAdministratives',
            'especes'
        ));
    }

    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $validated = $request->validate([
            'annee' => 'required|integer',
            'contarct' => 'required|string|max:255',
            'localisation_id' => 'required|exists:localisations,id',
            'situation_administrative_id' => 'required|exists:situation_administratives,id',
            'especes' => 'required|array|min:1',
            'especes.*' => 'exists:especes,id',
            'superficie' => 'nullable|string',
            'gardiennage' => 'nullable|string',
            'elagage' => 'nullable|string',
            'eclaircie' => 'nullable|string',
            'rajeunissement_romarin' => 'nullable|string',
            'valeurs_des_produits' => 'nullable|string',
            'valeur_des_prestations' => 'nullable|string',
            'redevances' => 'nullable|string',
            'taxes' => 'nullable|string',
            'total_avenant' => 'nullable|string',
            'bo_m3' => 'nullable|integer',
            'bi_m3' => 'nullable|integer',
            'bf_st' => 'nullable|integer',
            'tanin_t' => 'nullable|integer',
            'fleur_acacia_t' => 'nullable|integer',
            'caroube_t' => 'nullable|integer',
            'romarin_t' => 'nullable|integer',
            'ps_t' => 'nullable|integer',
            'liége_st' => 'nullable|integer',
            'charbon_bois_ox' => 'nullable|integer',
            'attribute13' => 'nullable|string',
            'attribute14' => 'nullable|string',
            'attribute15' => 'nullable|string',
            'attribute16' => 'nullable|string',
            'attribute17' => 'nullable|string',
        ]);

        try {
            $especes = $validated['especes'];
            unset($validated['especes']);
            
            $contract->update($validated);
            
            // Sync especes to the contract (replace existing with new ones)
            $contract->especes()->sync($especes);

            ActivityLogger::logUpdate(
                Contract::class,
                $contract->id,
                "Contrat {$contract->contarct} ({$contract->annee})",
                [],
                $request
            );

            return redirect()->route('contracts.index')
                ->with('success', 'Contrat mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du contrat: ' . $e->getMessage());
        }
    }

    public function destroy(Contract $contract): RedirectResponse
    {
        try {
            $contractNumber = $contract->contarct;
            $contractYear = $contract->annee;
            
            $contract->delete();

            ActivityLogger::logDelete(
                Contract::class,
                $contract->id,
                "Contrat {$contractNumber} ({$contractYear})"
            );

            return redirect()->route('contracts.index')
                ->with('success', 'Contrat supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du contrat: ' . $e->getMessage());
        }
    }

    // Espece Management Methods
    public function createEspece(): View
    {
        return view('contracts.especes.create');
    }

    public function storeEspece(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:especes,name,NULL,id,deleted_at,NULL',
        ]);

        try {
            $espece = Espece::create($validated);

            ActivityLogger::logCreate(
                Espece::class,
                $espece->id,
                "Espèce {$espece->name}",
                $request
            );

            return redirect()->route('contracts.index', ['tab' => 'especes'])
                ->with('success', 'Espèce créée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'espèce: ' . $e->getMessage());
        }
    }

    // Avenant Management Methods
    public function createAvenant(): View
    {
        $exploitants = Exploitant::orderBy('nom_complet')->get();
        return view('contracts.avenants.create', compact('exploitants'));
    }

    public function storeAvenant(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'annee' => 'required|integer',
            'coperative_id' => 'nullable|exists:exploitants,id',
            'date' => 'required|date',
            'superficie' => 'nullable|numeric',
            'gardiennage' => 'nullable|numeric',
            'prevention_incendies' => 'nullable|numeric',
            'elagage' => 'nullable|numeric',
            'eclaircie' => 'nullable|numeric',
            'rajeunissement_romarin' => 'nullable|numeric',
            'valeurs_des_produits' => 'nullable|numeric',
            'valeur_des_prestations' => 'nullable|numeric',
            'redevances' => 'nullable|numeric',
            'taxes' => 'nullable|numeric',
            'total_avenant' => 'nullable|numeric',
        ]);

        try {
            $avenant = \App\Models\Avenant::create($validated);

            ActivityLogger::logCreate(
                \App\Models\Avenant::class,
                $avenant->id,
                "Avenant #{$avenant->id} ({$avenant->annee})",
                $request
            );

            return redirect()->route('contracts.index', ['tab' => 'avenants'])
                ->with('success', 'Avenant créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'avenant: ' . $e->getMessage());
        }
    }
}
