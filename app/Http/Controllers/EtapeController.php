<?php

namespace App\Http\Controllers;

use App\Models\Etape;
use App\Models\Phase;
use App\Models\Pdfc;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class EtapeController extends Controller
{
    /**
     * Show the form for creating a new etape.
     */
    public function create(Pdfc $pdfc, Phase $phase): View
    {
        return view('pdfcs.etapes.create', compact('pdfc', 'phase'));
    }

    /**
     * Store a newly created etape.
     */
    public function store(Request $request, Pdfc $pdfc, Phase $phase): RedirectResponse
    {
        $validated = $request->validate([
            'num' => 'required|integer|min:1',
            'objet' => 'required|string|max:255',
            'content' => 'nullable|string',
            'fichier_joint' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ]);

        // Handle file upload
        if ($request->hasFile('fichier_joint')) {
            $file = $request->file('fichier_joint');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('pdfcs/etapes', $filename, 'public');
            $validated['fichier_joint'] = $path;
        }

        $validated['phase_id'] = $phase->id;
        $validated['etat'] = 'en_attente';

        $etape = Etape::create($validated);

        ActivityLogger::logCreate(
            Etape::class,
            $etape->id,
            "Étape créée pour Phase {$phase->id}",
            $request
        );

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'Étape créée avec succès.');
    }

    /**
     * Show the form for editing an etape.
     */
    public function edit(Pdfc $pdfc, Phase $phase, Etape $etape): View
    {
        return view('pdfcs.etapes.edit', compact('pdfc', 'phase', 'etape'));
    }

    /**
     * Update an etape.
     */
    public function update(Request $request, Pdfc $pdfc, Phase $phase, Etape $etape): RedirectResponse
    {
        $validated = $request->validate([
            'num' => 'required|integer|min:1',
            'objet' => 'required|string|max:255',
            'content' => 'nullable|string',
            'fichier_joint' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ]);

        // Handle file upload
        if ($request->hasFile('fichier_joint')) {
            // Delete old file if exists
            if ($etape->fichier_joint && Storage::disk('public')->exists($etape->fichier_joint)) {
                Storage::disk('public')->delete($etape->fichier_joint);
            }
            
            $file = $request->file('fichier_joint');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('pdfcs/etapes', $filename, 'public');
            $validated['fichier_joint'] = $path;
        } else {
            // Keep existing file if no new file uploaded
            unset($validated['fichier_joint']);
        }

        $oldValues = $etape->only(['num', 'objet', 'content']);
        $etape->update($validated);

        $changes = array_diff_assoc($etape->only(['num', 'objet', 'content']), $oldValues);
        
        ActivityLogger::logUpdate(
            Etape::class,
            $etape->id,
            "Étape {$etape->id}",
            $changes,
            $request
        );

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'Étape mise à jour avec succès.');
    }

    /**
     * Remove an etape.
     */
    public function destroy(Pdfc $pdfc, Phase $phase, Etape $etape): RedirectResponse
    {
        // Delete file if exists
        if ($etape->fichier_joint && Storage::disk('public')->exists($etape->fichier_joint)) {
            Storage::disk('public')->delete($etape->fichier_joint);
        }

        $etapeId = $etape->id;
        $etape->delete();

        ActivityLogger::logDelete(
            Etape::class,
            $etapeId,
            "Étape {$etapeId}",
            request()
        );

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'Étape supprimée avec succès.');
    }

    /**
     * Validate an etape.
     */
    public function validateEtape(Request $request, Pdfc $pdfc, Phase $phase, Etape $etape): RedirectResponse
    {
        $validated = $request->validate([
            'commentaire_validation' => 'nullable|string',
        ]);

        $etape->update([
            'etat' => 'validée',
            'validated_by' => auth()->id(),
            'validated_at' => now(),
            'commentaire_validation' => $validated['commentaire_validation'] ?? null,
        ]);

        ActivityLogger::log('update', "Étape {$etape->id} validée", Etape::class, $etape->id, null, $request);

        // Auto-validate phase if all etapes are validated
        $phase->refresh();
        if ($phase->canBeValidated() && $phase->etat != 'validée') {
            $phase->update(['etat' => 'validée']);
            ActivityLogger::log('update', "Phase {$phase->id} validée automatiquement (toutes les étapes validées)", Phase::class, $phase->id, null, $request);
            
            // Auto-transition PDFC if all phases are validated
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
                    $pdfc->update(['etat' => 'validé']);
                    ActivityLogger::log('update', "PDFC {$pdfc->id} : transition automatique vers 'validé' (toutes les phases validées)", Pdfc::class, $pdfc->id, null, $request);
                }
            }
        }

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'Étape validée avec succès.');
    }

    /**
     * Reject an etape.
     */
    public function rejectEtape(Request $request, Pdfc $pdfc, Phase $phase, Etape $etape): RedirectResponse
    {
        $validated = $request->validate([
            'commentaire_validation' => 'required|string',
        ]);

        $etape->update([
            'etat' => 'rejetée',
            'validated_by' => auth()->id(),
            'validated_at' => now(),
            'commentaire_validation' => $validated['commentaire_validation'],
        ]);

        ActivityLogger::log('update', "Étape {$etape->id} rejetée", Etape::class, $etape->id, null, $request);

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'Étape rejetée.');
    }
}
