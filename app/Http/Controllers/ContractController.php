<?php

namespace App\Http\Controllers;

use App\Models\Avenant;
use App\Models\Contract;
use App\Models\Coperative;
use App\Models\Prestation;
use App\Models\Product;
use App\Models\Vocation;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function show(Contract $contract): View
    {
        $contract->load([
            'localisation',
            'situationAdministrative',
            'essences',
            'forets',
            'coperative',
            'products',
            'prestations',
        ]);

        $avenants = Avenant::where('contact_id', $contract->id)
            ->with(['coperative', 'contract', 'products', 'prestations'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        ActivityLogger::log('view', "Consultation du contrat {$contract->contarct}", Contract::class);

        return view('contracts.show', compact('contract', 'avenants'));
    }

    public function createAvenant(Request $request): View
    {
        $contracts = Contract::with(['localisation', 'situationAdministrative', 'coperative', 'products', 'prestations'])
            ->orderBy('annee', 'desc')
            ->orderBy('contarct')
            ->get();
        $coperatives = Coperative::orderBy('nom')->get();
        $products = Product::orderBy('name')->get();
        $prestations = Prestation::orderBy('name')->get();
        $selectedContract = null;

        if ($request->filled('contract_id')) {
            $selectedContract = Contract::with(['localisation', 'situationAdministrative', 'coperative', 'products', 'prestations'])
                ->find($request->integer('contract_id'));
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
            $avenant = Avenant::create($validated);

            if ($request->has('products') && is_array($request->products)) {
                $productSync = [];

                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        $productModel = Product::firstOrCreate([
                            'name' => trim($product['name']),
                        ]);

                        $quantity = isset($product['quantity']) && $product['quantity'] > 0
                            ? $product['quantity']
                            : 1;

                        $productSync[$productModel->id] = ['quantity' => $quantity];
                    }
                }

                if ($productSync !== []) {
                    $avenant->products()->sync($productSync);
                }
            }

            if ($request->has('prestations') && is_array($request->prestations)) {
                $prestationSync = [];

                foreach ($request->prestations as $prestation) {
                    if (!empty($prestation['name'])) {
                        $prestationModel = Prestation::firstOrCreate([
                            'name' => trim($prestation['name']),
                        ]);

                        $quantity = isset($prestation['quantity']) && $prestation['quantity'] > 0
                            ? $prestation['quantity']
                            : 1;

                        $prestationSync[$prestationModel->id] = ['quantity' => $quantity];
                    }
                }

                if ($prestationSync !== []) {
                    $avenant->prestations()->sync($prestationSync);
                }
            }

            ActivityLogger::logCreate(
                Avenant::class,
                $avenant->id,
                "Avenant #{$avenant->id} ({$avenant->annee})",
                $request
            );

            return redirect()->route('contracts.index', ['tab' => 'avenants'])
                ->with('success', 'Avenant cree avec succes.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Erreur lors de la creation de l'avenant: " . $e->getMessage());
        }
    }

    public function createCoperative(): View
    {
        $vocations = Vocation::orderBy('name')->get();

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
                "Cooperative {$coperative->nom}",
                $request
            );

            return redirect()->route('coperatives.index')
                ->with('success', 'Cooperative creee avec succes.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la creation de la cooperative: ' . $e->getMessage());
        }
    }

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
            $vocation = Vocation::create($validated);

            ActivityLogger::logCreate(
                Vocation::class,
                $vocation->id,
                "Vocation {$vocation->name}",
                $request
            );

            return redirect()->route('entity-data.index', ['tab' => 'vocations'])
                ->with('success', 'Vocation creee avec succes.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la creation de la vocation: ' . $e->getMessage());
        }
    }

    public function editVocation(Vocation $vocation): View
    {
        return view('contracts.vocations.edit', compact('vocation'));
    }

    public function updateVocation(Request $request, Vocation $vocation): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vocations,name,' . $vocation->id . ',id,deleted_at,NULL',
        ]);

        try {
            $vocation->update($validated);

            ActivityLogger::logUpdate(
                Vocation::class,
                $vocation->id,
                "Vocation {$vocation->name}",
                [],
                $request
            );

            return redirect()->route('entity-data.index', ['tab' => 'vocations'])
                ->with('success', 'Vocation mise a jour avec succes.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise a jour de la vocation: ' . $e->getMessage());
        }
    }

    public function destroyVocation(Vocation $vocation): RedirectResponse
    {
        try {
            $vocationName = $vocation->name;
            $vocation->delete();

            ActivityLogger::logDelete(
                Vocation::class,
                $vocation->id,
                "Vocation {$vocationName}"
            );

            return redirect()->route('entity-data.index', ['tab' => 'vocations'])
                ->with('success', 'Vocation supprimee avec succes.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la vocation: ' . $e->getMessage());
        }
    }

    public function editCoperative(Coperative $coperative): View
    {
        $vocations = Vocation::orderBy('name')->get();

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
                "Cooperative {$coperative->nom}",
                [],
                $request
            );

            return redirect()->route('coperatives.index')
                ->with('success', 'Cooperative mise a jour avec succes.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise a jour de la cooperative: ' . $e->getMessage());
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
                "Cooperative {$coperativeName}"
            );

            return redirect()->route('coperatives.index')
                ->with('success', 'Cooperative supprimee avec succes.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la cooperative: ' . $e->getMessage());
        }
    }

    public function editAvenant(Avenant $avenant): View
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

    public function updateAvenant(Request $request, Avenant $avenant): RedirectResponse
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

            if ($request->has('products') && is_array($request->products)) {
                $productSync = [];

                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        $productModel = Product::firstOrCreate([
                            'name' => $product['name'],
                        ]);

                        $productSync[$productModel->id] = ['quantity' => $product['quantity'] ?? 1];
                    }
                }

                $avenant->products()->sync($productSync);
            } else {
                $avenant->products()->sync([]);
            }

            if ($request->has('prestations') && is_array($request->prestations)) {
                $prestationSync = [];

                foreach ($request->prestations as $prestation) {
                    if (!empty($prestation['name'])) {
                        $prestationModel = Prestation::firstOrCreate([
                            'name' => trim($prestation['name']),
                        ]);

                        $quantity = isset($prestation['quantity']) && $prestation['quantity'] > 0
                            ? $prestation['quantity']
                            : 1;

                        $prestationSync[$prestationModel->id] = ['quantity' => $quantity];
                    }
                }

                $avenant->prestations()->sync($prestationSync);
            } else {
                $avenant->prestations()->sync([]);
            }

            ActivityLogger::logUpdate(
                Avenant::class,
                $avenant->id,
                "Avenant #{$avenant->id} ({$avenant->annee})",
                [],
                $request
            );

            return redirect()->route('contracts.index', ['tab' => 'avenants'])
                ->with('success', 'Avenant mis a jour avec succes.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Erreur lors de la mise a jour de l'avenant: " . $e->getMessage());
        }
    }

    public function destroyAvenant(Avenant $avenant): RedirectResponse
    {
        try {
            $avenantId = $avenant->id;
            $avenantYear = $avenant->annee;
            $avenant->delete();

            ActivityLogger::logDelete(
                Avenant::class,
                $avenant->id,
                "Avenant #{$avenantId} ({$avenantYear})"
            );

            return redirect()->route('contracts.index', ['tab' => 'avenants'])
                ->with('success', 'Avenant supprime avec succes.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Erreur lors de la suppression de l'avenant: " . $e->getMessage());
        }
    }
}
