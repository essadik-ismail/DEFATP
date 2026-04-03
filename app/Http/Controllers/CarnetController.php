<?php

namespace App\Http\Controllers;

use App\Models\Carnet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarnetController extends Controller
{
    /**
     * Liste des carnets groupes par serie et date de creation.
     */
    public function index(): View
    {
        $series = $this->seriesSummaryQuery()
            ->groupBy('serie')
            ->groupByRaw('DATE(created_at)')
            ->orderByDesc('created_date')
            ->orderBy('serie')
            ->paginate(20);

        return view('carnets.index', compact('series'));
    }

    /**
     * Formulaire de creation d'une serie de carnets.
     */
    public function create(): View
    {
        $types = Carnet::types();

        return view('carnets.create', compact('types'));
    }

    /**
     * Creation en masse : une ligne par numero dans la serie.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'de' => 'required|integer|min:0',
            'a' => 'required|integer|min:0|gte:de',
            'type' => ['required', Rule::in(Carnet::types())],
        ]);

        $de = (int) $validated['de'];
        $a = (int) $validated['a'];
        $type = $validated['type'];
        $serie = (string) $de . '-' . $a;

        $created = 0;
        $createdDate = null;

        for ($n = $de; $n <= $a; $n++) {
            $existing = Carnet::where('serie', $serie)->where('num', $n)->first();

            if (!$existing) {
                $carnet = Carnet::create([
                    'serie' => $serie,
                    'type' => $type,
                    'num' => $n,
                    'status' => Carnet::STATUS_DISPONIBLE,
                ]);
                $created++;
                $createdDate ??= $this->resolveCreatedDate($carnet);
            }
        }

        Carnet::where('serie', $serie)->update(['type' => $type]);

        $createdDate ??= $this->latestCreatedDateForSerie($serie);

        if ($created > 0) {
            return redirect()->route('carnets.show-serie', ['serie' => $serie, 'createdDate' => $createdDate])
                ->with('success', $created . ' numero(s) de carnet cree(s) pour la serie ' . $serie . '.');
        }

        return redirect()->route('carnets.show-serie', ['serie' => $serie, 'createdDate' => $createdDate])
            ->with('info', 'Aucun nouveau numero cree : la serie ' . $serie . ' existe deja et son type a ete mis a jour.');
    }

    /**
     * Affiche tous les carnets d'une serie pour une date de creation.
     */
    public function showSerie(string $serie, string $createdDate): View
    {
        $seriesSummary = $this->seriesSummaryQuery()
            ->where('serie', $serie)
            ->whereDate('created_at', $createdDate)
            ->groupBy('serie')
            ->groupByRaw('DATE(created_at)')
            ->firstOrFail();

        $carnets = Carnet::where('serie', $serie)
            ->whereDate('created_at', $createdDate)
            ->orderBy('num')
            ->paginate(100);

        return view('carnets.show', compact('carnets', 'seriesSummary'));
    }

    /**
     * Edition d'un carnet.
     */
    public function edit(Carnet $carnet): View
    {
        $types = Carnet::types();

        return view('carnets.edit', compact('carnet', 'types'));
    }

    /**
     * Mise a jour d'un carnet.
     */
    public function update(Request $request, Carnet $carnet): RedirectResponse
    {
        $createdDate = $this->resolveCreatedDate($carnet);

        $validated = $request->validate([
            'num' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('carnets', 'num')
                    ->ignore($carnet->id)
                    ->where(function ($query) use ($carnet) {
                        return $query
                            ->where('serie', $carnet->serie)
                            ->whereNull('deleted_at');
                    }),
            ],
            'status' => ['required', Rule::in(Carnet::statuses())],
            'type' => ['required', Rule::in(Carnet::types())],
        ]);

        $carnet->update([
            'num' => $validated['num'],
            'status' => $validated['status'],
        ]);

        Carnet::withTrashed()
            ->where('serie', $carnet->serie)
            ->update(['type' => $validated['type']]);

        return redirect()->route('carnets.show-serie', ['serie' => $carnet->serie, 'createdDate' => $createdDate])
            ->with('success', 'Carnet mis a jour.');
    }

    /**
     * Suppression logique.
     */
    public function destroy(Carnet $carnet): RedirectResponse
    {
        $serie = $carnet->serie;
        $createdDate = $this->resolveCreatedDate($carnet);

        $carnet->delete();

        return $this->redirectToSerieOrIndex($serie, $createdDate)
            ->with('success', 'Carnet supprime.');
    }

    /**
     * Marquer le carnet comme perdu.
     */
    public function markPerdu(Carnet $carnet): RedirectResponse
    {
        $createdDate = $this->resolveCreatedDate($carnet);

        if (!$carnet->canBeMarkedPerdu()) {
            return $this->redirectToSerieOrIndex($carnet->serie, $createdDate)
                ->with('error', 'Ce carnet ne peut pas etre marque comme perdu.');
        }

        $carnet->update(['status' => Carnet::STATUS_PERDU]);

        return $this->redirectToSerieOrIndex($carnet->serie, $createdDate)
            ->with('success', 'Carnet marque comme perdu.');
    }

    private function seriesSummaryQuery(): Builder
    {
        return Carnet::query()
            ->select('serie')
            ->selectRaw('DATE(created_at) as created_date')
            ->selectRaw('MIN(type) as type')
            ->selectRaw('MIN(num) as first_num')
            ->selectRaw('MAX(num) as last_num')
            ->selectRaw('COUNT(*) as total_carnets')
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as disponible_count', [Carnet::STATUS_DISPONIBLE])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as epuise_count', [Carnet::STATUS_EPUISE])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as utilise_count', [Carnet::STATUS_UTILISE])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as perdu_count', [Carnet::STATUS_PERDU]);
    }

    private function redirectToSerieOrIndex(string $serie, string $createdDate): RedirectResponse
    {
        if (Carnet::where('serie', $serie)->whereDate('created_at', $createdDate)->exists()) {
            return redirect()->route('carnets.show-serie', ['serie' => $serie, 'createdDate' => $createdDate]);
        }

        return redirect()->route('carnets.index');
    }

    private function resolveCreatedDate(Carnet $carnet): string
    {
        return $carnet->created_at?->toDateString() ?? now()->toDateString();
    }

    private function latestCreatedDateForSerie(string $serie): string
    {
        $carnet = Carnet::where('serie', $serie)
            ->orderByDesc('created_at')
            ->first();

        return $carnet?->created_at?->toDateString() ?? now()->toDateString();
    }
}
