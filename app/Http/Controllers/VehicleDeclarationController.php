<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\VehicleDeclaration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VehicleDeclarationController extends Controller
{

    public function overview(): View
    {
        $this->authorize('vehicle.declare');

        $vehicles = VehicleDeclaration::with([
            'contractVente.article.cession.dranef',
            'contractVente.exploitant',
            'declaredBy',
        ])
            ->latest('date_declaration')
            ->latest()
            ->paginate(20);

        return view('workflow.vehicles.overview', compact('vehicles'));
    }

    public function index(Article $article): View
    {
        $this->authorize('vehicle.declare');

        $vehicles = $article->vehicles()->with('declaredBy')->latest()->get();

        return view('workflow.vehicles.index', compact('article', 'vehicles'));
    }

    public function search(Request $request, Article $article): JsonResponse
    {
        $this->authorize('vehicle.declare');

        $immat = trim($request->query('immatriculation', ''));

        if (!$immat) {
            return response()->json(['found' => false]);
        }

        $vehicle = VehicleDeclaration::where('immatriculation', $immat)->first();

        if (!$vehicle) {
            return response()->json(['found' => false]);
        }

        $alreadyLinked = $article->vehicles()->where('vehicle_declaration_id', $vehicle->id)->exists();

        return response()->json([
            'found'          => true,
            'already_linked' => $alreadyLinked,
            'vehicle'        => [
                'id'             => $vehicle->id,
                'immatriculation'=> $vehicle->immatriculation,
                'marque'         => $vehicle->marque,
                'capacite'       => $vehicle->capacite,
                'capacite_unite' => $vehicle->capacite_unite,
                'chauffeur_nom'  => $vehicle->chauffeur_nom,
                'chauffeur_cin'  => $vehicle->chauffeur_cin,
            ],
        ]);
    }

    public function store(Request $request, Article $article): RedirectResponse
    {
        $this->authorize('vehicle.declare');
        $request->validate([
            'immatriculation' => 'required|string|max:80',
            'marque'          => 'nullable|string|max:100',
            'capacite'        => 'nullable|numeric|min:0',
            'capacite_unite'  => 'required|in:m3,stere,sacs,tonnes,autre',
            'chauffeur_nom'   => 'nullable|string|max:150',
            'chauffeur_cin'   => 'nullable|string|max:50',
            'date_declaration'=> 'nullable|date',
        ]);

        $existing = VehicleDeclaration::where('immatriculation', $request->immatriculation)->first();

        if ($existing) {
            $alreadyLinked = $article->vehicles()->where('vehicle_declaration_id', $existing->id)->exists();
            if ($alreadyLinked) {
                return back()->withErrors(['immatriculation' => 'Ce véhicule est déjà lié à cet article.'])->withInput();
            }
            $article->vehicles()->syncWithoutDetaching([$existing->id]);
            return redirect()->route('vehicles.index', $article)
                ->with('success', 'Véhicule existant lié à l\'article avec succès.');
        }

        $vehicle = VehicleDeclaration::create(array_merge(
            $request->only(['immatriculation', 'marque', 'capacite', 'capacite_unite', 'chauffeur_nom', 'chauffeur_cin', 'date_declaration']),
            ['declared_by' => Auth::id()]
        ));

        $article->vehicles()->syncWithoutDetaching([$vehicle->id]);

        return redirect()->route('vehicles.index', $article)
            ->with('success', 'Véhicule créé et lié à l\'article avec succès.');
    }

    public function attach(Request $request, Article $article): RedirectResponse
    {
        $this->authorize('vehicle.declare');
        $request->validate(['vehicle_id' => 'required|exists:vehicle_declarations,id']);

        $article->vehicles()->syncWithoutDetaching([$request->vehicle_id]);

        return redirect()->route('vehicles.index', $article)
            ->with('success', 'Véhicule lié à l\'article avec succès.');
    }

    public function detach(Article $article, VehicleDeclaration $vehicle): RedirectResponse
    {
        $this->authorize('vehicle.declare');

        $article->vehicles()->detach($vehicle->id);

        return back()->with('success', 'Véhicule retiré de l\'article.');
    }

    // --- Standalone CRUD at /vehicles ---

    public function createStandalone(): View
    {
        $this->authorize('vehicle.declare');

        return view('workflow.vehicles.create-standalone');
    }

    public function storeStandalone(Request $request): RedirectResponse
    {
        $this->authorize('vehicle.declare');
        $request->validate([
            'immatriculation'   => 'required|string|max:80|unique:vehicle_declarations,immatriculation',
            'marque'            => 'nullable|string|max:100',
            'capacite'          => 'nullable|numeric|min:0',
            'capacite_unite'    => 'required|in:m3,stere,sacs,tonnes,autre',
            'chauffeur_nom'     => 'nullable|string|max:150',
            'chauffeur_cin'     => 'nullable|string|max:50',
            'date_declaration'  => 'nullable|date',
        ]);

        VehicleDeclaration::create(array_merge(
            $request->only(['immatriculation', 'marque', 'capacite', 'capacite_unite', 'chauffeur_nom', 'chauffeur_cin', 'date_declaration']),
            ['declared_by' => Auth::id()]
        ));

        return redirect()->route('vehicles.overview')
            ->with('success', 'Véhicule déclaré avec succès.');
    }

    public function edit(VehicleDeclaration $vehicle): View
    {
        $this->authorize('vehicle.declare');

        return view('workflow.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, VehicleDeclaration $vehicle): RedirectResponse
    {
        $this->authorize('vehicle.declare');
        $request->validate([
            'immatriculation'   => 'required|string|max:80|unique:vehicle_declarations,immatriculation,' . $vehicle->id,
            'marque'            => 'nullable|string|max:100',
            'capacite'          => 'nullable|numeric|min:0',
            'capacite_unite'    => 'required|in:m3,stere,sacs,tonnes,autre',
            'chauffeur_nom'     => 'nullable|string|max:150',
            'chauffeur_cin'     => 'nullable|string|max:50',
            'date_declaration'  => 'nullable|date',
        ]);

        $vehicle->update($request->only(['immatriculation', 'marque', 'capacite', 'capacite_unite', 'chauffeur_nom', 'chauffeur_cin', 'date_declaration']));

        return redirect()->route('vehicles.overview')
            ->with('success', 'Véhicule mis à jour avec succès.');
    }

    public function destroyStandalone(VehicleDeclaration $vehicle): RedirectResponse
    {
        $this->authorize('vehicle.declare');
        $vehicle->delete();

        return redirect()->route('vehicles.overview')
            ->with('success', 'Véhicule supprimé.');
    }
}
