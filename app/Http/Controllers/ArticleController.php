<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Province;
use App\Models\Commune;
use App\Models\Dranef;
use App\Models\Dpanef;
use App\Models\Zdtf;
use App\Models\Dfp;
use App\Models\Foret;
use App\Models\Canton;
use App\Models\Parcelle;
use App\Models\NatureDeCoupe;
use App\Models\ModeExploitation;
use App\Models\Essence;
use App\Models\Product;
use App\Models\Depot;
use App\Models\ContractVente;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the articles.
     */
    public function index(Request $request): View
    {
        $query = Article::with([
            'forets.dpanef.dranef',
            'forets.dpanef.zdtfs',
            'contractVentes',
            'provinces.commune',
            'essences',
            'products'
        ]);

        // Year filter
        if ($request->filled('year')) {
            $query->where('annee', $request->year);
        }

        // Adjudication date filter
        if ($request->filled('adjudication_date')) {
            $query->whereHas('contractVentes', function ($q) use ($request) {
                $q->whereDate('date_adjudication', $request->adjudication_date);
            });
        }

        // Type filter (from contract_ventes)
        if ($request->filled('type')) {
            $query->whereHas('contractVentes', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        // Global search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhere('lot', 'like', "%{$search}%")
                  ->orWhere('parcelle', 'like', "%{$search}%")
                  ->orWhere('annee', 'like', "%{$search}%")
                  ->orWhereHas('forets', function ($q) use ($search) {
                      $q->where('foret', 'like', "%{$search}%");
                  })
                  ->orWhereHas('contractVentes', function ($q) use ($search) {
                      $q->where('type', 'like', "%{$search}%")
                        ->orWhere('numeraAO', 'like', "%{$search}%");
                  });
            });
        }

        // Get available years for filter dropdown
        $availableYears = Article::select('annee')
            ->distinct()
            ->whereNotNull('annee')
            ->orderBy('annee', 'desc')
            ->pluck('annee')
            ->toArray();

        // Get available types for filter dropdown
        $availableTypes = ContractVente::select('type')
            ->distinct()
            ->whereNotNull('type')
            ->pluck('type')
            ->toArray();

        $perPage = $request->get('per_page', 15);
        $articles = $query->latest()->paginate($perPage)->appends($request->query());

        return view('articles.index', compact('articles', 'availableYears', 'availableTypes'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        $communes = Commune::with('provinces')->orderBy('nom')->get();
        $provinces = Province::with('commune')->orderBy('nom')->get();
        $dranefs = Dranef::orderBy('code')->get();
        $dpanefs = Dpanef::with('dranef')->orderBy('code')->get();
        $zdtfs = Zdtf::with('dpanef')->orderBy('code')->get();
        $dfps = Dfp::with(['zdtf', 'dpanef'])->orderBy('code')->get();
        $forets = Foret::orderBy('foret')->get();
        $cantons = Canton::with('foret')->orderBy('canton')->get();
        $parcelles = Parcelle::with('canton')->orderBy('parcelle')->get();
        $natureDeCoupes = NatureDeCoupe::orderBy('nature_de_coupe')->get();
        $modeExploitations = ModeExploitation::orderBy('mode_exploiattion')->get();
        $essences = Essence::orderBy('essence')->get();
        $products = Product::orderBy('name')->get();
        $depots = Depot::orderBy('nom')->get();

        return view('articles.create', compact(
            'communes',
            'provinces',
            'dranefs',
            'dpanefs',
            'zdtfs',
            'dfps',
            'forets',
            'cantons',
            'parcelles',
            'natureDeCoupes',
            'modeExploitations',
            'essences',
            'products',
            'depots'
        ));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Create the article
            $article = Article::create($request->validated());
            
            // Save code-based foreign keys
            if ($request->filled('dranef_code')) {
                $article->dranef_code = $request->dranef_code;
            }
            if ($request->filled('dpanef_code')) {
                $article->dpanef_code = $request->dpanef_code;
            }
            if ($request->filled('zdtf_code')) {
                $article->zdtf_code = $request->zdtf_code;
            }
            if ($request->filled('dfp_code')) {
                $article->dfp_code = $request->dfp_code;
            }
            $article->save();

            // Attach relationships
            if ($request->has('province_ids')) {
                $article->provinces()->attach($request->province_ids);
            }

            if ($request->has('foret_ids')) {
                $article->forets()->attach($request->foret_ids);
            }

            if ($request->has('parcelle_ids')) {
                $article->parcelles()->attach($request->parcelle_ids);
            }

            if ($request->has('nature_de_coupe_ids')) {
                $article->natureDeCoupes()->attach($request->nature_de_coupe_ids);
            }

            if ($request->has('mode_exploitation_ids')) {
                $article->modeExploitations()->attach($request->mode_exploitation_ids);
            }

            // Handle products (essence, product, quantity)
            if ($request->has('products') && is_array($request->products)) {
                foreach ($request->products as $productData) {
                    if (isset($productData['essence_id']) && isset($productData['product_id']) && isset($productData['quantity'])) {
                        $article->essences()->attach($productData['essence_id'], [
                            'product_id' => $productData['product_id'],
                            'quantity' => $productData['quantity']
                        ]);
                    }
                }
            }

            // Handle depot if checkbox is checked
            if ($request->has('is_on_depot') && $request->is_on_depot) {
                // You might want to attach to a specific depot or handle this differently
                // For now, we'll just mark it in the article
            }

            DB::commit();

            ActivityLogger::log('create', 'Article créé', Article::class, $article->id);

            return redirect()->route('articles.index')
                ->with('success', 'Article créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error with shorter message
            $errorMessage = $e->getMessage();
            // Extract just the main error message, not the full SQL
            if (strpos($errorMessage, 'SQLSTATE') !== false) {
                // Extract the main error part
                preg_match('/SQLSTATE\[[^\]]+\]:\s*([^\(]+)/', $errorMessage, $matches);
                $errorMessage = $matches[1] ?? substr($errorMessage, 0, 100);
            }
            
            ActivityLogger::log('error', 'Erreur lors de la création de l\'article: ' . $errorMessage);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'article. Veuillez vérifier les données saisies.');
        }
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): View
    {
        $article->load([
            'forets',
            'provinces.commune',
            'parcelles',
            'natureDeCoupes',
            'modeExploitations',
            'essences',
            'products',
            'depots',
            'contractVentes'
        ]);

        $exploitants = \App\Models\Exploitant::orderBy('nom_complet')->get();
        $contractVente = $article->contractVentes->first();

        return view('articles.show', compact('article', 'exploitants', 'contractVente'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article): View
    {
        $communes = Commune::with('provinces')->orderBy('nom')->get();
        $provinces = Province::with('commune')->orderBy('nom')->get();
        $forets = Foret::orderBy('foret')->get();
        $cantons = Canton::with('foret')->orderBy('canton')->get();
        $parcelles = Parcelle::with('canton')->orderBy('parcelle')->get();
        $natureDeCoupes = NatureDeCoupe::orderBy('nature_de_coupe')->get();
        $modeExploitations = ModeExploitation::orderBy('mode_exploiattion')->get();
        $essences = Essence::orderBy('essence')->get();
        $products = Product::orderBy('name')->get();
        $depots = Depot::orderBy('nom')->get();

        $article->load([
            'provinces.commune',
            'forets',
            'parcelles',
            'natureDeCoupes',
            'modeExploitations',
            'essences',
            'products',
            'depots'
        ]);

        return view('articles.edit', compact(
            'article',
            'communes',
            'provinces',
            'forets',
            'cantons',
            'parcelles',
            'natureDeCoupes',
            'modeExploitations',
            'essences',
            'products',
            'depots'
        ));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $article->update($request->validated());

            // Sync relationships
            if ($request->has('province_ids')) {
                $article->provinces()->sync($request->province_ids);
            }

            if ($request->has('foret_ids')) {
                $article->forets()->sync($request->foret_ids);
            }

            if ($request->has('parcelle_ids')) {
                $article->parcelles()->sync($request->parcelle_ids);
            }

            if ($request->has('nature_de_coupe_ids')) {
                $article->natureDeCoupes()->sync($request->nature_de_coupe_ids);
            }

            if ($request->has('mode_exploitation_ids')) {
                $article->modeExploitations()->sync($request->mode_exploitation_ids);
            }

            // Handle products
            if ($request->has('products') && is_array($request->products)) {
                // Detach all existing
                $article->essences()->detach();
                
                // Attach new ones
                foreach ($request->products as $productData) {
                    if (isset($productData['essence_id']) && isset($productData['product_id']) && isset($productData['quantity'])) {
                        $article->essences()->attach($productData['essence_id'], [
                            'product_id' => $productData['product_id'],
                            'quantity' => $productData['quantity']
                        ]);
                    }
                }
            }

            DB::commit();

            ActivityLogger::log('update', 'Article modifié', Article::class, $article->id);

            return redirect()->route('articles.index')
                ->with('success', 'Article modifié avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error with shorter message
            $errorMessage = $e->getMessage();
            // Extract just the main error message, not the full SQL
            if (strpos($errorMessage, 'SQLSTATE') !== false) {
                // Extract the main error part
                preg_match('/SQLSTATE\[[^\]]+\]:\s*([^\(]+)/', $errorMessage, $matches);
                $errorMessage = $matches[1] ?? substr($errorMessage, 0, 100);
            }
            
            ActivityLogger::log('error', 'Erreur lors de la modification de l\'article: ' . $errorMessage);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification de l\'article. Veuillez vérifier les données saisies.');
        }
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        try {
            $articleId = $article->id;
            $article->delete();

            ActivityLogger::log('delete', 'Article supprimé', Article::class, $articleId);

            return redirect()->route('articles.index')
                ->with('success', 'Article supprimé avec succès.');
        } catch (\Exception $e) {
            ActivityLogger::log('error', 'Erreur lors de la suppression de l\'article: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'article: ' . $e->getMessage());
        }
    }
}

