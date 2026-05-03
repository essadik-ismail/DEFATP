<?php

namespace App\Http\Controllers;

use App\Models\Cession;
use App\Models\Dranef;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CessionController extends Controller
{
    public function index(Request $request): View
    {
        $dranefs = Dranef::orderBy('dranef')->get();

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $userDranefId = ($user?->dranef_id && !$user->hasRole('admin')) ? $user->dranef_id : null;

        $baseQuery = fn() => Cession::with(['dranef', 'articles.contractVentes.recolement'])
            ->withCount('articles')
            ->when($userDranefId, fn($q) => $q->where('dranef_id', $userDranefId));

        $adjudications = $baseQuery()
            ->where('mode_cession', 'adjudication')
            ->orderByDesc('Exercice')
            ->orderByDesc('DateAdj')
            ->get();

        $appelOffres = $baseQuery()
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

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user?->dranef_id && !$user->hasRole('admin') && $user->dranef_id != $validated['dranef_id']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['dranef_id' => 'Vous ne pouvez créer une cession que pour votre DRANEF.']);
        }

        $cession = new Cession();
        $cession->dranef_id = $validated['dranef_id'];
        $cession->mode_cession = $validated['type'];
        $cession->Exercice = $validated['annee_exercice'];

        if ($validated['type'] === 'adjudication') {
            $cession->DateAdj = $validated['date_adjudication'];
        } else {
            $cession->numAO = $validated['numero_ao'] ?? null;
            $cession->dateAO = $validated['date_attribution'] ?? null;
        }

        $cession->Statut = 'ouvert';
        $cession->save();

        return redirect()
            ->route('cessions.index')
            ->with('success', 'Cession créée avec succès.');
    }

    public function update(Request $request, Cession $cession): RedirectResponse
    {
        // Type is immutable after creation — always use the stored value
        $type = $cession->mode_cession;

        $rules = [
            'dranef_id' => ['required', 'exists:dranefs,id'],
            'annee_exercice' => ['required', 'integer', 'min:2000', 'max:' . (now()->year + 1)],
        ];

        if ($type === 'adjudication') {
            $rules['date_adjudication'] = ['required', 'date'];
        } elseif ($type === 'appel_offre') {
            $rules['numero_ao'] = ['required', 'string', 'max:255'];
            $rules['date_attribution'] = ['required', 'date'];
        }

        $validated = $request->validate($rules);

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user?->dranef_id && !$user->hasRole('admin') && $user->dranef_id != $validated['dranef_id']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['dranef_id' => 'Vous ne pouvez modifier que les cessions de votre DRANEF.']);
        }

        $cession->dranef_id = $validated['dranef_id'];
        $cession->Exercice = $validated['annee_exercice'];

        if ($type === 'adjudication') {
            $cession->DateAdj = $validated['date_adjudication'];
            $cession->numAO = null;
            $cession->dateAO = null;
        } else {
            $cession->numAO = $validated['numero_ao'] ?? null;
            $cession->dateAO = $validated['date_attribution'] ?? null;
            $cession->DateAdj = null;
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
     * All articles must have a completed recolement before closing.
     */
    public function cloture(Cession $cession): RedirectResponse
    {
        $cession->load('articles.contractVentes.recolement');

        $allRecoled = $cession->articles->isNotEmpty()
            && $cession->articles->every(
                fn($article) => $article->contractVentes->first()?->recolement !== null
            );

        if (!$allRecoled) {
            return redirect()->back()
                ->with('error', 'Impossible de clôturer : tous les articles doivent avoir un récolement complété.');
        }

        $cession->update(['Statut' => 'cloture']);

        return redirect()
            ->back()
            ->with('success', 'La cession a été clôturée.');
    }
}

