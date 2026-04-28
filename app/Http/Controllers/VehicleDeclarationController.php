<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\VehicleDeclaration;
use App\Services\ArticleWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VehicleDeclarationController extends Controller
{
    public function __construct(private readonly ArticleWorkflowService $workflow) {}

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
        $contract = $article->contractVentes()->latest()->firstOrFail();
        $vehicles = $contract->vehicleDeclarations()->with('declaredBy')->latest()->get();

        return view('workflow.vehicles.index', compact('article', 'contract', 'vehicles'));
    }

    public function create(Article $article): View
    {
        $this->authorize('vehicle.declare');
        $contract = $article->contractVentes()->latest()->firstOrFail();

        return view('workflow.vehicles.create', compact('article', 'contract'));
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

        $contract = $article->contractVentes()->latest()->firstOrFail();

        $contract->vehicleDeclarations()->create(array_merge(
            $request->only(['immatriculation', 'marque', 'capacite', 'capacite_unite', 'chauffeur_nom', 'chauffeur_cin', 'date_declaration']),
            ['declared_by' => Auth::id()]
        ));

        // Advance workflow to TRANCHES_IN_PROGRESS once first vehicle is declared
        $current = $article->workflow_state ?? ArticleWorkflowService::DRAFT_ARTICLE;
        if ($current === ArticleWorkflowService::PV_INSTALLATION_DONE) {
            try {
                $this->workflow->transition($article, ArticleWorkflowService::TRANCHES_IN_PROGRESS, Auth::id());
            } catch (\RuntimeException) {}
        }

        return redirect()->route('vehicles.index', $article)
            ->with('success', 'Véhicule déclaré avec succès.');
    }

    public function destroy(Article $article, VehicleDeclaration $vehicle): RedirectResponse
    {
        $this->authorize('vehicle.declare');
        $vehicle->delete();

        return back()->with('success', 'Véhicule supprimé.');
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
            'immatriculation'   => 'required|string|max:80',
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
            'immatriculation'   => 'required|string|max:80',
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
