<?php

namespace App\Http\Controllers;

use App\Models\Carnet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarnetController extends Controller
{
    /**
     * Liste des carnets (non supprimés), triés par série puis num.
     */
    public function index(): View
    {
        $carnets = Carnet::orderBy('serie')->orderBy('num')->paginate(50);

        return view('carnets.index', compact('carnets'));
    }

    /**
     * Formulaire de création (saisie De et À).
     */
    public function create(): View
    {
        return view('carnets.create');
    }

    /**
     * Création en masse : pour chaque entier entre De et À, une ligne (serie = "De-À", num = n, status = disponible).
     * Les doublons (serie, num) existants sont ignorés.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'de' => 'required|integer|min:0',
            'a' => 'required|integer|min:0|gte:de',
        ]);

        $de = (int) $validated['de'];
        $a = (int) $validated['a'];
        $serie = (string) $de . '-' . $a;

        $created = 0;
        for ($n = $de; $n <= $a; $n++) {
            $existing = Carnet::where('serie', $serie)->where('num', $n)->first();
            if (!$existing) {
                Carnet::create([
                    'serie' => $serie,
                    'num' => $n,
                    'status' => Carnet::STATUS_DISPONIBLE,
                ]);
                $created++;
            }
        }

        if ($created > 0) {
            return redirect()->route('carnets.index')
                ->with('success', $created . ' numéro(s) de carnet créé(s) pour la série ' . $serie . '.');
        }

        return redirect()->route('carnets.index')
            ->with('info', 'Aucun nouveau numéro créé : tous existent déjà pour la série ' . $serie . '.');
    }

    /**
     * Édition manuelle : num et statut (série en lecture seule).
     */
    public function edit(Carnet $carnet): View
    {
        return view('carnets.edit', compact('carnet'));
    }

    /**
     * Mise à jour du carnet (num, status).
     */
    public function update(Request $request, Carnet $carnet): RedirectResponse
    {
        $validated = $request->validate([
            'num' => 'required|integer|min:0',
            'status' => 'required|in:disponible,epuise,perdu,utilise',
        ]);

        $carnet->update($validated);

        return redirect()->route('carnets.index')
            ->with('success', 'Carnet mis à jour.');
    }

    /**
     * Suppression logique (deleted_at).
     */
    public function destroy(Carnet $carnet): RedirectResponse
    {
        $carnet->delete();

        return redirect()->route('carnets.index')
            ->with('success', 'Carnet supprimé.');
    }

    /**
     * Marquer le carnet comme "perdu" (si pas déjà épuisé ou perdu).
     */
    public function markPerdu(Carnet $carnet): RedirectResponse
    {
        if (!$carnet->canBeMarkedPerdu()) {
            return redirect()->route('carnets.index')
                ->with('error', 'Ce carnet ne peut pas être marqué comme perdu.');
        }

        $carnet->update(['status' => Carnet::STATUS_PERDU]);

        return redirect()->route('carnets.index')
            ->with('success', 'Carnet marqué comme perdu.');
    }
}
