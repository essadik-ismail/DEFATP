<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use App\Models\Pdfc;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PhaseController extends Controller
{
    /**
     * Show the form for creating a new phase.
     */
    public function create(Pdfc $pdfc): View
    {
        return view('pdfcs.phases.create', compact('pdfc'));
    }

    /**
     * Store a newly created phase.
     */
    public function store(Request $request, Pdfc $pdfc): RedirectResponse
    {
        $validated = $request->validate([
            'num' => 'required|integer|min:1',
            'nom' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'date_de_début' => 'nullable|date',
            'date_de_fin' => 'nullable|date|after_or_equal:date_de_début',
            'échéance' => 'nullable|date',
        ]);

        $validated['pdfc_id'] = $pdfc->id;
        $validated['etat'] = 'en_cours';

        $phase = Phase::create($validated);

        // Auto-transition: If PDFC is "Non élaboré" and has dates, move to "élaboré"
        if ($pdfc->etat == 'Non élaboré' && $pdfc->date_de_début && $pdfc->date_de_fin) {
            $pdfc->update(['etat' => 'élaboré']);
            ActivityLogger::log('update', "PDFC {$pdfc->id} : transition automatique vers 'élaboré' (première phase créée)", Pdfc::class, $pdfc->id, null, $request);
        }

        ActivityLogger::logCreate(
            Phase::class,
            $phase->id,
            "Phase créée pour PDFC {$pdfc->id}",
            $request
        );

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'Phase créée avec succès.');
    }

    /**
     * Show the form for editing a phase.
     */
    public function edit(Pdfc $pdfc, Phase $phase): View
    {
        return view('pdfcs.phases.edit', compact('pdfc', 'phase'));
    }

    /**
     * Update a phase.
     */
    public function update(Request $request, Pdfc $pdfc, Phase $phase): RedirectResponse
    {
        $validated = $request->validate([
            'num' => 'required|integer|min:1',
            'nom' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'date_de_début' => 'nullable|date',
            'date_de_fin' => 'nullable|date|after_or_equal:date_de_début',
            'échéance' => 'nullable|date',
        ]);

        $oldValues = $phase->only(['num', 'nom', 'date', 'date_de_début', 'date_de_fin', 'échéance']);
        $phase->update($validated);

        $changes = array_diff_assoc($phase->only(['num', 'nom', 'date', 'date_de_début', 'date_de_fin', 'échéance']), $oldValues);
        
        ActivityLogger::logUpdate(
            Phase::class,
            $phase->id,
            "Phase {$phase->id}",
            $changes,
            $request
        );

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'Phase mise à jour avec succès.');
    }

    /**
     * Remove a phase.
     */
    public function destroy(Pdfc $pdfc, Phase $phase): RedirectResponse
    {
        $phaseId = $phase->id;
        $phase->delete();

        ActivityLogger::logDelete(
            Phase::class,
            $phaseId,
            "Phase {$phaseId}",
            request()
        );

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'Phase supprimée avec succès.');
    }

    /**
     * Validate a phase.
     */
    public function validatePhase(Request $request, Pdfc $pdfc, Phase $phase): RedirectResponse
    {
        if (!$phase->canBeValidated()) {
            return redirect()->route('pdfcs.show', $pdfc)
                ->with('error', 'Impossible de valider la phase. Toutes les étapes doivent être validées.');
        }

        $phase->update(['etat' => 'validée']);

        ActivityLogger::log('update', "Phase {$phase->id} validée", Phase::class, $phase->id, null, $request);

        // Auto-transition: Check if all phases are validated, then move PDFC to "validé C.C"
        $pdfc->refresh();
        $allPhases = $pdfc->phases;
        if ($allPhases->count() > 0) {
            $allValidated = true;
            foreach ($allPhases as $p) {
                if ($p->etat !== 'validée') {
                    $allValidated = false;
                    break;
                }
            }
            
            if ($allValidated && $pdfc->etat == 'validé') {
                $pdfc->update(['etat' => 'validé C.C']);
                ActivityLogger::log('update', "PDFC {$pdfc->id} : transition automatique vers 'validé C.C' (toutes les phases validées)", Pdfc::class, $pdfc->id, null, $request);
            } elseif ($allValidated && $pdfc->etat == 'élaboré') {
                // If PDFC is still "élaboré" and all phases are validated, move to "validé"
                $pdfc->update(['etat' => 'validé']);
                ActivityLogger::log('update', "PDFC {$pdfc->id} : transition automatique vers 'validé' (toutes les phases validées)", Pdfc::class, $pdfc->id, null, $request);
            }
        }

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'Phase validée avec succès.');
    }
}
