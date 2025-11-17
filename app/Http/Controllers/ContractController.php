<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Localisation;
use App\Models\SituationAdministrative;
use App\Models\Espece;
use App\Models\Coperative;
use App\Models\Product;
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
        $contracts = Contract::with(['localisation', 'situationAdministrative', 'especes', 'forets', 'coperative'])
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
                      })
                      ->orWhereHas('forets', function($foretQuery) use ($request) {
                          $foretQuery->where('foret', 'like', '%' . $request->search . '%');
                      })
                      ->orWhereHas('coperative', function($coopQuery) use ($request) {
                          $coopQuery->where('nom', 'like', '%' . $request->search . '%');
                      });
                });
            })
            ->when($request->filled('year'), function($query) use ($request) {
                $query->where('annee', $request->year);
            })
            ->when($request->filled('localisation_id'), function($query) use ($request) {
                $query->where('localisation_id', $request->localisation_id);
            })
            ->when($request->filled('situation_administrative_id'), function($query) use ($request) {
                $query->where('situation_administrative_id', $request->situation_administrative_id);
            })
            ->when($request->filled('espece_id'), function($query) use ($request) {
                $query->whereHas('especes', function($q) use ($request) {
                    $q->where('especes.id', $request->espece_id);
                });
            })
            ->when($request->filled('foret_id'), function($query) use ($request) {
                $query->whereHas('forets', function($q) use ($request) {
                    $q->where('forets.id', $request->foret_id);
                });
            })
            ->when($request->filled('coperative_id'), function($query) use ($request) {
                $query->where('coperative_id', $request->coperative_id);
            })
            ->when($request->filled('start_date'), function($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end_date);
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
        $avenants = \App\Models\Avenant::with(['coperative', 'contract'])
            ->when($request->filled('avenant_search'), function($query) use ($request) {
                $query->where('annee', 'like', '%' . $request->avenant_search . '%')
                      ->orWhereHas('coperative', function($coopQuery) use ($request) {
                          $coopQuery->where('nom', 'like', '%' . $request->avenant_search . '%');
                      })
                      ->orWhereHas('contract', function($contractQuery) use ($request) {
                          $contractQuery->where('contarct', 'like', '%' . $request->avenant_search . '%');
                      });
            })
            ->orderBy('date', 'desc')
            ->paginate(10, ['*'], 'avenants_page');

        // Provide coperatives list for the coperatives table section
        $coperatives = \App\Models\Coperative::with('vocation')
            ->when($request->filled('coperative_search'), function($query) use ($request) {
                $query->where('nom', 'like', '%' . $request->coperative_search . '%');
            })
            ->orderBy('nom')
            ->paginate(10, ['*'], 'coperatives_page');

        // Get filter options
        $localisations = Localisation::orderBy('CODE')->get();
        $situations = SituationAdministrative::orderBy('commune')->get();
        $especesList = Espece::orderBy('name')->get();
        $forets = \App\Models\Foret::orderBy('foret')->get();
        $coperativesList = \App\Models\Coperative::orderBy('nom')->get();
        
        // Get available years for filter
        $availableYears = Contract::select('annee')
            ->distinct()
            ->whereNotNull('annee')
            ->orderBy('annee', 'desc')
            ->pluck('annee');

        return view('contracts.index', compact('contracts', 'especes', 'avenants', 'coperatives', 'localisations', 'situations', 'especesList', 'forets', 'coperativesList', 'availableYears'));
    }

    public function create(): View
    {
        $localisations = Localisation::orderBy('CODE')->get();
        $situationAdministratives = SituationAdministrative::orderBy('commune')->get();
        $especes = Espece::orderBy('name')->get();
        $forets = \App\Models\Foret::orderBy('foret')->get();
        $coperatives = Coperative::orderBy('nom')->get();

        return view('contracts.create', compact(
            'localisations',
            'situationAdministratives',
            'especes',
            'forets',
            'coperatives'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'annee' => 'required|integer',
            'contarct' => 'required|integer',
            'localisation_id' => 'required|exists:localisations,id',
            'situation_administrative_id' => 'required|exists:situation_administratives,id',
            'forets' => 'required|array|min:1',
            'forets.*' => 'exists:forets,id',
            'coperative_id' => 'required|exists:coperatives,id',
            'especes' => 'required|array|min:1',
            'especes.*' => 'exists:especes,id',
            'superficie' => 'required|numeric|min:0',
            'gardiennage' => 'nullable|string|max:255',
            'prevention_contre_les_incendies' => 'nullable|string|max:255',
            'elagage' => 'nullable|string|max:255',
            'eclaircie' => 'nullable|string|max:255',
            'rajeunissement_romarin' => 'nullable|string|max:255',
            'bo_m3' => 'nullable|integer|min:0',
            'bi_m3' => 'nullable|integer|min:0',
            'bf_st' => 'nullable|integer|min:0',
            'tanin_t' => 'nullable|integer|min:0',
            'laurier_sauce' => 'nullable|integer|min:0',
            'myrte' => 'nullable|integer|min:0',
            'callune' => 'nullable|integer|min:0',
            'thym' => 'nullable|integer|min:0',
            'bruyetre' => 'nullable|integer|min:0',
            'lichen' => 'nullable|integer|min:0',
            'tanin' => 'nullable|integer|min:0',
            'romarin' => 'nullable|integer|min:0',
            'liege_male' => 'nullable|integer|min:0',
            'liege_de_reproduction' => 'nullable|integer|min:0',
            'sauge' => 'nullable|integer|min:0',
            'lavande' => 'nullable|integer|min:0',
            'armoise' => 'nullable|integer|min:0',
            'origan' => 'nullable|integer|min:0',
            'alfa' => 'nullable|integer|min:0',
            'lentisque' => 'nullable|integer|min:0',
            'ciste' => 'nullable|integer|min:0',
            'fleur_acacia_t' => 'nullable|integer|min:0',
            'valeurs_des_produits' => 'required|string|max:255',
            'valeur_des_prestations' => 'required|string|max:255',
            'redevances' => 'required|string|max:255',
            'taxes' => 'required|string|max:255',
            'total_avenant' => 'required|string|max:255',
            'resiliation' => 'nullable|boolean',
            'date_resiliation' => 'nullable|date',
        ]);

        try {
            $especes = $validated['especes'];
            unset($validated['especes']);
            
            $forets = $validated['forets'];
            unset($validated['forets']);
            
            $contract = Contract::create($validated);
            
            // Attach especes to the contract
            $contract->especes()->attach($especes);
            
            // Attach forets to the contract
            $contract->forets()->attach($forets);

            // Handle products if provided
            if ($request->has('products') && is_array($request->products)) {
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        $contract->products()->create([
                            'name' => $product['name'],
                            'quantity' => $product['quantity'] ?? 1,
                            'is_deleted' => false
                        ]);
                    }
                }
            }

            // Handle prestations if provided
            if ($request->has('prestations') && is_array($request->prestations)) {
                foreach ($request->prestations as $prestation) {
                    if (!empty($prestation['name'])) {
                        $contract->prestations()->create([
                            'name' => $prestation['name'],
                            'quantity' => $prestation['quantity'] ?? 1,
                        ]);
                    }
                }
            }

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
        $contract->load(['localisation', 'situationAdministrative', 'especes', 'forets', 'coperative', 'products', 'prestations']);
        
        // Load related avenants for this contract
        $avenants = \App\Models\Avenant::where('contact_id', $contract->id)
            ->with(['coperative', 'contract', 'products', 'prestations'])
            ->orderBy('date', 'desc')
            ->get();
        
        ActivityLogger::log('view', "Consultation du contrat {$contract->contarct}", Contract::class);

        return view('contracts.show', compact('contract', 'avenants'));
    }

    public function edit(Contract $contract): View
    {
        $contract->load(['especes', 'forets', 'products', 'prestations']);
        $localisations = Localisation::orderBy('CODE')->get();
        $situationAdministratives = SituationAdministrative::orderBy('commune')->get();
        $especes = Espece::orderBy('name')->get();
        $forets = \App\Models\Foret::orderBy('foret')->get();
        $coperatives = Coperative::orderBy('nom')->get();

        return view('contracts.edit', compact(
            'contract',
            'localisations',
            'situationAdministratives',
            'especes',
            'forets',
            'coperatives'
        ));
    }

    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $validated = $request->validate([
            'annee' => 'required|integer',
            'contarct' => 'required|integer',
            'localisation_id' => 'required|exists:localisations,id',
            'situation_administrative_id' => 'required|exists:situation_administratives,id',
            'forets' => 'required|array|min:1',
            'forets.*' => 'exists:forets,id',
            'coperative_id' => 'required|exists:coperatives,id',
            'especes' => 'required|array|min:1',
            'especes.*' => 'exists:especes,id',
            'superficie' => 'required|numeric|min:0',
            'gardiennage' => 'nullable|string|max:255',
            'prevention_contre_les_incendies' => 'nullable|string|max:255',
            'elagage' => 'nullable|string|max:255',
            'eclaircie' => 'nullable|string|max:255',
            'rajeunissement_romarin' => 'nullable|string|max:255',
            'bo_m3' => 'nullable|integer|min:0',
            'bi_m3' => 'nullable|integer|min:0',
            'bf_st' => 'nullable|integer|min:0',
            'tanin_t' => 'nullable|integer|min:0',
            'laurier_sauce' => 'nullable|integer|min:0',
            'myrte' => 'nullable|integer|min:0',
            'callune' => 'nullable|integer|min:0',
            'thym' => 'nullable|integer|min:0',
            'bruyetre' => 'nullable|integer|min:0',
            'lichen' => 'nullable|integer|min:0',
            'tanin' => 'nullable|integer|min:0',
            'romarin' => 'nullable|integer|min:0',
            'liege_male' => 'nullable|integer|min:0',
            'liege_de_reproduction' => 'nullable|integer|min:0',
            'sauge' => 'nullable|integer|min:0',
            'lavande' => 'nullable|integer|min:0',
            'armoise' => 'nullable|integer|min:0',
            'origan' => 'nullable|integer|min:0',
            'alfa' => 'nullable|integer|min:0',
            'lentisque' => 'nullable|integer|min:0',
            'ciste' => 'nullable|integer|min:0',
            'fleur_acacia_t' => 'nullable|integer|min:0',
            'valeurs_des_produits' => 'required|string|max:255',
            'valeur_des_prestations' => 'required|string|max:255',
            'redevances' => 'required|string|max:255',
            'taxes' => 'required|string|max:255',
            'total_avenant' => 'required|string|max:255',
            'resiliation' => 'nullable|boolean',
            'date_resiliation' => 'nullable|date',
        ]);

        try {
            $especes = $validated['especes'];
            unset($validated['especes']);
            
            $forets = $validated['forets'];
            unset($validated['forets']);
            
            $contract->update($validated);
            
            // Sync especes to the contract (replace existing with new ones)
            $contract->especes()->sync($especes);
            
            // Sync forets to the contract (replace existing with new ones)
            $contract->forets()->sync($forets);

            // Handle products update
            if ($request->has('products') && is_array($request->products)) {
                // Delete existing products
                $contract->products()->delete();
                
                // Create new products
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        $contract->products()->create([
                            'name' => $product['name'],
                            'quantity' => $product['quantity'] ?? 1,
                            'is_deleted' => false
                        ]);
                    }
                }
            }

            // Handle prestations update
            if ($request->has('prestations') && is_array($request->prestations)) {
                // Delete existing prestations
                $contract->prestations()->delete();
                
                // Create new prestations
                foreach ($request->prestations as $prestation) {
                    if (!empty($prestation['name'])) {
                        $contract->prestations()->create([
                            'name' => $prestation['name'],
                            'quantity' => $prestation['quantity'] ?? 1,
                        ]);
                    }
                }
            }

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
    public function createAvenant(Request $request): View
    {
        $contracts = Contract::with(['localisation', 'situationAdministrative', 'coperative'])
            ->orderBy('annee', 'desc')
            ->orderBy('contarct')
            ->get();
        $coperatives = Coperative::orderBy('nom')->get();
        
        // Get preselected contract if provided
        $selectedContract = null;
        if ($request->has('contract_id')) {
            $selectedContract = Contract::find($request->contract_id);
        }
        
        return view('contracts.avenants.create', compact('contracts', 'coperatives', 'selectedContract'));
    }

    public function storeAvenant(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'annee' => 'required|integer',
            'avenant' => 'required|string|max:255',
            'coperative_id' => 'nullable|exists:coperatives,id',
            'date' => 'required|date',
            'superficie' => 'nullable|numeric|min:0',
            'gardiennage' => 'nullable|numeric|min:0',
            'prevention_incendies' => 'nullable|numeric|min:0',
            'elagage' => 'nullable|numeric|min:0',
            'eclaircie' => 'nullable|numeric|min:0',
            'rajeunissement_romarin' => 'nullable|numeric|min:0',
            'bo_m3' => 'nullable|integer|min:0',
            'bi_m3' => 'nullable|integer|min:0',
            'bf_st' => 'nullable|integer|min:0',
            'tanin_t' => 'nullable|integer|min:0',
            'laurier_sauce' => 'nullable|integer|min:0',
            'myrte' => 'nullable|integer|min:0',
            'callune' => 'nullable|integer|min:0',
            'thym' => 'nullable|integer|min:0',
            'bruyetre' => 'nullable|integer|min:0',
            'lichen' => 'nullable|integer|min:0',
            'tanin' => 'nullable|integer|min:0',
            'romarin' => 'nullable|integer|min:0',
            'liege_male' => 'nullable|integer|min:0',
            'liege_de_reproduction' => 'nullable|integer|min:0',
            'sauge' => 'nullable|integer|min:0',
            'lavande' => 'nullable|integer|min:0',
            'armoise' => 'nullable|integer|min:0',
            'origan' => 'nullable|integer|min:0',
            'alfa' => 'nullable|integer|min:0',
            'lentisque' => 'nullable|integer|min:0',
            'ciste' => 'nullable|integer|min:0',
            'fleur_acacia_t' => 'nullable|integer|min:0',
            'valeurs_des_produits' => 'required|numeric|min:0',
            'valeur_des_prestations' => 'required|numeric|min:0',
            'redevances' => 'required|numeric|min:0',
            'taxes' => 'required|numeric|min:0',
            'total_avenant' => 'required|numeric|min:0',
        ]);

        try {
            $avenant = \App\Models\Avenant::create($validated);

            // Handle products if provided
            if ($request->has('products') && is_array($request->products)) {
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        $avenant->products()->create([
                            'name' => $product['name'],
                            'quantity' => $product['quantity'] ?? 1,
                            'is_deleted' => false
                        ]);
                    }
                }
            }

            // Handle prestations if provided
            if ($request->has('prestations') && is_array($request->prestations)) {
                foreach ($request->prestations as $prestation) {
                    if (!empty($prestation['name'])) {
                        $avenant->prestations()->create([
                            'name' => $prestation['name'],
                            'quantity' => $prestation['quantity'] ?? 1,
                        ]);
                    }
                }
            }

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

    // Coperative Management Methods
    public function createCoperative(): View
    {
        $vocations = \App\Models\Vocation::orderBy('name')->get();
        return view('contracts.coperatives.create', compact('vocations'));
    }

    public function storeCoperative(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'vocation_id' => 'nullable|exists:vocations,id',
            'nombre_membres' => 'nullable|integer|min:0',
            'nombre_coperatives' => 'nullable|integer|min:0',
        ]);

        try {
            $coperative = Coperative::create($validated);

            ActivityLogger::logCreate(
                Coperative::class,
                $coperative->id,
                "Coopérative {$coperative->nom}",
                $request
            );

            return redirect()->route('coperatives.index')
                ->with('success', 'Coopérative créée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de la coopérative: ' . $e->getMessage());
        }
    }

    // Vocation Management Methods
    public function createVocation(): View
    {
        return view('contracts.vocations.create');
    }

    public function storeVocation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vocations,name,NULL,id,deleted_at,NULL',
        ]);

        try {
            $vocation = \App\Models\Vocation::create($validated);

            ActivityLogger::logCreate(
                \App\Models\Vocation::class,
                $vocation->id,
                "Vocation {$vocation->name}",
                $request
            );

            return redirect()->route('contracts.index', ['tab' => 'coperatives'])
                ->with('success', 'Vocation créée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de la vocation: ' . $e->getMessage());
        }
    }

    public function editVocation(\App\Models\Vocation $vocation): View
    {
        return view('contracts.vocations.edit', compact('vocation'));
    }

    public function updateVocation(Request $request, \App\Models\Vocation $vocation): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vocations,name,' . $vocation->id . ',id,deleted_at,NULL',
        ]);

        try {
            $vocation->update($validated);

            ActivityLogger::logUpdate(
                \App\Models\Vocation::class,
                $vocation->id,
                "Vocation {$vocation->name}",
                [],
                $request
            );

            return redirect()->route('contracts.index', ['tab' => 'coperatives'])
                ->with('success', 'Vocation mise à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour de la vocation: ' . $e->getMessage());
        }
    }

    public function destroyVocation(\App\Models\Vocation $vocation): RedirectResponse
    {
        try {
            $vocationName = $vocation->name;
            $vocation->delete();

            ActivityLogger::logDelete(
                \App\Models\Vocation::class,
                $vocation->id,
                "Vocation {$vocationName}"
            );

            return redirect()->route('contracts.index', ['tab' => 'coperatives'])
                ->with('success', 'Vocation supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la vocation: ' . $e->getMessage());
        }
    }

    // Coperative CRUD Methods
    public function editCoperative(Coperative $coperative): View
    {
        $vocations = \App\Models\Vocation::orderBy('name')->get();
        return view('contracts.coperatives.edit', compact('coperative', 'vocations'));
    }

    public function updateCoperative(Request $request, Coperative $coperative): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'vocation_id' => 'nullable|exists:vocations,id',
            'nombre_membres' => 'nullable|integer|min:0',
            'nombre_coperatives' => 'nullable|integer|min:0',
        ]);

        try {
            $coperative->update($validated);

            ActivityLogger::logUpdate(
                Coperative::class,
                $coperative->id,
                "Coopérative {$coperative->nom}",
                [],
                $request
            );

            return redirect()->route('coperatives.index')
                ->with('success', 'Coopérative mise à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour de la coopérative: ' . $e->getMessage());
        }
    }

    public function destroyCoperative(Coperative $coperative): RedirectResponse
    {
        try {
            $coperativeName = $coperative->nom;
            $coperative->delete();

            ActivityLogger::logDelete(
                Coperative::class,
                $coperative->id,
                "Coopérative {$coperativeName}"
            );

            return redirect()->route('coperatives.index')
                ->with('success', 'Coopérative supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la coopérative: ' . $e->getMessage());
        }
    }

    // Espece CRUD Methods
    public function editEspece(Espece $espece): View
    {
        return view('contracts.especes.edit', compact('espece'));
    }

    public function updateEspece(Request $request, Espece $espece): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:especes,name,' . $espece->id . ',id,deleted_at,NULL',
        ]);

        try {
            $espece->update($validated);

            ActivityLogger::logUpdate(
                Espece::class,
                $espece->id,
                "Espèce {$espece->name}",
                [],
                $request
            );

            return redirect()->route('contracts.index', ['tab' => 'especes'])
                ->with('success', 'Espèce mise à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour de l\'espèce: ' . $e->getMessage());
        }
    }

    public function destroyEspece(Espece $espece): RedirectResponse
    {
        try {
            $especeName = $espece->name;
            $espece->delete();

            ActivityLogger::logDelete(
                Espece::class,
                $espece->id,
                "Espèce {$especeName}"
            );

            return redirect()->route('contracts.index', ['tab' => 'especes'])
                ->with('success', 'Espèce supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'espèce: ' . $e->getMessage());
        }
    }

    // Avenant CRUD Methods
    public function editAvenant(\App\Models\Avenant $avenant): View
    {
        $contracts = Contract::with(['localisation', 'situationAdministrative', 'coperative'])
            ->orderBy('annee', 'desc')
            ->orderBy('contarct')
            ->get();
        $coperatives = Coperative::orderBy('nom')->get();
        $avenant->load(['products', 'prestations']);
        return view('contracts.avenants.edit', compact('avenant', 'contracts', 'coperatives'));
    }

    public function updateAvenant(Request $request, \App\Models\Avenant $avenant): RedirectResponse
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'annee' => 'required|integer',
            'avenant' => 'required|string|max:255',
            'coperative_id' => 'nullable|exists:coperatives,id',
            'date' => 'required|date',
            'superficie' => 'nullable|numeric|min:0',
            'gardiennage' => 'nullable|numeric|min:0',
            'prevention_incendies' => 'nullable|numeric|min:0',
            'elagage' => 'nullable|numeric|min:0',
            'eclaircie' => 'nullable|numeric|min:0',
            'rajeunissement_romarin' => 'nullable|numeric|min:0',
            'bo_m3' => 'nullable|integer|min:0',
            'bi_m3' => 'nullable|integer|min:0',
            'bf_st' => 'nullable|integer|min:0',
            'tanin_t' => 'nullable|integer|min:0',
            'laurier_sauce' => 'nullable|integer|min:0',
            'myrte' => 'nullable|integer|min:0',
            'callune' => 'nullable|integer|min:0',
            'thym' => 'nullable|integer|min:0',
            'bruyetre' => 'nullable|integer|min:0',
            'lichen' => 'nullable|integer|min:0',
            'tanin' => 'nullable|integer|min:0',
            'romarin' => 'nullable|integer|min:0',
            'liege_male' => 'nullable|integer|min:0',
            'liege_de_reproduction' => 'nullable|integer|min:0',
            'sauge' => 'nullable|integer|min:0',
            'lavande' => 'nullable|integer|min:0',
            'armoise' => 'nullable|integer|min:0',
            'origan' => 'nullable|integer|min:0',
            'alfa' => 'nullable|integer|min:0',
            'lentisque' => 'nullable|integer|min:0',
            'ciste' => 'nullable|integer|min:0',
            'fleur_acacia_t' => 'nullable|integer|min:0',
            'valeurs_des_produits' => 'required|numeric|min:0',
            'valeur_des_prestations' => 'required|numeric|min:0',
            'redevances' => 'required|numeric|min:0',
            'taxes' => 'required|numeric|min:0',
            'total_avenant' => 'required|numeric|min:0',
        ]);

        try {
            $avenant->update($validated);

            // Handle products update
            if ($request->has('products') && is_array($request->products)) {
                // Delete existing products
                $avenant->products()->delete();
                
                // Create new products
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        $avenant->products()->create([
                            'name' => $product['name'],
                            'quantity' => $product['quantity'] ?? 1,
                            'is_deleted' => false
                        ]);
                    }
                }
            }

            // Handle prestations update
            if ($request->has('prestations') && is_array($request->prestations)) {
                // Delete existing prestations
                $avenant->prestations()->delete();
                
                // Create new prestations
                foreach ($request->prestations as $prestation) {
                    if (!empty($prestation['name'])) {
                        $avenant->prestations()->create([
                            'name' => $prestation['name'],
                            'quantity' => $prestation['quantity'] ?? 1,
                        ]);
                    }
                }
            }

            ActivityLogger::logUpdate(
                \App\Models\Avenant::class,
                $avenant->id,
                "Avenant #{$avenant->id} ({$avenant->annee})",
                [],
                $request
            );

            return redirect()->route('contracts.index', ['tab' => 'avenants'])
                ->with('success', 'Avenant mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour de l\'avenant: ' . $e->getMessage());
        }
    }

    public function destroyAvenant(\App\Models\Avenant $avenant): RedirectResponse
    {
        try {
            $avenantId = $avenant->id;
            $avenantYear = $avenant->annee;
            $avenant->delete();

            ActivityLogger::logDelete(
                \App\Models\Avenant::class,
                $avenant->id,
                "Avenant #{$avenantId} ({$avenantYear})"
            );

            return redirect()->route('contracts.index', ['tab' => 'avenants'])
                ->with('success', 'Avenant supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'avenant: ' . $e->getMessage());
        }
    }
}
