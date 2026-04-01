<?php

namespace App\Http\Controllers;

use App\Models\Cession;
use App\Models\Dranef;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CessionController extends Controller
{
    public function index(Request $request): View
    {
        $dranefs = Dranef::orderBy('dranef')->get();

        $adjudications = Cession::with('dranef')
            ->withCount('articles')
            ->where('mode_cession', 'adjudication')
            ->orderByDesc('Exercice')
            ->orderByDesc('DateAdj')
            ->get();

        $appelOffres = Cession::with('dranef')
            ->withCount('articles')
            ->where('mode_cession', 'appel_offre')
            ->orderByDesc('Exercice')
            ->orderByDesc('dateAO')
            ->get();

        return view('cessions.index', [
            'dranefs' => $dranefs,
            'adjudications' => $adjudications,
            'appelOffres' => $appelOffres,
        ]);
    }

    public function create(): View
    {
        $dranefs = Dranef::orderBy('dranef')->get();

        return view('cessions.create', [
            'dranefs' => $dranefs,
        ]);
    }

    public function edit(Cession $cession): View
    {
        $dranefs = Dranef::orderBy('dranef')->get();

        return view('cessions.edit', [
            'cession' => $cession,
            'dranefs' => $dranefs,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $type = $request->input('type');

        $rules = [
            'dranef_id' => ['required', 'exists:dranefs,id'],
            'annee_exercice' => ['required', 'integer', 'min:2000', 'max:' . (now()->year + 1)],
            'type' => ['required', 'in:adjudication,appel_offre'],
        ];

        if ($type === 'adjudication') {
            $rules['date_adjudication'] = ['required', 'date'];
        } elseif ($type === 'appel_offre') {
            $rules['numero_ao'] = ['required', 'string', 'max:255'];
            $rules['date_attribution'] = ['required', 'date'];
        }

        $validated = $request->validate($rules);

        $cession = new Cession();
        $cession->dranef_id = $validated['dranef_id'];
        $cession->type = $validated['type'];
        $cession->annee_exercice = $validated['annee_exercice'];

        if ($validated['type'] === 'adjudication') {
            $cession->date_adjudication = $validated['date_adjudication'];
        } else {
            $cession->numero_ao = $validated['numero_ao'] ?? null;
            $cession->date_attribution = $validated['date_attribution'] ?? null;
        }

        $cession->status = '';
        $cession->save();

        return redirect()
            ->route('cessions.index')
            ->with('success', 'Cession créée avec succès.');
    }

    public function update(Request $request, Cession $cession): RedirectResponse
    {
        $type = $request->input('type', $cession->type);

        $rules = [
            'dranef_id' => ['required', 'exists:dranefs,id'],
            'annee_exercice' => ['required', 'integer', 'min:2000', 'max:' . (now()->year + 1)],
            'type' => ['required', 'in:adjudication,appel_offre'],
        ];

        if ($type === 'adjudication') {
            $rules['date_adjudication'] = ['required', 'date'];
        } elseif ($type === 'appel_offre') {
            $rules['numero_ao'] = ['required', 'string', 'max:255'];
            $rules['date_attribution'] = ['required', 'date'];
        }

        $validated = $request->validate($rules);

        $cession->dranef_id = $validated['dranef_id'];
        $cession->type = $validated['type'];
        $cession->annee_exercice = $validated['annee_exercice'];

        if ($validated['type'] === 'adjudication') {
            $cession->date_adjudication = $validated['date_adjudication'];
            $cession->numero_ao = null;
            $cession->date_attribution = null;
        } else {
            $cession->numero_ao = $validated['numero_ao'] ?? null;
            $cession->date_attribution = $validated['date_attribution'] ?? null;
            $cession->date_adjudication = null;
        }

        $cession->save();

        return redirect()
            ->route('cessions.show', $cession)
            ->with('success', 'Cession mise à jour avec succès.');
    }

    public function show(Cession $cession): View
    {
        $cession->load(['dranef', 'articles']);

        return view('cessions.show', [
            'cession' => $cession,
        ]);
    }

    /**
     * Set cession status to "Clôturée".
     */
    public function cloture(Cession $cession): RedirectResponse
    {
        $cession->update(['Statut' => 'cloture']);

        return redirect()
            ->back()
            ->with('success', 'La cession a été clôturée.');
    }
}

