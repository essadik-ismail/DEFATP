<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Localisation;
use App\Models\SituationAdministrative;
use App\Models\Essence;
use App\Models\Coperative;
use App\Models\Product;
use App\Models\Prestation;
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
        $contracts = Contract::with(['localisation', 'situationAdministrative', 'essences', 'forets', 'coperative'])
            ->when($request->has('search') && !empty(trim($request->search)), function($query) use ($request) {
                $searchTerm = '%' . trim($request->search) . '%';
                $query->where(function($q) use ($searchTerm) {
                    $q->where('contacts.contarct', 'like', $searchTerm)
                      ->orWhere('contacts.annee', 'like', $searchTerm)
                      ->orWhereHas('localisation', function($locQuery) use ($searchTerm) {
                          $locQuery->where(function($subQuery) use ($searchTerm) {
                              $subQuery->where('CODE', 'like', $searchTerm)
                                       ->orWhere('DRANEF', 'like', $searchTerm)
                                       ->orWhere('DPANEF', 'like', $searchTerm)
                                       ->orWhere('ENTITE', 'like', $searchTerm);
                          });
                      })
                      ->orWhereHas('situationAdministrative', function($sitQuery) use ($searchTerm) {
                          $sitQuery->where(function($subQuery) use ($searchTerm) {
                              $subQuery->where('commune', 'like', $searchTerm)
                                       ->orWhere('province', 'like', $searchTerm);
                          });
                      })
                      ->orWhereHas('essences', function($espQuery) use ($searchTerm) {
                          $espQuery->where('name', 'like', $searchTerm);
                      })
                      ->orWhereHas('forets', function($foretQuery) use ($searchTerm) {
                          $foretQuery->where('foret', 'like', $searchTerm);
                      })
                      ->orWhereHas('coperative', function($coopQuery) use ($searchTerm) {
                          $coopQuery->where('nom', 'like', $searchTerm);
                      });
                });
            })
            ->when($request->filled('years'), function($query) use ($request) {
                $years = is_array($request->years) ? $request->years : [$request->years];
                $query->whereIn('annee', $years);
            })
            ->when($request->filled('date_debut'), function($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_debut);
            })
            ->when($request->filled('date_fin'), function($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_fin);
            })
            ->when($request->filled('localisation_ids'), function($query) use ($request) {
                $localisationIds = is_array($request->localisation_ids) ? $request->localisation_ids : [$request->localisation_ids];
                $query->whereIn('localisation_id', $localisationIds);
            })
            ->when($request->filled('situation_administrative_ids'), function($query) use ($request) {
                $situationIds = is_array($request->situation_administrative_ids) ? $request->situation_administrative_ids : [$request->situation_administrative_ids];
                $query->whereIn('situation_administrative_id', $situationIds);
            })
            ->when($request->filled('essence_ids'), function($query) use ($request) {
                $essenceIds = is_array($request->essence_ids) ? $request->essence_ids : [$request->essence_ids];
                $query->whereHas('essences', function($q) use ($essenceIds) {
                    $q->whereIn('essences.id', $essenceIds);
                });
            })
            ->when($request->filled('foret_ids'), function($query) use ($request) {
                $foretIds = is_array($request->foret_ids) ? $request->foret_ids : [$request->foret_ids];
                $query->whereHas('forets', function($q) use ($foretIds) {
                    $q->whereIn('forets.id', $foretIds);
                });
            })
            ->when($request->filled('coperative_ids'), function($query) use ($request) {
                $coperativeIds = is_array($request->coperative_ids) ? $request->coperative_ids : [$request->coperative_ids];
                $query->whereIn('coperative_id', $coperativeIds);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        // Provide essences list for the essences table section
        $essences = Essence::when($request->filled('essence_search'), function($query) use ($request) {
                $query->where('essence', 'like', '%' . $request->essence_search . '%');
            })
            ->orderBy('essence')
            ->paginate(10, ['*'], 'essences_page');

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
        $essencesList = Essence::orderBy('essence')->get();
        $forets = \App\Models\Foret::orderBy('foret')->get();
        $coperativesList = \App\Models\Coperative::orderBy('nom')->get();
        
        // Get available years for filter
        $availableYears = Contract::select('annee')
            ->distinct()
            ->whereNotNull('annee')
            ->orderBy('annee', 'desc')
            ->pluck('annee');

        return view('contracts.index', compact('contracts', 'essences', 'avenants', 'coperatives', 'localisations', 'situations', 'essencesList', 'forets', 'coperativesList', 'availableYears'));
    }

    public function create(): View
    {
        $localisations = Localisation::orderBy('CODE')->get();
        $situationAdministratives = SituationAdministrative::orderBy('commune')->get();
        $essences = Essence::orderBy('essence')->get();
        $forets = \App\Models\Foret::orderBy('foret')->get();
        $coperatives = Coperative::with('vocation')->orderBy('nom')->get();
        $products = Product::orderBy('name')->get();
        $prestations = Prestation::orderBy('name')->get();

        return view('contracts.create', compact(
            'localisations',
            'situationAdministratives',
            'essences',
            'forets',
            'coperatives',
            'products',
            'prestations'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'annee' => 'required|integer',
            'contarct' => 'required|integer',
            'date' => 'nullable|date',
            'localisation_id' => 'required|exists:localisations,id',
            'situation_administrative_id' => 'required|exists:situation_administratives,id',
            'forets' => 'required|array|min:1',
            'forets.*' => 'exists:forets,id',
            'coperative_id' => 'required|exists:coperatives,id',
            'essences' => 'required|array|min:1',
            'essences.*' => 'exists:essences,id',
            'superficie' => 'required|numeric|min:0',
            'gardiennage_nbjour' => 'nullable|integer|min:0',
            'gardiennage_superficie' => 'nullable|integer|min:0',
            'gardiennage_parcelle' => 'nullable|string|max:255',
            'prevention_incendies_nbjour' => 'nullable|integer|min:0',
            'prevention_incendies_superficie' => 'nullable|integer|min:0',
            'prevention_incendies_parcelle' => 'nullable|string|max:255',
            'valeurs_des_produits' => 'required|string|max:255',
            'valeur_des_prestations' => 'required|string|max:255',
            'redevances' => 'required|string|max:255',
            'taxes' => 'required|string|max:255',
            'total_avenant' => 'required|string|max:255',
            'resiliation' => 'nullable|boolean',
            'date_resiliation' => 'nullable|date',
        ]);

        try {
            $essences = $validated['essences'];
            unset($validated['essences']);
            
            $forets = $validated['forets'];
            unset($validated['forets']);
            
            $contract = Contract::create($validated);
            
            // Attach essences to the contract
            $contract->essences()->attach($essences);
            
            // Attach forets to the contract
            $contract->forets()->attach($forets);

            // Handle products if provided
            if ($request->has('products') && is_array($request->products)) {
                $productSync = [];
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        // Find or create product (normalize name to match seeder)
                        $productName = trim($product['name']);
                        $productModel = \App\Models\Product::firstOrCreate(
                            ['name' => $productName]
                        );
                        $quantity = isset($product['quantity']) && $product['quantity'] > 0 ? $product['quantity'] : 1;
                        $productSync[$productModel->id] = ['quantity' => $quantity];
                    }
                }
                if (!empty($productSync)) {
                    $contract->products()->sync($productSync);
                }
            }

            // Handle prestations if provided (many-to-many, like products)
            if ($request->has('prestations') && is_array($request->prestations)) {
                $prestationSync = [];
                foreach ($request->prestations as $prestation) {
                    if (!empty($prestation['name'])) {
                        $name = trim($prestation['name']);
                        $prestationModel = Prestation::firstOrCreate(['name' => $name]);
                        $quantity = isset($prestation['quantity']) && $prestation['quantity'] > 0
                            ? $prestation['quantity']
                            : 1;
                        $prestationSync[$prestationModel->id] = ['quantity' => $quantity];
                    }
                }

                if (!empty($prestationSync)) {
                    $contract->prestations()->sync($prestationSync);
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
        $contract->load(['localisation', 'situationAdministrative', 'essences', 'forets', 'coperative', 'products', 'prestations']);
        
        // Load related avenants for this contract
        $avenants = \App\Models\Avenant::where('contact_id', $contract->id)
            ->with(['coperative', 'contract', 'products', 'prestations'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        
        ActivityLogger::log('view', "Consultation du contrat {$contract->contarct}", Contract::class);

        return view('contracts.show', compact('contract', 'avenants'));
    }

    public function edit(Contract $contract): View
    {
        $contract->load(['essences', 'forets', 'products', 'prestations']);
        $localisations = Localisation::orderBy('CODE')->get();
        $situationAdministratives = SituationAdministrative::orderBy('commune')->get();
        $essences = Essence::orderBy('essence')->get();
        $forets = \App\Models\Foret::orderBy('foret')->get();
        $coperatives = Coperative::with('vocation')->orderBy('nom')->get();
        $products = Product::orderBy('name')->get();
        $prestations = Prestation::orderBy('name')->get();

        return view('contracts.edit', compact(
            'contract',
            'localisations',
            'situationAdministratives',
            'essences',
            'forets',
            'coperatives',
            'products',
            'prestations'
        ));
    }

    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $validated = $request->validate([
            'annee' => 'required|integer',
            'contarct' => 'required|integer',
            'date' => 'nullable|date',
            'localisation_id' => 'required|exists:localisations,id',
            'situation_administrative_id' => 'required|exists:situation_administratives,id',
            'forets' => 'required|array|min:1',
            'forets.*' => 'exists:forets,id',
            'coperative_id' => 'required|exists:coperatives,id',
            'essences' => 'required|array|min:1',
            'essences.*' => 'exists:essences,id',
            'superficie' => 'required|numeric|min:0',
            'gardiennage_nbjour' => 'nullable|integer|min:0',
            'gardiennage_superficie' => 'nullable|integer|min:0',
            'gardiennage_parcelle' => 'nullable|string|max:255',
            'prevention_incendies_nbjour' => 'nullable|integer|min:0',
            'prevention_incendies_superficie' => 'nullable|integer|min:0',
            'prevention_incendies_parcelle' => 'nullable|string|max:255',
            'valeurs_des_produits' => 'required|string|max:255',
            'valeur_des_prestations' => 'required|string|max:255',
            'redevances' => 'required|string|max:255',
            'taxes' => 'required|string|max:255',
            'total_avenant' => 'required|string|max:255',
            'resiliation' => 'nullable|boolean',
            'date_resiliation' => 'nullable|date',
        ]);

        try {
            $essences = $validated['essences'];
            unset($validated['essences']);
            
            $forets = $validated['forets'];
            unset($validated['forets']);
            
            $contract->update($validated);
            
            // Sync essences to the contract (replace existing with new ones)
            $contract->essences()->sync($essences);
            
            // Sync forets to the contract (replace existing with new ones)
            $contract->forets()->sync($forets);

            // Handle products update
            if ($request->has('products') && is_array($request->products)) {
                $productSync = [];
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        // Find or create product
                        $productModel = \App\Models\Product::firstOrCreate(
                            ['name' => $product['name']]
                        );
                        $productSync[$productModel->id] = ['quantity' => $product['quantity'] ?? 1];
                    }
                }
                $contract->products()->sync($productSync);
            } else {
                // If no products provided, detach all
                $contract->products()->sync([]);
            }

            // Handle prestations update (many-to-many)
            if ($request->has('prestations') && is_array($request->prestations)) {
                $prestationSync = [];
                foreach ($request->prestations as $prestation) {
                    if (!empty($prestation['name'])) {
                        $name = trim($prestation['name']);
                        $prestationModel = Prestation::firstOrCreate(['name' => $name]);
                        $quantity = isset($prestation['quantity']) && $prestation['quantity'] > 0
                            ? $prestation['quantity']
                            : 1;
                        $prestationSync[$prestationModel->id] = ['quantity' => $quantity];
                    }
                }
                $contract->prestations()->sync($prestationSync);
            } else {
                // If no prestations provided, detach all
                $contract->prestations()->sync([]);
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

    // Essence Management Methods (removed - essences are managed in articles section)

    // Avenant Management Methods
    public function createAvenant(Request $request): View
    {
        $contracts = Contract::with(['localisation', 'situationAdministrative', 'coperative', 'products', 'prestations'])
            ->orderBy('annee', 'desc')
            ->orderBy('contarct')
            ->get();
        $coperatives = Coperative::orderBy('nom')->get();
        $products = Product::orderBy('name')->get();
        $prestations = Prestation::orderBy('name')->get();
        
        // Get preselected contract if provided
        $selectedContract = null;
        if ($request->has('contract_id')) {
            $selectedContract = Contract::with(['localisation', 'situationAdministrative', 'coperative', 'products', 'prestations'])->find($request->contract_id);
        }
        
        return view('contracts.avenants.create', compact('contracts', 'coperatives', 'selectedContract', 'products', 'prestations'));
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
            'gardiennage_nbjour' => 'nullable|integer|min:0',
            'gardiennage_superficie' => 'nullable|integer|min:0',
            'gardiennage_parcelle' => 'nullable|string|max:255',
            'prevention_incendies_nbjour' => 'nullable|integer|min:0',
            'prevention_incendies_superficie' => 'nullable|integer|min:0',
            'prevention_incendies_parcelle' => 'nullable|string|max:255',
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
                $productSync = [];
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        // Find or create product (normalize name to match seeder)
                        $productName = trim($product['name']);
                        $productModel = \App\Models\Product::firstOrCreate(
                            ['name' => $productName]
                        );
                        $quantity = isset($product['quantity']) && $product['quantity'] > 0 ? $product['quantity'] : 1;
                        $productSync[$productModel->id] = ['quantity' => $quantity];
                    }
                }
                if (!empty($productSync)) {
                    $avenant->products()->sync($productSync);
                }
            }

            // Handle prestations if provided (many-to-many)
            if ($request->has('prestations') && is_array($request->prestations)) {
                $prestationSync = [];
                foreach ($request->prestations as $prestation) {
                    if (!empty($prestation['name'])) {
                        $name = trim($prestation['name']);
                        $prestationModel = Prestation::firstOrCreate(['name' => $name]);
                        $quantity = isset($prestation['quantity']) && $prestation['quantity'] > 0
                            ? $prestation['quantity']
                            : 1;
                        $prestationSync[$prestationModel->id] = ['quantity' => $quantity];
                    }
                }

                if (!empty($prestationSync)) {
                    $avenant->prestations()->sync($prestationSync);
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

            return redirect()->route('entity-data.index', ['tab' => 'vocations'])
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

            return redirect()->route('entity-data.index', ['tab' => 'vocations'])
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

            return redirect()->route('entity-data.index', ['tab' => 'vocations'])
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


    // Avenant CRUD Methods
    public function editAvenant(\App\Models\Avenant $avenant): View
    {
        $contracts = Contract::with(['localisation', 'situationAdministrative', 'coperative'])
            ->orderBy('annee', 'desc')
            ->orderBy('contarct')
            ->get();
        $coperatives = Coperative::orderBy('nom')->get();
        $products = Product::orderBy('name')->get();
        $prestations = Prestation::orderBy('name')->get();
        $avenant->load(['products', 'prestations']);
        return view('contracts.avenants.edit', compact('avenant', 'contracts', 'coperatives', 'products', 'prestations'));
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
            'gardiennage_nbjour' => 'nullable|integer|min:0',
            'gardiennage_superficie' => 'nullable|integer|min:0',
            'gardiennage_parcelle' => 'nullable|string|max:255',
            'prevention_incendies_nbjour' => 'nullable|integer|min:0',
            'prevention_incendies_superficie' => 'nullable|integer|min:0',
            'prevention_incendies_parcelle' => 'nullable|string|max:255',
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
                $productSync = [];
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        // Find or create product
                        $productModel = \App\Models\Product::firstOrCreate(
                            ['name' => $product['name']]
                        );
                        $productSync[$productModel->id] = ['quantity' => $product['quantity'] ?? 1];
                    }
                }
                $avenant->products()->sync($productSync);
            } else {
                // If no products provided, detach all
                $avenant->products()->sync([]);
            }

            // Handle prestations update (many-to-many)
            if ($request->has('prestations') && is_array($request->prestations)) {
                $prestationSync = [];
                foreach ($request->prestations as $prestation) {
                    if (!empty($prestation['name'])) {
                        $name = trim($prestation['name']);
                        $prestationModel = Prestation::firstOrCreate(['name' => $name]);
                        $quantity = isset($prestation['quantity']) && $prestation['quantity'] > 0
                            ? $prestation['quantity']
                            : 1;
                        $prestationSync[$prestationModel->id] = ['quantity' => $quantity];
                    }
                }
                $avenant->prestations()->sync($prestationSync);
            } else {
                // If no prestations provided, detach all
                $avenant->prestations()->sync([]);
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
