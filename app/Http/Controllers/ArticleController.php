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
use App\Models\Payment;
use App\Models\ChargeApayer;
use App\Models\Exploitant;
use App\Models\Permis;
use App\Models\PermiEnlever;
use App\Models\PermisExploiter;
use App\Models\ColportageEnlever;
use App\Models\Carnet;
use App\Models\PvInstallation;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Imports\LocationsImport;
use App\Services\ActivityLogger;
use App\Services\AlertService;
use App\Services\ArticleWorkflowService;
use App\Services\LettreAdjudicataireService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArticleController extends Controller
{
    /**
     * Articles list removed: redirect to cessions (articles are accessed via cessions).
     */
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('cessions.index');
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        $this->authorize('create', Article::class);

        // Optimize: Use select() to load only necessary fields
        $communes = Commune::select('id', 'nom', 'province_id')
            ->with('province:id,nom')
            ->orderBy('nom')
            ->get();
        $provinces = Province::select('id', 'nom')->orderBy('nom')->get();
        $dranefs = Dranef::select('id', 'code', 'dranef', 'Abréviation')->orderBy('code')->get();
        $dpanefs = Dpanef::select('id', 'code', 'dpanef', 'dranef_code') // Fixed: use 'dpanef' not 'designation'
            ->with('dranef:id,code,dranef')
            ->orderBy('code')
            ->get();
        $zdtfs = Zdtf::select('id', 'code', 'zdtf', 'dpanef_code') // Fixed: use 'zdtf' not 'designation'
            ->with('dpanef:id,code,dpanef')
            ->orderBy('code')
            ->get();
        $dfps = Dfp::select('id', 'code', 'dfp', 'zdtf_code', 'dpanef_code') // Fixed: use 'dfp' not 'designation'
            ->with(['zdtf:id,code,zdtf', 'dpanef:id,code,dpanef'])
            ->orderBy('code')
            ->get();
        $cantons = Canton::select('id', 'canton', 'foret_id')
            ->with('foret:id,foret')
            ->orderBy('canton')
            ->get();
        $parcelles = Parcelle::select('id', 'parcelle', 'canton_id') // Fixed: removed 'nom' column (doesn't exist)
            ->with('canton:id,canton')
            ->orderBy('parcelle')
            ->get();
        $natureDeCoupes = NatureDeCoupe::select('id', 'nature_de_coupe')->orderBy('nature_de_coupe')->get();
        $modeExploitations = ModeExploitation::select('id', 'mode_exploiattion')->orderBy('mode_exploiattion')->get();
        $essences = Essence::select('id', 'essence')->orderBy('essence')->get();
        $products = Product::select('id', 'name')->orderBy('name')->get();
        $depots = Depot::select('id', 'nom')->orderBy('nom')->get();

        $currentUser = Auth::user()?->load(['dranef', 'dpanef', 'zdtf', 'dfp', 'province', 'commune']);

        // Load forets with dpanef_id for dynamic filtering (Bug 7)
        $forets = Foret::select('id', 'foret', 'dpanef_id')->orderBy('foret')->get();

        // Load cession context if provided (Bug 3, 5)
        $cession = null;
        if (request('cession_id')) {
            $cession = \App\Models\Cession::with('dranef')->find(request('cession_id'));
        }

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
            'depots',
            'currentUser',
            'cession'
        ));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['groupe_cession_id'] = $data['cession_id'] ?? null;
            unset($data['cession_id']);

            // Security: non-admin users must use their own DRANEF/DPANEF/ZDTF/DFP
            // Auto-fill from user profile if the field was not submitted (locked in the form)
            $user = Auth::user()?->load(['dranef', 'dpanef', 'zdtf']);
            if ($user && !$user->hasRole('admin')) {
                if ($user->dranef?->code) {
                    if (empty($data['dranef_code'])) {
                        $data['dranef_code'] = $user->dranef->code;
                    } elseif ($data['dranef_code'] !== $user->dranef->code) {
                        DB::rollBack();
                        return redirect()->back()->withInput()
                            ->withErrors(['dranef_code' => 'Vous ne pouvez créer un article que pour votre DRANEF.']);
                    }
                }
                if ($user->dpanef?->code) {
                    if (empty($data['dpanef_code'])) {
                        $data['dpanef_code'] = $user->dpanef->code;
                    } elseif ($data['dpanef_code'] !== $user->dpanef->code) {
                        DB::rollBack();
                        return redirect()->back()->withInput()
                            ->withErrors(['dpanef_code' => 'Vous ne pouvez créer un article que pour votre DPANEF.']);
                    }
                }
                if ($user->zdtf?->code) {
                    if (empty($data['zdtf_code'])) {
                        $data['zdtf_code'] = $user->zdtf->code;
                    } elseif ($data['zdtf_code'] !== $user->zdtf->code) {
                        DB::rollBack();
                        return redirect()->back()->withInput()
                            ->withErrors(['zdtf_code' => 'Vous ne pouvez créer un article que pour votre ZDTF.']);
                    }
                }
            }

            // Bug 16 – default status is "Article créé"
            $data['current_step'] = $data['current_step'] ?? 'article_cree';

            // Create the article
            $article = Article::create($data);

            // When cession gets its first article, set status to "En cours"
            if ($article->groupe_cession_id) {
                $cession = \App\Models\Cession::find($article->groupe_cession_id);
                if ($cession && $cession->articles()->count() === 1) {
                    $cession->update(['Statut' => 'en_cours']);
                }
            }

            // Attach relationships
            if ($request->has('province_ids')) {
                $article->provinces()->attach($request->province_ids);
            }

            if ($request->has('commune_ids')) {
                $article->communes()->attach($request->commune_ids);
            }

            if ($request->has('foret_ids')) {
                $article->forets()->attach($request->foret_ids);
            }

            // Parcelle/Canton are stored as text on the article; parcelle_ids no longer used on create form
            if ($request->has('parcelle_ids') && is_array($request->parcelle_ids)) {
                $article->parcelles()->attach($request->parcelle_ids);
            }

            if ($request->has('nature_de_coupe_ids')) {
                $article->natureDeCoupes()->attach($request->nature_de_coupe_ids);
            }

            if ($request->has('mode_exploitation_ids')) {
                $article->modeExploitations()->attach($request->mode_exploitation_ids);
            }

            // Handle depots - only sync if checkbox is checked and depot_ids are provided
            if ($request->has('is_on_depot') && $request->has('depot_ids')) {
                $article->depots()->sync($request->depot_ids);
            } else {
                // If checkbox is unchecked, detach all depots
                $article->depots()->detach();
            }

            if ($request->has('products') && is_array($request->products)) {
                $rows = [];
                $now  = now();
                foreach ($request->products as $productData) {
                    if (empty($productData['essence_id']) || !is_numeric($productData['essence_id'])) {
                        continue;
                    }
                    $essenceId = (int) $productData['essence_id'];
                    // New nested structure: products[n][items][m][product_id/quantity]
                    if (isset($productData['items']) && is_array($productData['items'])) {
                        foreach ($productData['items'] as $item) {
                            if (
                                isset($item['product_id'], $item['quantity'])
                                && is_numeric($item['product_id'])
                            ) {
                                $rows[] = [
                                    'article_id' => $article->id,
                                    'essence_id' => $essenceId,
                                    'product_id' => (int) $item['product_id'],
                                    'quantity'   => $item['quantity'],
                                    'created_at' => $now,
                                    'updated_at' => $now,
                                ];
                            }
                        }
                    // Legacy flat structure fallback
                    } elseif (isset($productData['product_id'], $productData['quantity']) && is_numeric($productData['product_id'])) {
                        $rows[] = [
                            'article_id' => $article->id,
                            'essence_id' => $essenceId,
                            'product_id' => (int) $productData['product_id'],
                            'quantity'   => $productData['quantity'],
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
                if ($rows) {
                    DB::table('article_essence')->insert($rows);
                }
            }

            DB::commit();

            $locationsImported = false;
            if ($request->hasFile('locations_file')) {
                try {
                    Excel::import(new LocationsImport($article->id), $request->file('locations_file'));
                    $locationsImported = true;
                    ActivityLogger::log('update', 'Locations (plan de situation) importées à la création', Article::class, $article->id);
                } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                    $failures = $e->failures();
                    $messages = collect($failures)->map(fn ($f) => 'Ligne ' . $f->row() . ': ' . implode(', ', $f->errors()))->implode(' ; ');
                    \Log::warning('Locations import on create failed: ' . $messages);
                } catch (\Throwable $e) {
                    \Log::error('Locations import on create: ' . $e->getMessage());
                }
            }

            ActivityLogger::log('create', 'Article créé', Article::class, $article->id);

            $successMsg = 'Article créé avec succès.';
            if ($locationsImported) {
                $successMsg .= ' Plan de situation importé.';
            } elseif ($request->hasFile('locations_file')) {
                return redirect()->route('articles.edit', $article)
                    ->with('success', 'Article créé avec succès. L\'import du plan de situation a échoué ; vous pouvez réessayer l\'import Excel ci-dessous.');
            }

            // Bug 13 – redirect to cession articles list, not single article view
            if ($article->groupe_cession_id) {
                return redirect()->route('cessions.show', $article->groupe_cession_id)
                    ->with('success', $successMsg);
            }

            return redirect()->route('cessions.index')
                ->with('success', $successMsg);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Erreur de validation. Veuillez vérifier les données saisies.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log full error for debugging
            \Log::error('Article creation error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
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
                ->with('error', 'Erreur lors de la création de l\'article: ' . $errorMessage);
        }
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): View
    {
        // Optimize: Load only necessary fields for each relationship
        $article->load([
            'cession:id,mode_cession,numAO,DateAdj,dateAO,Exercice',
            'forets:id,foret',
            'provinces:id,nom',
            'communes:id,nom',
            'parcelles:id,parcelle', // Fixed: removed 'nom' column (doesn't exist)
            'natureDeCoupes:id,nature_de_coupe',
            'modeExploitations:id,mode_exploiattion',
            'essences:id,essence',
            'products:id,name',
            'depots:id,nom',
            'contractVentes' => function($query) {
                $query->with([
                    'chargeApayer' => function($q) {
                        $q->with('payments');
                    },
                    'permisExploiter',
                    'pvInstallations',
                    'vehicleDeclarations',
                    'prorogations',
                    'recolement',
                    'denombrements',
                    'exploitant' => function($q) {
                        $q->select('id', 'nom_complet', 'raison_sociale', 'numero', 'n_cin', 'adresse', 'categorie', 'activite', 'qualification_rc', 'date_obtention')
                          ->with('dranef:id,code,dranef');
                    }
                ]);
            },
        ]);

        // Optimize: Load exploitant fields needed for contract form (incl. CIN, numero, adresse for auto-fill)
        $exploitants = Exploitant::select('id', 'nom_complet', 'raison_sociale', 'numero', 'n_cin', 'adresse')
            ->orderBy('nom_complet')
            ->get();
        $contractVente = $article->contractVentes->first();

        // Load permis d'enlever for this contract if it exists
        $permisEnlevers = collect();
        if ($contractVente) {
            $permisEnlevers = \App\Models\PermiEnlever::where(function($query) use ($contractVente) {
                    $query->where('contract_vente_id', $contractVente->id)
                    ->orWhereHas('permis', function($q) use ($contractVente) {
                        $q->where('contract_vente_id', $contractVente->id);
                    });
                })
                ->with([
                    'permis',
                    'colportages' => fn($q) => $q->orderBy('date_debut', 'desc'),
                ])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Workflow state panel data
        $workflowService = app(ArticleWorkflowService::class);
        $steps   = $workflowService->getStepStatuses($article);
        $alerts  = app(AlertService::class)->getActiveAlertsForArticle($article);
        $tranchesBlockedReason = $workflowService->tranchesBlockedReason($article);

        return view('articles.show', compact('article', 'exploitants', 'contractVente', 'permisEnlevers', 'steps', 'alerts', 'tranchesBlockedReason'));
    }

    /**
     * Display a read-only consultation page for a validated article.
     */
    public function consult(Article $article): View
    {
        $article->load([
            'cession:id,mode_cession,numAO,DateAdj,dateAO,Exercice',
            'forets:id,foret',
            'provinces:id,nom',
            'communes:id,nom',
            'natureDeCoupes:id,nature_de_coupe',
            'modeExploitations:id,mode_exploiattion',
            'essences' => fn($q) => $q->withPivot('product_id', 'quantity'),
            'products:id,name',
            'depots:id,nom',
        ]);

        $dranef = $article->dranef_code ? Dranef::select('id', 'code', 'dranef', 'Abréviation')->where('code', $article->dranef_code)->first() : null;
        $dpanef = $article->dpanef_code ? Dpanef::select('id', 'code', 'dpanef')->where('code', $article->dpanef_code)->first() : null;
        $zdtf   = $article->zdtf_code   ? Zdtf::select('id', 'code', 'zdtf')->where('code', $article->zdtf_code)->first()     : null;
        $dfp    = $article->dfp_code    ? Dfp::select('id', 'code', 'dfp')->where('code', $article->dfp_code)->first()         : null;

        return view('articles.consult', compact('article', 'dranef', 'dpanef', 'zdtf', 'dfp'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article): View
    {
        $this->authorize('update', $article);

        // Optimize: Use select() to load only necessary fields
        $communes = Commune::select('id', 'nom', 'province_id')
            ->with('province:id,nom')
            ->orderBy('nom')
            ->get();
        $provinces = Province::select('id', 'nom')
            ->orderBy('nom')
            ->get();
        $dranefs = Dranef::select('id', 'code', 'dranef', 'Abréviation')->orderBy('code')->get();
        $dpanefs = Dpanef::select('id', 'code', 'dpanef', 'dranef_code') // Fixed: use 'dpanef' not 'designation'
            ->with('dranef:id,code,dranef')
            ->orderBy('code')
            ->get();
        $zdtfs = Zdtf::select('id', 'code', 'zdtf', 'dpanef_code') // Fixed: use 'zdtf' not 'designation'
            ->with('dpanef:id,code,dpanef')
            ->orderBy('code')
            ->get();
        $dfps = Dfp::select('id', 'code', 'dfp', 'zdtf_code', 'dpanef_code') // Fixed: use 'dfp' not 'designation'
            ->with(['zdtf:id,code,zdtf', 'dpanef:id,code,dpanef'])
            ->orderBy('code')
            ->get();
        $forets = Foret::select('id', 'foret', 'dpanef_id')->orderBy('foret')->get();
        $cantons = Canton::select('id', 'canton', 'foret_id')
            ->with('foret:id,foret')
            ->orderBy('canton')
            ->get();
        $parcelles = Parcelle::select('id', 'parcelle', 'canton_id') // Fixed: removed 'nom' column (doesn't exist)
            ->with('canton:id,canton')
            ->orderBy('parcelle')
            ->get();
        $natureDeCoupes = NatureDeCoupe::select('id', 'nature_de_coupe')->orderBy('nature_de_coupe')->get();
        $modeExploitations = ModeExploitation::select('id', 'mode_exploiattion')->orderBy('mode_exploiattion')->get();
        $essences = Essence::select('id', 'essence')->orderBy('essence')->get();
        $products = Product::select('id', 'name')->orderBy('name')->get();
        $depots = Depot::select('id', 'nom')->orderBy('nom')->get();
        $exploitants = Exploitant::select('id', 'nom_complet', 'raison_sociale')->orderBy('nom_complet')->get();

        $currentUser = Auth::user()?->load(['dranef', 'dpanef', 'zdtf', 'dfp', 'province', 'commune']);

        $article->load([
            'provinces',
            'communes',
            'forets.dpanef.dranef',
            'parcelles',
            'natureDeCoupes',
            'modeExploitations',
            'essences',
            'products',
            'depots',
            'cession.dranef',
        ]);

        // Fallback: fill missing code fields for articles created before these columns existed.
        // These are display-only defaults — nothing is persisted until the user saves the form.
        $user = $currentUser;
        if (!$article->dranef_code) {
            $article->dranef_code = $article->cession?->dranef?->code
                ?? $article->forets->first()?->dpanef?->dranef?->code
                ?? $user?->dranef?->code;
        }
        if (!$article->dpanef_code) {
            $article->dpanef_code = $article->forets->first()?->dpanef?->code
                ?? $user?->dpanef?->code;
        }
        if (!$article->zdtf_code) {
            $article->zdtf_code = $user?->zdtf?->code;
        }
        if (!$article->dfp_code) {
            $article->dfp_code = $user?->dfp?->code;
        }

        return view('articles.edit', compact(
            'article',
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
            'depots',
            'exploitants',
            'currentUser'
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

            // Sync many-to-many relationships (always sync so removals are persisted)
            $article->provinces()->sync(array_filter((array) $request->input('province_ids', []), fn($v) => $v !== '' && $v !== null));
            $article->communes()->sync(array_filter((array) $request->input('commune_ids', []), fn($v) => $v !== '' && $v !== null));
            $article->forets()->sync(array_filter((array) $request->input('foret_ids', []), fn($v) => $v !== '' && $v !== null));
            $article->parcelles()->sync(array_filter((array) $request->input('parcelle_ids', []), fn($v) => $v !== '' && $v !== null));
            $article->natureDeCoupes()->sync(array_filter((array) $request->input('nature_de_coupe_ids', []), fn($v) => $v !== '' && $v !== null));
            $article->modeExploitations()->sync(array_filter((array) $request->input('mode_exploitation_ids', []), fn($v) => $v !== '' && $v !== null));
            $article->depots()->sync(array_filter((array) $request->input('depot_ids', []), fn($v) => $v !== '' && $v !== null));

            // Always detach all essences then re-attach from submitted products
            $article->essences()->detach();
            foreach ((array) $request->input('products', []) as $productData) {
                if (empty($productData['essence_id']) || !is_numeric($productData['essence_id'])) {
                    continue;
                }
                $essenceId = (int) $productData['essence_id'];
                if (isset($productData['items']) && is_array($productData['items'])) {
                    foreach ($productData['items'] as $item) {
                        if (!empty($item['product_id']) && isset($item['quantity'])) {
                            $article->essences()->attach($essenceId, [
                                'product_id' => $item['product_id'],
                                'quantity'   => $item['quantity'],
                            ]);
                        }
                    }
                } elseif (!empty($productData['product_id']) && isset($productData['quantity'])) {
                    $article->essences()->attach($essenceId, [
                        'product_id' => $productData['product_id'],
                        'quantity'   => $productData['quantity'],
                    ]);
                }
            }

            DB::commit();

            ActivityLogger::log('update', 'Article modifié', Article::class, $article->id);

            if ($article->groupe_cession_id) {
                return redirect()->route('cessions.show', $article->groupe_cession_id)
                    ->with('success', 'Article modifié avec succès.');
            }

            return redirect()->route('cessions.index')
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
     * Import locations (plan de situation) from Excel file with columns: mat, x, y.
     */
    public function importLocations(Request $request, Article $article): RedirectResponse
    {
        $request->validate([
            'locations_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ], [
            'locations_file.required' => 'Veuillez sélectionner un fichier Excel.',
            'locations_file.mimes' => 'Le fichier doit être au format Excel (.xlsx ou .xls).',
            'locations_file.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        try {
            Excel::import(new LocationsImport($article->id), $request->file('locations_file'));

            ActivityLogger::log('update', 'Locations (plan de situation) importées', Article::class, $article->id);

            return redirect()->route('articles.edit', $article)
                ->with('success', 'Fichier Excel importé avec succès. Les coordonnées (mat, x, y) ont été enregistrées dans le plan de situation.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = collect($failures)->map(fn ($f) => 'Ligne ' . $f->row() . ': ' . implode(', ', $f->errors()))->implode(' ; ');
            return redirect()->back()->with('error', 'Erreurs de validation: ' . $messages);
        } catch (\Throwable $e) {
            \Log::error('Locations import error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        $this->authorize('delete', $article);

        try {
            $articleId = $article->id;
            $article->delete();

            ActivityLogger::log('delete', 'Article supprimé', Article::class, $articleId);

            return redirect()->route('cessions.index')
                ->with('success', 'Article supprimé avec succès.');
        } catch (\Exception $e) {
            ActivityLogger::log('error', 'Erreur lors de la suppression de l\'article: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'article: ' . $e->getMessage());
        }
    }

    /**
     * Toggle invendu status
     */
    public function toggleInvendu(Article $article): RedirectResponse
    {
        try {
            $article->invendu = !$article->invendu;
            $article->save();

            $status = $article->invendu ? 'invendu' : 'vendu';
            
            ActivityLogger::log('update', "Article marqué comme {$status}", Article::class, $article->id);

            return redirect()->back()->with('success', "L'article a été marqué comme {$status}.");
        } catch (\Exception $e) {
            ActivityLogger::log('error', 'Erreur lors de la mise à jour du statut invendu: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }

    /**
     * Update charge payments for an article.
     */
    public function updateChargePayments(Request $request, Article $article): RedirectResponse
    {
        $contractVente = $article->contractVentes->first();

        if (!$contractVente) {
            return redirect()->back()
                ->with('error', 'Aucun contrat de vente trouvé pour cet article.');
        }

        // Determine which step type this update targets and enforce lock
        $wfService = app(\App\Services\ArticleWorkflowService::class);
        $chargeName = strtolower($request->input('payments.' . array_key_first((array) $request->input('payments', [])) . '.charge_nom', ''));
        $isCaution  = str_contains($chargeName, 'caution') || str_contains($chargeName, 'cautionnement');
        $isTranche  = str_starts_with($chargeName, 'tranche');
        $isAnef     = str_contains($chargeName, 'anef');

        if ($isCaution && $wfService->isAtOrPast($article, \App\Services\ArticleWorkflowService::CAUTION_PAID)) {
            return back()->withErrors(['error' => 'La caution est verrouillée : cette étape a déjà été validée.']);
        }
        if (!$isCaution && !$isTranche && !$isAnef && $wfService->isAtOrPast($article, \App\Services\ArticleWorkflowService::TAXES_PAID)) {
            return back()->withErrors(['error' => 'Les taxes sont verrouillées : cette étape a déjà été validée.']);
        }

        $validated = $request->validate([
            'payments' => 'required|array',
            'payments.*.statut' => 'required|in:0,1',
            'payments.*.reference' => 'nullable|string|max:255',
            'payments.*.date_payment' => 'nullable|date',
            'payments.*.fichier_joint' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'payments.*.percepteur' => 'nullable|string|max:255',
            'payments.*.charge_id' => 'nullable|exists:charge_apayer,id',
            'payments.*.charge_nom' => 'required|string',
        ]);

        // Validation: date and quittance file are required when marking any charge as paid
        foreach ($validated['payments'] as $key => $paymentData) {
            if (($paymentData['statut'] ?? '0') == '1') {
                $chargeName = $paymentData['charge_nom'] ?? '';
                if (empty($paymentData['date_payment'])) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Date de paiement obligatoire pour « {$chargeName} ».");
                }
                $chargeId = $paymentData['charge_id'] ?? null;
                $existingPayment = $chargeId ? Payment::where('chargeapayer_id', $chargeId)->first() : null;
                $hasFile = $request->hasFile("payments.{$key}.fichier_joint") || ($existingPayment?->fichier_joint);
                if (!$hasFile) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "La quittance (PDF/JPG/PNG) est obligatoire pour « {$chargeName} ».");
                }
            }
        }

        try {
            DB::beginTransaction();

            foreach ($validated['payments'] as $key => $paymentData) {
                $chargeId = $paymentData['charge_id'] ?? null;
                
                // Find or create charge
                if ($chargeId) {
                    $charge = ChargeApayer::find($chargeId);
                } else {
                    // Create new charge if it doesn't exist
                    $charge = ChargeApayer::create([
                        'nom' => $paymentData['charge_nom'],
                        'montant' => 0,
                        'date_echeance' => null,
                        'contrat_vente_id' => $contractVente->id,
                    ]);
                }

                if (!$charge) {
                    continue;
                }

                $paymentType = $this->resolvePaymentType($charge->nom);

                // Handle file upload
                $fichierJoint = null;
                if ($request->hasFile("payments.{$key}.fichier_joint")) {
                    $file = $request->file("payments.{$key}.fichier_joint");
                    $fichierJoint = $file->store('charge-payments', 'public');
                }

                // Find or create payment
                $payment = Payment::where('chargeapayer_id', $charge->id)->first();
                
                if ($payment) {
                    // Update existing payment
                    $payment->update([
                        'type' => $paymentType,
                        'is_paye' => $paymentData['statut'] == '1',
                        'num_quittace' => $paymentData['reference'] ?? null,
                        'percepteur' => $paymentData['percepteur'] ?? null,
                        'date_payment' => $paymentData['date_payment'] ?? null,
                        'fichier_joint' => $fichierJoint ?? $payment->fichier_joint,
                    ]);
                } else {
                    // Create new payment
                    Payment::create([
                        'nom' => $charge->nom,
                        'type' => $paymentType,
                        'is_paye' => $paymentData['statut'] == '1',
                        'num_quittace' => $paymentData['reference'] ?? null,
                        'percepteur' => $paymentData['percepteur'] ?? null,
                        'date_payment' => $paymentData['date_payment'] ?? null,
                        'fichier_joint' => $fichierJoint,
                        'chargeapayer_id' => $charge->id,
                        'contract_vente_id' => $contractVente->id,
                    ]);
                }
            }

            // Check for late payments: warn and update contract status for overdue unpaid taxes
            $overdueNames = [];
            $contractVente->loadMissing('chargeApayer.payments');
            foreach ($contractVente->chargeApayer as $charge) {
                if (str_starts_with(strtolower($charge->nom), 'tranche')) {
                    continue;
                }
                $chargePayment = $charge->payments->first();
                if (!$chargePayment?->is_paye && $charge->date_echeance && now()->gt($charge->date_echeance)) {
                    $overdueNames[] = $charge->nom;
                }
                // Warn if payment date is after echeance
                if ($chargePayment?->is_paye && $chargePayment->date_payment && $charge->date_echeance) {
                    $payDate = \Carbon\Carbon::parse($chargePayment->date_payment);
                    $echeance = \Carbon\Carbon::parse($charge->date_echeance);
                    if ($payDate->gt($echeance)) {
                        session()->push('warnings', "Paiement de « {$charge->nom} » effectué après l'échéance ({$echeance->format('d/m/Y')}).");
                    }
                }
            }
            if (!empty($overdueNames)) {
                $contractVente->update(['Current_state' => 'Taxe impayée : en attente de décision']);
                $notifService = app(\App\Services\NotificationService::class);
                $actionUrl = route('articles.show', $article);
                foreach ($overdueNames as $nom) {
                    $notifService->sendToAllUsers('taxe_retard', 'Taxe impayée', "Article {$article->numero} — taxe impayée après échéance : {$nom}", [], [
                        'action_url' => $actionUrl,
                        'icon' => 'fas fa-exclamation-triangle',
                        'color' => 'warning',
                        'priority' => 'high',
                    ]);
                }
            }

            DB::commit();

            ActivityLogger::log('update', 'Paiements des charges mis à jour', Article::class, $article->id);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Paiements des charges enregistrés avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            ActivityLogger::log('error', 'Erreur lors de la mise à jour des paiements: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement des paiements: ' . $e->getMessage());
        }
    }

    private function resolvePaymentType(string $chargeName): string
    {
        $normalizedName = strtolower($chargeName);

        if (str_starts_with($normalizedName, 'tranche')) {
            return 'tranche';
        }

        if (str_contains($normalizedName, 'caution')) {
            return 'caution';
        }

        return 'taxe';
    }

    /**
     * Pay selected tranches.
     */
    public function payTranches(Request $request, Article $article): RedirectResponse
    {
        $wfService = app(\App\Services\ArticleWorkflowService::class);
        if ($wfService->isAtOrPast($article, \App\Services\ArticleWorkflowService::RECOLEMENT_PENDING)) {
            return back()->withErrors(['error' => 'Les tranches sont verrouillées : cette étape a déjà été validée.']);
        }

        $validated = $request->validate([
            'selected_tranche' => 'required',
            'num_quittance' => 'required|string|max:255',
            'date_payment' => 'required|date',
            'fichier_joint' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $contractVente = $article->contractVentes->first();

            if (!$contractVente) {
                throw new \Exception("Aucun contrat de vente trouvé pour cet article.");
            }

            $trancheId = $validated['selected_tranche'];

            $tranche = ChargeApayer::find($trancheId);

            if (!$tranche) {
                throw new \Exception("Tranche sélectionnée introuvable.");
            }

            // Create or update payment for this single tranche
            $payment = Payment::firstOrNew([
                'chargeapayer_id' => $tranche->id,
                'contract_vente_id' => $contractVente->id,
            ]);

            $payment->is_paye = true;
            $payment->num_quittace = $validated['num_quittance'];
            $payment->date_payment = $validated['date_payment'];
            $payment->nom = $tranche->nom;
            $payment->type = 'tranche';

            // Handle file upload
            if ($request->hasFile('fichier_joint')) {
                $file = $request->file('fichier_joint');
                $path = $file->store('public/tranche_justificatifs');
                $payment->fichier_joint = str_replace('public/', '', $path);
            }

            $payment->save();

            // Check if all tranches are paid to update article status
            $allTranches = $contractVente->chargeApayer->filter(function($charge) {
                return str_starts_with($charge->nom, 'Tranche');
            });

            $allPaid = true;
            foreach ($allTranches as $tranche) {
                $payment = $tranche->payments->first();
                if (!$payment || !$payment->is_paye) {
                    $allPaid = false;
                    break;
                }
            }

            if ($allPaid) {
                $article->update(['current_step' => 'recollement']);
            } else {
                $article->update(['current_step' => 'paiement_tranches']);
            }

            DB::commit();
            ActivityLogger::log('update', 'Paiement des tranches effectué', Article::class, $article->id);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Paiement des tranches enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error paying tranches: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors du paiement des tranches: ' . $e->getMessage());
        }
    }

    /**
     * Update article step.
     */
    public function updateStep(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'step' => 'required|in:cahier_affiche,contrat_vente,paiement_charges,paiement_tranches,recollement,main_levee',
        ]);

        try {
            // Define steps in order
            $steps = ['cahier_affiche', 'contrat_vente', 'paiement_charges', 'paiement_tranches', 'recollement', 'main_levee'];
            
            // Find the index of the clicked step
            $clickedStepIndex = array_search($validated['step'], $steps);
            
            // Set current_step to the step BEFORE the clicked one, so the clicked step becomes active
            // For the first step (cahier_affiche), set it to itself
            $stepToSet = $clickedStepIndex > 0 ? $steps[$clickedStepIndex - 1] : 'cahier_affiche';
            
            $article->update(['current_step' => $stepToSet]);

            ActivityLogger::log('update', 'Navigation vers l\'étape: ' . $validated['step'], Article::class, $article->id);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Navigation vers l\'étape: ' . $validated['step']);
        } catch (\Exception $e) {
            ActivityLogger::log('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }

    /**
     * Show the Lettre Adjudicataire page and resolved template values.
     */
    public function lettreAdjudicataire(Article $article, LettreAdjudicataireService $lettreAdjudicataireService): View|RedirectResponse
    {
        $article->load([
            'cession.dranef',
            'provinces',
            'contractVentes.exploitant',
            'contractVentes.chargeApayer',
            'contractVentes.permisExploiter',
        ]);

        $contractVente = $article->contractVentes->first();
        
        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer la lettre adjudicataire.');
        }

        $resolvedPlaceholders = $lettreAdjudicataireService->getResolvedData($article);
        $templateAvailable = $lettreAdjudicataireService->hasTemplate();
        $pdfAvailable = $lettreAdjudicataireService->canGeneratePdf();

        return view('articles.lettre-adjudicataire', compact(
            'article',
            'contractVente',
            'resolvedPlaceholders',
            'templateAvailable',
            'pdfAvailable'
        ));
    }

    /**
     * Print the Lettre Adjudicataire (standalone A4 view).
     */
    public function printLettreAdjudicataire(Article $article, LettreAdjudicataireService $lettreAdjudicataireService): View|RedirectResponse
    {
        $contractVente = $article->contractVentes()->latest()->first();

        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant d\'imprimer la lettre adjudicataire.');
        }

        $resolvedPlaceholders = $lettreAdjudicataireService->getResolvedData($article);

        return view('articles.lettre-adjudicataire-print', compact('article', 'resolvedPlaceholders'));
    }

    /**
     * Generate and download the Lettre Adjudicataire DOCX.
     */
    public function downloadLettreAdjudicataire(Article $article, LettreAdjudicataireService $lettreAdjudicataireService): BinaryFileResponse|RedirectResponse
    {
        $article->load([
            'contractVentes.exploitant',
            'contractVentes.chargeApayer',
            'contractVentes.permisExploiter',
        ]);

        if (!$article->contractVentes->first()) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit etre cree avant de generer la lettre adjudicataire.');
        }

        try {
            $document = $lettreAdjudicataireService->generate($article);

            ActivityLogger::log(
                'export',
                'Lettre adjudicataire telechargee',
                Article::class,
                $article->id,
                ['format' => 'docx']
            );

            return response()->download(
                $document['path'],
                $document['download_name'],
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ]
            )->deleteFileAfterSend();
        } catch (\Throwable $e) {
            \Log::error('Erreur lors de la generation de la lettre adjudicataire: ' . $e->getMessage(), [
                'article_id' => $article->id,
                'exception' => $e,
            ]);

            return redirect()->route('articles.lettre-adjudicataire', $article)
                ->with('error', 'Erreur lors de la generation de la lettre adjudicataire: ' . $e->getMessage());
        }
    }

    /**
     * Generate and download the Lettre Adjudicataire PDF.
     */
    public function downloadLettreAdjudicatairePdf(Article $article, LettreAdjudicataireService $lettreAdjudicataireService): BinaryFileResponse|RedirectResponse
    {
        $article->load([
            'contractVentes.exploitant',
            'contractVentes.chargeApayer',
            'contractVentes.permisExploiter',
        ]);

        if (!$article->contractVentes->first()) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit etre cree avant de generer la lettre adjudicataire.');
        }

        if (!$lettreAdjudicataireService->canGeneratePdf()) {
            return redirect()->back()
                ->with('error', 'La conversion PDF n\'est pas disponible sur ce serveur.');
        }

        try {
            $document = $lettreAdjudicataireService->generatePdf($article);

            ActivityLogger::log(
                'export',
                'Lettre adjudicataire PDF telechargee',
                Article::class,
                $article->id,
                ['format' => 'pdf']
            );

            return response()->download(
                $document['path'],
                $document['download_name'],
                [
                    'Content-Type' => 'application/pdf',
                ]
            )->deleteFileAfterSend();
        } catch (\Throwable $e) {
            \Log::error('Erreur lors de la generation du PDF de la lettre adjudicataire: ' . $e->getMessage(), [
                'article_id' => $article->id,
                'exception' => $e,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la generation du PDF de la lettre adjudicataire: ' . $e->getMessage());
        }
    }

    /**
     * Show Permis d'Enlever form
     */
    public function permisEnlever(Article $article)
    {
        // Load article essences
        $article->load('essences');
        
        // Load all products referenced in essences to avoid N+1 queries
        $productIds = $article->essences->pluck('pivot.product_id')->filter()->unique();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        $contractVente = $article->contractVentes()->with(['chargeApayer.payments', 'permisExploiter'])->first();
        
        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer un permis d\'enlever.');
        }

        // Get paid tranches grouped by payment date
        $paidTranches = collect();
        
        foreach ($contractVente->chargeApayer as $charge) {
            if (str_starts_with($charge->nom, 'Tranche')) {
                $payment = $charge->payments->first();
                if ($payment && $payment->is_paye && $payment->date_payment) {
                    $paidTranches->push([
                        'charge' => $charge,
                        'payment' => $payment,
                        'date_paiement' => $payment->date_payment,
                        'montant' => $charge->montant,
                        'tranche_number' => $charge->nom,
                    ]);
                }
            }
        }

        if ($paidTranches->isEmpty()) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Aucune tranche payée n\'est disponible pour générer un permis d\'enlever.');
        }

        $tranchesByDate = $paidTranches
            ->groupBy(fn ($tranche) => \Carbon\Carbon::parse($tranche['date_paiement'])->format('Y-m-d'))
            ->map(function ($tranches, $date) {
                return $tranches->map(function ($tranche) use ($date) {
                    $tranche['date_paiement'] = $date;

                    return $tranche;
                })->values();
            });

        // Get existing Permis d'Enlever for this article
        // Query records by contract_vente_id (direct relationship) or through permis relationship
        $permisEnlevers = PermiEnlever::where(function($query) use ($contractVente) {
                // Records with direct contract_vente_id
                $query->where('contract_vente_id', $contractVente->id)
                // OR records with permis relationship (for backward compatibility)
                ->orWhereHas('permis', function($q) use ($contractVente) {
                    $q->where('contract_vente_id', $contractVente->id);
                });
            })
            ->with(['permis', 'contractVente.payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Filter out dates that already have a Permis d'Enlever
        $datesWithPermis = $permisEnlevers->pluck('date_paiement')
            ->filter()
            ->map(function($date) {
                if ($date instanceof \Carbon\Carbon) {
                    return $date->format('Y-m-d');
                }
                // If it's already a string, try to parse it
                if (is_string($date)) {
                    try {
                        return \Carbon\Carbon::parse($date)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return $date;
                    }
                }
                return $date;
            })
            ->toArray();

        // Remove dates that already have a permis
        $tranchesByDate = $tranchesByDate->filter(function($tranches, $date) use ($datesWithPermis) {
            // Normalize the date key to Y-m-d format for comparison
            $dateFormatted = $date;
            if ($date instanceof \Carbon\Carbon) {
                $dateFormatted = $date->format('Y-m-d');
            } elseif (is_string($date)) {
                try {
                    $dateFormatted = \Carbon\Carbon::parse($date)->format('Y-m-d');
                } catch (\Exception $e) {
                    $dateFormatted = $date;
                }
            }
            return !in_array($dateFormatted, $datesWithPermis);
        });

        // Count total paid tranches
        $totalPaidTranches = $paidTranches->count();

        // Get total number of tranches from contract
        $nombreTranche = $contractVente->nombre_tranche ?? 1;

        // Check if can create more permis
        $canCreateMore = $permisEnlevers->count() < $totalPaidTranches;

        // Build available payments for the remaining (unpermitted) tranches
        $availablePayments = $tranchesByDate->map(function ($tranches, $date) {
            $firstTranche = $tranches->first();
            $payment = $firstTranche['payment'];
            return [
                'date'           => $date,
                'num_quittace'   => $payment->num_quittace ?? '',
                'percepteur'     => $payment->percepteur ?? '',
                'tranche_label'  => $tranches->pluck('tranche_number')->implode(', '),
            ];
        })->values();

        return view('articles.permis-enlever-create', compact('article', 'tranchesByDate', 'products', 'nombreTranche', 'contractVente', 'availablePayments'));
    }

    /**
     * Show details page for a single Permis d'Enlever.
     */
    public function showPermisEnlever(Article $article, PermiEnlever $permiEnlever)
    {
        $permiEnlever->load(['contractVente.payments', 'essences', 'products', 'colportages']);

        // Build authorized quantities table (essence+product rows)
        $authorizedRows = collect(
            DB::table('permisenlever_product')
                ->where('permis_id', $permiEnlever->id)
                ->select('id_essence', 'product_id', 'quantity')
                ->get()
        );

        $essenceIds = $authorizedRows->pluck('id_essence')->filter()->unique()->values();
        $productIds = $authorizedRows->pluck('product_id')->filter()->unique()->values();
        $essencesById = $essenceIds->isEmpty() ? collect() : Essence::whereIn('id', $essenceIds)->get()->keyBy('id');
        $productsById = $productIds->isEmpty() ? collect() : Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Consumed quantities from colportage_enlever_product
        $usedRows = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('colportage_enlever_product')) {
            $usedRows = DB::table('colportage_enlever_product')
                ->join('colportage_enlever', 'colportage_enlever.id', '=', 'colportage_enlever_product.colportage_enlever_id')
                ->where('colportage_enlever.id_permis_enlever', $permiEnlever->id)
                ->select(
                    'colportage_enlever_product.id_essence',
                    'colportage_enlever_product.product_id',
                    DB::raw('SUM(colportage_enlever_product.quantity) as used_quantity')
                )
                ->groupBy('colportage_enlever_product.id_essence', 'colportage_enlever_product.product_id')
                ->get()
                ->keyBy(fn ($r) => $r->id_essence . '_' . $r->product_id);
        }

        $volumeRows = $authorizedRows->map(function ($row) use ($essencesById, $productsById, $usedRows) {
            $key = $row->id_essence . '_' . $row->product_id;
            $authorized = (float) $row->quantity;
            $consumed = (float) ($usedRows->get($key)?->used_quantity ?? 0);
            return [
                'essence_name'  => $essencesById->get($row->id_essence)?->essence ?? '-',
                'product_name'  => $productsById->get($row->product_id)?->name ?? '-',
                'authorized'    => $authorized,
                'consumed'      => $consumed,
                'remaining'     => max(0, $authorized - $consumed),
            ];
        });

        $allConsumed = $volumeRows->isNotEmpty() && $volumeRows->every(fn ($r) => $r['remaining'] <= 0);

        // Load colportages with their detail rows
        $colportages = $permiEnlever->colportages()->with(['carnet'])->orderBy('created_at', 'desc')->get();
        $colportages = $this->attachColportageDetailRows($colportages);

        // Determine read-only state (article workflow has moved past TRANCHES_IN_PROGRESS)
        $workflowOrder = \App\Services\ArticleWorkflowService::STATE_ORDER;
        $currentOrder = $workflowOrder[$article->workflow_state ?? 'DRAFT_ARTICLE'] ?? 0;
        $tranchesOrder = $workflowOrder[\App\Services\ArticleWorkflowService::TRANCHES_IN_PROGRESS] ?? 0;
        $isReadOnly = $currentOrder > $tranchesOrder;

        return view('articles.permis-enlever-show', compact(
            'article', 'permiEnlever', 'volumeRows', 'colportages', 'allConsumed', 'isReadOnly'
        ));
    }

    /**
     * Print a specific Permis d'Enlever.
     */
    public function printPermisEnlever(Article $article, PermiEnlever $permiEnlever)
    {
        $permiEnlever->load([
            'contractVente.exploitant',
            'contractVente.payments',
            'products',
        ]);

        $article->load([
            'cession.dranef.dpanefs',
            'forets',
        ]);

        return view('articles.permis-enlever-print', compact('article', 'permiEnlever'));
    }

    /**
     * Store and generate Permis d'Enlever
     */
    public function storePermisEnlever(Request $request, Article $article)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'percepteur_enlever' => 'required|string|max:255',
            'essences' => 'nullable|array',
            'essences.*.essence_id' => 'nullable|exists:essences,id',
            'essences.*.product_id' => 'nullable|exists:products,id',
            'essences.*.quantity' => 'nullable|numeric|min:0',
        ]);

        $contractVente = $article->contractVentes()->with('chargeApayer.payments')->first();

        if (!$contractVente) {
            return redirect()->back()
                ->with('error', 'Un contrat de vente doit être créé.');
        }

        // Load article essences for validation
        $article->load('essences');

        if ($article->essences->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Aucune essence n\'est associée à cet article. Ajoutez des essences à l\'article avant de générer un permis d\'enlever.');
        }

        // Find the payment matching the selected date
        $selectedDate = \Carbon\Carbon::parse($validated['date'])->format('Y-m-d');
        $matchingPayment = null;
        foreach ($contractVente->chargeApayer as $charge) {
            if (!str_starts_with($charge->nom, 'Tranche')) continue;
            $payment = $charge->payments->first();
            if ($payment && $payment->is_paye && $payment->date_payment &&
                \Carbon\Carbon::parse($payment->date_payment)->format('Y-m-d') === $selectedDate) {
                $matchingPayment = $payment;
                break;
            }
        }

        if (!$matchingPayment) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Aucun paiement validé trouvé pour la date sélectionnée. Veuillez sélectionner une tranche payée.');
        }

        // Enforce max permis: one permis per paid tranche
        $totalPaidTranches = $contractVente->chargeApayer->filter(function ($charge) {
            if (!str_starts_with($charge->nom, 'Tranche')) return false;
            $payment = $charge->payments->first();
            return $payment && $payment->is_paye;
        })->count();

        $existingPermisCount = PermiEnlever::where('contract_vente_id', $contractVente->id)->count();
        if ($existingPermisCount >= $totalPaidTranches) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le nombre maximum de permis d\'enlever a été atteint (un permis par tranche payée).');
        }

        // Use quantities submitted from the form
        $essencesData = [];
        foreach (($validated['essences'] ?? []) as $essenceData) {
            if (empty($essenceData['essence_id']) || empty($essenceData['product_id'])) {
                continue;
            }
            $essencesData[] = [
                'essence_id' => $essenceData['essence_id'],
                'product_id' => $essenceData['product_id'],
                'quantity' => round((float) ($essenceData['quantity'] ?? 0), 2),
            ];
        }

        if (empty($essencesData)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Aucune essence valide trouvée.');
        }

        try {
            DB::beginTransaction();

            $totalVolume = collect($essencesData)->sum('quantity');

            // Create PermiEnlever record (without Permis parent)
            $permiEnlever = PermiEnlever::create([
                'permis_id'        => null,
                'contract_vente_id' => $contractVente->id,
                'num_quittance'    => $matchingPayment->num_quittace,
                'date'             => $matchingPayment->date_payment->format('Y-m-d'),
                'num_tranche_paye' => 1,
                'date_paiement'    => $matchingPayment->date_payment->format('Y-m-d'),
                'percepteur'       => $validated['percepteur_enlever'],
                'volume'           => $totalVolume,
            ]);

            // Insert calculated quantities into permisenlever_product
            foreach ($essencesData as $essenceData) {
                // Insert all essences, even if quantity is 0 (as it's calculated)
                DB::table('permisenlever_product')->insert([
                    'permis_id' => $permiEnlever->id,
                    'product_id' => $essenceData['product_id'],
                    'id_essence' => $essenceData['essence_id'],
                    'quantity' => $essenceData['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            ActivityLogger::log('create', 'Permis d\'enlever généré', PermiEnlever::class, $permiEnlever->id);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Permis d\'enlever créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating Permis d\'enlever: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du permis d\'enlever: ' . $e->getMessage());
        }
    }

    /**
     * Show Permis d'Exploiter form
     */
    public function permisExploiter(Article $article)
    {
        $article->load(['essences', 'forets', 'communes', 'dfp']);
        
        $contractVente = $article->contractVentes()->with(['chargeApayer.payments', 'permisExploiter'])->first();
        
        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer un permis d\'exploiter.');
        }

        // Get existing permis exploiter if exists
        $permisExploiter = $contractVente->permisExploiter;

        if (!$this->canGeneratePermisExploiter($contractVente) && !$permisExploiter) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'La caution, toutes les taxes et le service rendu par l\'ANEF doivent être payés avant de générer un permis d\'exploiter.');
        }

        $percepteurs = $contractVente->percepteur ?? '';

        // Pre-fill caution quittance number
        $cautionCharge = $contractVente->chargeApayer
            ->first(fn($c) => str_contains(strtolower($c->nom), 'caution'));
        $cautionQuittance = $cautionCharge?->payments?->first()?->num_quittace ?? '';

        // Pre-fill tax quittance numbers (concatenated)
        $taxQuittances = $contractVente->chargeApayer
            ->filter(fn($c) => !str_starts_with(strtolower($c->nom), 'tranche') && !str_contains(strtolower($c->nom), 'caution') && !str_contains(strtolower($c->nom), 'anef'))
            ->map(fn($c) => $c->payments->first()?->num_quittace)
            ->filter()
            ->unique()
            ->implode(', ');

        $dfpLabel = $article->dfp?->dfp ?? $article->dfp_code ?? '';

        return view('articles.permis-exploiter', compact('article', 'contractVente', 'permisExploiter', 'percepteurs', 'cautionQuittance', 'taxQuittances', 'dfpLabel'));
    }

    /**
     * Print the Permis d'Exploiter for an article.
     */
    public function printPermisExploiter(Article $article)
    {
        $contractVente = $article->contractVentes()
            ->with(['exploitant', 'payments'])
            ->latest()
            ->first();

        abort_if(!$contractVente, 404, 'Aucun contrat de vente trouvé.');

        $permisExploiter = $contractVente->permisExploiter;

        abort_if(!$permisExploiter, 404, 'Permis d\'exploiter non trouvé.');

        $permisExploiter->load(['contractVente.article.cession.dranef.dpanefs', 'contractVente.article.forets', 'contractVente.exploitant']);

        return view('articles.permis-exploiter-print', compact('permisExploiter'));
    }

    /**
     * Store and generate Permis d'Exploiter
     */
    public function storePermisExploiter(Request $request, Article $article)
    {
        $validated = $request->validate([
            'num_assurance' => 'required|string|max:255',
            'num_quittance' => 'required|string|max:255',
            'percepteur' => 'nullable|string|max:255',
            'date_expiration_assurance' => 'nullable|date',
            'article_ccs' => 'nullable|string|max:255',
            'dfp' => 'nullable|string|max:255',
            'clature' => 'nullable|boolean',
            'fichier_permis_signe' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $contractVente = $article->contractVentes()->first();
            
            if (!$contractVente) {
                return redirect()->back()
                    ->with('error', 'Un contrat de vente doit être créé avant de générer un permis d\'exploiter.');
            }

            $contractVente->loadMissing('chargeApayer.payments');

            if (!$this->canGeneratePermisExploiter($contractVente)) {
                return redirect()->back()
                    ->with('error', 'La caution, toutes les taxes et le service rendu par l\'ANEF doivent être payés avant de générer un permis d\'exploiter.');
            }

            $signedPath = $request->hasFile('fichier_permis_signe')
                ? $request->file('fichier_permis_signe')->store('permis-exploiter/signes', 'public')
                : null;

            // Create permis exploiter record
            $permisExploiter = PermisExploiter::create([
                'contrat_vente_id' => $contractVente->id,
                'num_assurance' => $validated['num_assurance'],
                'num_quittance' => $validated['num_quittance'],
                'percepteur' => $validated['percepteur'],
                'date_expiration_assurance' => $validated['date_expiration_assurance'] ?? null,
                'article_ccs' => $validated['article_ccs'] ?? null,
                'dfp' => $validated['dfp'] ?? null,
                'clature' => $validated['clature'] ?? false,
                'fichier_permis_signe' => $signedPath,
                'signed_at' => $signedPath ? now() : null,
            ]);

            // Update article current step if needed
            // $article->update(['current_step' => 'permis_exploiter']);

            ActivityLogger::log('create', 'Permis d\'exploiter généré', PermisExploiter::class, $permisExploiter->id);

            DB::commit();

            return redirect()->route('articles.show', $article)
                ->with('success', 'Permis d\'exploiter créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating Permis d\'exploiter: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du permis d\'exploiter: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing Permis d'Exploiter
     */
    public function updatePermisExploiter(Request $request, Article $article)
    {
        $validated = $request->validate([
            'num_assurance' => 'required|string|max:255',
            'num_quittance' => 'required|string|max:255',
            'percepteur' => 'nullable|string|max:255',
            'date_expiration_assurance' => 'nullable|date',
            'article_ccs' => 'nullable|string|max:255',
            'dfp' => 'nullable|string|max:255',
            'clature' => 'nullable|boolean',
        ]);

        try {
            $contractVente = $article->contractVentes()->with('permisExploiter')->first();

            if (!$contractVente || !$contractVente->permisExploiter) {
                return redirect()->back()->with('error', 'Permis d\'exploiter introuvable.');
            }

            $contractVente->permisExploiter->update([
                'num_assurance' => $validated['num_assurance'],
                'num_quittance' => $validated['num_quittance'],
                'percepteur' => $validated['percepteur'],
                'date_expiration_assurance' => $validated['date_expiration_assurance'] ?? null,
                'article_ccs' => $validated['article_ccs'] ?? null,
                'dfp' => $validated['dfp'] ?? null,
                'clature' => $validated['clature'] ?? false,
            ]);

            ActivityLogger::log('update', 'Permis d\'exploiter mis à jour', PermisExploiter::class, $contractVente->permisExploiter->id);

            return redirect()->route('articles.permis-exploiter', $article)
                ->with('success', 'Permis d\'exploiter mis à jour avec succès.');

        } catch (\Exception $e) {
            \Log::error('Error updating Permis d\'exploiter: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Upload the signed Permis d'Exploiter file.
     */
    public function uploadPermisExploiterSigne(Request $request, Article $article): RedirectResponse
    {
        $request->validate([
            'fichier_permis_signe' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $contractVente = $article->contractVentes()->first();
        $permisExploiter = $contractVente?->permisExploiter;

        if (!$permisExploiter) {
            return redirect()->back()->with('error', 'Permis d\'exploiter introuvable.');
        }

        $path = $request->file('fichier_permis_signe')->store('permis-exploiter/signes', 'public');

        $permisExploiter->update([
            'fichier_permis_signe' => $path,
            'signed_at' => now(),
        ]);

        ActivityLogger::log('update', 'Permis d\'exploiter signé importé', PermisExploiter::class, $permisExploiter->id);

        return redirect()->route('articles.permis-exploiter', $article)
            ->with('success', 'Permis d\'exploiter signé importé avec succès.');
    }

    /**
     * Upload signed Permis d'Enlever file.
     */
    public function uploadPermisEnleverSigne(Request $request, Article $article, \App\Models\PermiEnlever $permiEnlever): RedirectResponse
    {
        $request->validate([
            'fichier_permis_signe' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $path = $request->file('fichier_permis_signe')->store('permis-enlever/signes', 'public');

        $permiEnlever->update([
            'fichier_permis_signe' => $path,
            'signed_at' => now(),
        ]);

        ActivityLogger::log('update', 'Permis d\'enlever signé importé', \App\Models\PermiEnlever::class, $permiEnlever->id);

        return redirect()->route('articles.permis-enlever.show', ['article' => $article, 'permiEnlever' => $permiEnlever])
            ->with('success', 'Permis d\'enlever signé importé avec succès.');
    }

    private function canGeneratePermisExploiter(ContractVente $contractVente): bool
    {
        $contractVente->loadMissing('chargeApayer.payments');

        $charges = $contractVente->chargeApayer ?? collect();

        $cautionCharge = $charges->first(function ($charge) {
            $name = strtolower((string) ($charge->nom ?? ''));

            return str_contains($name, 'caution');
        });

        $taxCharges = $charges->filter(function ($charge) {
            $name = strtolower((string) ($charge->nom ?? ''));

            return !str_starts_with($name, 'tranche')
                && !str_contains($name, 'caution')
                && !str_contains($name, 'anef');
        });

        $cautionPaid = (bool) $cautionCharge?->payments?->first()?->is_paye;
        $allTaxesPaid = $taxCharges->isNotEmpty()
            ? $taxCharges->every(fn ($charge) => (bool) $charge->payments->first()?->is_paye)
            : true;

        return $cautionPaid && $allTaxesPaid;
    }

    /**
     * Attach labeled essence/product quantity rows from permisenlever_product.
     */
    private function attachPermisEnleverDetailRows($permisEnlevers)
    {
        $permisIds = $permisEnlevers->pluck('id')->filter()->unique()->values();

        if ($permisIds->isEmpty()) {
            return $permisEnlevers;
        }

        $quantityRowsByPermis = DB::table('permisenlever_product')
            ->whereIn('permis_id', $permisIds)
            ->select('permis_id', 'id_essence', 'product_id', 'quantity')
            ->get()
            ->groupBy('permis_id')
            ->map(function ($rows) {
                return $rows->map(function ($item) {
                    return [
                        'essence_id' => (int) $item->id_essence,
                        'product_id' => (int) $item->product_id,
                        'quantity' => (float) $item->quantity,
                        'permis_quantity' => (float) $item->quantity,
                    ];
                })->values();
            });

        $allRows = $quantityRowsByPermis->flatMap(fn ($rows) => $rows);
        $essenceIds = $allRows->pluck('essence_id')->filter()->unique()->values();
        $productIds = $allRows->pluck('product_id')->filter()->unique()->values();

        $essencesById = $essenceIds->isEmpty()
            ? collect()
            : Essence::whereIn('id', $essenceIds)->get()->keyBy('id');

        $productsById = $productIds->isEmpty()
            ? collect()
            : Product::whereIn('id', $productIds)->get()->keyBy('id');

        return $permisEnlevers->map(function ($permis) use ($quantityRowsByPermis, $essencesById, $productsById) {
            $quantities = collect($quantityRowsByPermis->get($permis->id, collect()))->values();

            $permis->quantities = $quantities;
            $permis->detail_rows = $quantities->map(function ($row) use ($essencesById, $productsById) {
                $essence = $essencesById->get($row['essence_id']);
                $product = $productsById->get($row['product_id']);
                $parentQuantity = (float) ($row['permis_quantity'] ?? $row['quantity'] ?? 0);

                return $row + [
                    'parent_quantity' => $parentQuantity,
                    'essence_name' => $essence?->essence ?? '-',
                    'product_name' => $product?->name ?? '-',
                ];
            })->values();

            return $permis;
        });
    }

    /**
     * Attach labeled essence/product quantity rows from colportage_enlever_product.
     */
    private function attachColportageDetailRows($colportages)
    {
        $colportageIds = $colportages->pluck('id')->filter()->unique()->values();

        if ($colportageIds->isEmpty()) {
            return $colportages;
        }

        $quantityRowsByColportage = collect();

        if (\Illuminate\Support\Facades\Schema::hasTable('colportage_enlever_product')) {
            $quantityRowsByColportage = DB::table('colportage_enlever_product')
                ->whereIn('colportage_enlever_id', $colportageIds)
                ->select('colportage_enlever_id', 'id_essence', 'product_id', 'quantity')
                ->get()
                ->groupBy('colportage_enlever_id')
                ->map(function ($rows) {
                    return $rows->map(function ($item) {
                        return [
                            'essence_id' => (int) $item->id_essence,
                            'product_id' => (int) $item->product_id,
                            'quantity' => (float) $item->quantity,
                        ];
                    })->values();
                });
        }

        $fallbackRowsByColportage = $colportages
            ->filter(fn ($colportage) => $colportage->product_id && $colportage->id_essence)
            ->mapWithKeys(function ($colportage) {
                return [$colportage->id => collect([[
                    'essence_id' => (int) $colportage->id_essence,
                    'product_id' => (int) $colportage->product_id,
                    'quantity' => (float) ($colportage->quantity ?? 0),
                ]])];
            });

        $allRows = $quantityRowsByColportage->union($fallbackRowsByColportage)->flatMap(fn ($rows) => $rows);
        $essenceIds = $allRows->pluck('essence_id')->filter()->unique()->values();
        $productIds = $allRows->pluck('product_id')->filter()->unique()->values();

        $essencesById = $essenceIds->isEmpty()
            ? collect()
            : Essence::whereIn('id', $essenceIds)->get()->keyBy('id');

        $productsById = $productIds->isEmpty()
            ? collect()
            : Product::whereIn('id', $productIds)->get()->keyBy('id');

        return $colportages->map(function ($colportage) use ($quantityRowsByColportage, $fallbackRowsByColportage, $essencesById, $productsById) {
            $quantities = collect(
                $quantityRowsByColportage->get($colportage->id)
                ?? $fallbackRowsByColportage->get($colportage->id, collect())
            )->values();

            $colportage->detail_rows = $quantities->map(function ($row) use ($essencesById, $productsById) {
                $essence = $essencesById->get($row['essence_id']);
                $product = $productsById->get($row['product_id']);

                return $row + [
                    'essence_name' => $essence?->essence ?? '-',
                    'product_name' => $product?->name ?? '-',
                ];
            })->values();

            $colportage->total_quantity = (float) $quantities->sum('quantity');

            return $colportage;
        });
    }

    /**
     * Show Permis de Colportage form
     */
    public function permisColportageCreate(Article $article)
    {
        // No need to load article essences – volumes come from permisenlever_product, not article

        $contractVente = $article->contractVentes()->with('chargeApayer.payments')->first();

        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer un permis de colportage.');
        }

        $hasPaidTranches = false;
        foreach ($contractVente->chargeApayer as $charge) {
            if (str_starts_with($charge->nom, 'Tranche')) {
                $payment = $charge->payments->first();
                if ($payment && $payment->is_paye) {
                    $hasPaidTranches = true;
                    break;
                }
            }
        }

        if (!$hasPaidTranches) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Au moins une tranche doit être payée avant de générer un permis de colportage.');
        }

        $permisEnlevers = PermiEnlever::where('contract_vente_id', $contractVente->id)
            ->with(['essences', 'products'])
            ->orderBy('created_at', 'desc')
            ->get();

        $permisEnleversWithQuantities = $this->attachPermisEnleverDetailRows($permisEnlevers);

        // Load already-used quantities from colportage per (permis_id, essence_id, product_id)
        $permisIds = $permisEnlevers->pluck('id');
        $usedByPermis = DB::table('colportage_enlever_product')
            ->join('colportage_enlever', 'colportage_enlever.id', '=', 'colportage_enlever_product.colportage_enlever_id')
            ->whereIn('colportage_enlever.id_permis_enlever', $permisIds)
            ->select(
                'colportage_enlever.id_permis_enlever',
                'colportage_enlever_product.id_essence',
                'colportage_enlever_product.product_id',
                DB::raw('SUM(colportage_enlever_product.quantity) as used_quantity')
            )
            ->groupBy('colportage_enlever.id_permis_enlever', 'colportage_enlever_product.id_essence', 'colportage_enlever_product.product_id')
            ->get()
            ->groupBy('id_permis_enlever')
            ->map(fn($rows) => $rows->keyBy(fn($r) => $r->id_essence . '_' . $r->product_id));

        // Attach used_quantity and remaining to each detail row
        $permisEnleversWithQuantities = $permisEnleversWithQuantities->map(function ($permis) use ($usedByPermis) {
            $usedMap = $usedByPermis->get($permis->id, collect());
            $permis->detail_rows = collect($permis->detail_rows ?? [])->map(function ($row) use ($usedMap) {
                $key = ($row['essence_id'] ?? 0) . '_' . ($row['product_id'] ?? 0);
                $used = (float) ($usedMap->get($key)?->used_quantity ?? 0);
                $parent = (float) ($row['parent_quantity'] ?? $row['permis_quantity'] ?? $row['quantity'] ?? 0);
                $row['used_quantity'] = $used;
                $row['remaining_quantity'] = max(0, $parent - $used);
                $row['used_pct'] = $parent > 0 ? round(($used / $parent) * 100, 1) : 0;
                $row['parent_quantity'] = $parent;
                return $row;
            })->all();
            return $permis;
        });

        $selectedPermisEnleverId = request('permis_enlever_id');
        $carnetsDisponibles = Carnet::disponible()->listable()->get();
        $vehicles = $article->vehicles()->orderBy('immatriculation')->get();

        return view('articles.permis-colportage-create', compact(
            'article', 'contractVente', 'permisEnlevers', 'permisEnleversWithQuantities',
            'selectedPermisEnleverId', 'carnetsDisponibles', 'vehicles'
        ));
    }

    /**
     * Store and generate Permis de Colportage
     */
    public function storePermisColportage(Request $request, Article $article)
    {
        $validated = $request->validate([
            'id_permis_enlever' => 'required|exists:permi_enlevers,id',
            'carnet_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('carnets', 'id')
                    ->where('status', \App\Models\Carnet::STATUS_DISPONIBLE)
                    ->whereNull('deleted_at'),
            ],
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'vehicule_immatriculation' => 'required|string|max:255',
            'chauffeur_nom' => 'required|string|max:255',
            'chauffeur_cin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'transport_nuit' => 'nullable|boolean',
            'distance_km' => 'nullable|numeric|min:0',
            'essences' => 'nullable|array',
            'essences.*.essence_id' => 'nullable|exists:essences,id',
            'essences.*.product_id' => 'nullable|exists:products,id',
            'essences.*.quantity' => 'nullable|numeric|min:0',
            'fichier_joint' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'id_permis_enlever.required' => 'Le permis d\'enlever est obligatoire.',
            'id_permis_enlever.exists' => 'Le permis d\'enlever selectionne est invalide.',
            'carnet_id.required' => 'Le numéro de permis de colportage est obligatoire.',
            'carnet_id.exists' => 'Le carnet sélectionné n\'est pas disponible (déjà utilisé, épuisé ou perdu).',
        ]);

        try {
            DB::beginTransaction();

            // Handle optional attached file (justificatif du permis de colportage)
            $fichierJoint = null;
            if ($request->hasFile('fichier_joint')) {
                $file = $request->file('fichier_joint');
                $path = $file->store('public/colportage_justificatifs');
                $fichierJoint = str_replace('public/', '', $path);
            }

            // Ensure selected Permis d'Enlever belongs to this article (via contract vente)
            $contractVente = $article->contractVentes()->first();
            $permiEnlever = PermiEnlever::where('id', $validated['id_permis_enlever'])
                ->when($contractVente, fn($q) => $q->where('contract_vente_id', $contractVente->id))
                ->first();

            if (!$permiEnlever) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Le Permis d\'Enlever sélectionné est invalide pour cet article.');
            }

            // Load parent rows from permisenlever_product (NOT from article)
            $parentRows = DB::table('permisenlever_product')
                ->where('permis_id', $permiEnlever->id)
                ->select('id_essence', 'product_id', 'quantity')
                ->get();

            if ($parentRows->isEmpty()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Ce permis d\'enlever ne contient aucune essence enregistrée. Recréez le permis d\'enlever.');
            }

            $parentQuantityMap = $parentRows->keyBy(
                fn ($row) => ((int) $row->id_essence) . '_' . ((int) $row->product_id)
            );

            $usedQuantityMap = DB::table('colportage_enlever_product')
                ->join('colportage_enlever', 'colportage_enlever.id', '=', 'colportage_enlever_product.colportage_enlever_id')
                ->where('colportage_enlever.id_permis_enlever', $permiEnlever->id)
                ->select(
                    'colportage_enlever_product.id_essence',
                    'colportage_enlever_product.product_id',
                    DB::raw('SUM(colportage_enlever_product.quantity) as used_quantity')
                )
                ->groupBy('colportage_enlever_product.id_essence', 'colportage_enlever_product.product_id')
                ->get()
                ->keyBy(fn ($row) => ((int) $row->id_essence) . '_' . ((int) $row->product_id));

            // Generate numero_permis
            $numeroPermis = 'PC-' . $article->numero . '-' . date('Y');

            // Build colportage details from submitted form (sourced from permisenlever_product via the form)
            $colportageDetails = collect($validated['essences'] ?? [])
                ->filter(function ($essenceData) {
                    return !empty($essenceData['essence_id']) && !empty($essenceData['product_id']);
                })
                ->map(function ($essenceData) {
                    return [
                        'id_essence' => (int) $essenceData['essence_id'],
                        'product_id' => (int) $essenceData['product_id'],
                        'quantity' => isset($essenceData['quantity']) ? (float) $essenceData['quantity'] : 0,
                    ];
                })
                ->filter(fn ($detail) => $detail['quantity'] > 0)
                ->values();

            if ($colportageDetails->isEmpty()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Veuillez saisir au moins un volume de colportage pour une essence.');
            }

            foreach ($colportageDetails as $detail) {
                $rowKey = $detail['id_essence'] . '_' . $detail['product_id'];
                $parentRow = $parentQuantityMap->get($rowKey);

                if (!$parentRow) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Une essence soumise ne figure pas dans le permis d\'enlever sélectionné.');
                }

                $parentQuantity = (float) ($parentRow->quantity ?? 0);
                $usedQuantity = (float) ($usedQuantityMap->get($rowKey)?->used_quantity ?? 0);
                $remainingQuantity = max(0, $parentQuantity - $usedQuantity);

                if ($detail['quantity'] > $remainingQuantity + 0.005) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Le volume de colportage dépasse le volume restant autorisé du permis d\'enlever.');
                }
            }

            $colportage = ColportageEnlever::create([
                'article_id' => $article->id,
                'id_permis_enlever' => $permiEnlever->id,
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'],
                'vehicule_immatriculation' => $validated['vehicule_immatriculation'],
                'chauffeur_nom' => $validated['chauffeur_nom'],
                'chauffeur_cin' => $validated['chauffeur_cin'],
                'destination' => $validated['destination'],
                'transport_nuit' => !empty($validated['transport_nuit']),
                'distance_km' => $validated['distance_km'] ?? null,
                'numero_permis' => $numeroPermis,
                'volume' => $colportageDetails->sum('quantity'),
                'carnet_id' => $validated['carnet_id'],
                'fichier_joint' => $fichierJoint,
            ]);

            if ($colportageDetails->isNotEmpty()) {
                $timestamp = now();

                DB::table('colportage_enlever_product')->insert(
                    $colportageDetails->map(function ($detail) use ($colportage, $timestamp) {
                        return [
                            'colportage_enlever_id' => $colportage->id,
                            'product_id' => $detail['product_id'],
                            'id_essence' => $detail['id_essence'],
                            'quantity' => $detail['quantity'],
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ];
                    })->all()
                );
            }

            // Si un carnet a été choisi, le passer en "epuise" (un numéro = un seul permis)
            if (!empty($validated['carnet_id'])) {
                Carnet::where('id', $validated['carnet_id'])
                    ->where('status', Carnet::STATUS_DISPONIBLE)
                    ->update(['status' => Carnet::STATUS_EPUISE]);
            }

            // Do not update current_step to a value outside the workflow steps

            ActivityLogger::log('create', 'Permis de colportage généré', ColportageEnlever::class, $colportage->id);

            DB::commit();

            $permiEnleverId = $validated['id_permis_enlever'];
            return redirect()->route('articles.permis-enlever.show', ['article' => $article, 'permiEnlever' => $permiEnleverId])
                ->with('success', 'Permis de colportage généré avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating Permis de colportage: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du permis de colportage: ' . $e->getMessage());
        }
    }

    /**
     * Appliquer un statut spécial (délai, résiliation, maintenance)
     */
    public function updateStatutSpecial(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'statut_special'       => 'required|in:delai,resiliation,maintenance',
            'date_statut_special'  => 'required|date',
            'motif_statut_special' => 'nullable|string|max:500',
        ]);

        $article->update($validated);

        $labels = ['delai' => 'Délai', 'resiliation' => 'Résiliation', 'maintenance' => 'Maintenance'];
        ActivityLogger::log('update', 'Statut spécial : ' . ($labels[$validated['statut_special']] ?? $validated['statut_special']), Article::class, $article->id);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Statut spécial enregistré : ' . ($labels[$validated['statut_special']] ?? $validated['statut_special']));
    }

    /**
     * Show Dénombrement form
     */
    public function denombrement(Article $article)
    {
        $contractVente = $article->contractVentes()->first();

        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de saisir un dénombrement.');
        }

        $denombrement = \App\Models\Denombrement::where('contract_vente_id', $contractVente->id)->first();

        return view('articles.denombrement', compact('article', 'contractVente', 'denombrement'));
    }

    /**
     * Store or update Dénombrement
     */
    public function storeDenombrement(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'date_denombrement'  => 'required|date',
            'agent_responsable'  => 'nullable|string|max:255',
            'volume_denombre'    => 'nullable|numeric|min:0',
            'observations'       => 'nullable|string',
            'fichier_pv'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $contractVente = $article->contractVentes()->firstOrFail();

        if ($request->hasFile('fichier_pv')) {
            $validated['fichier_pv'] = $request->file('fichier_pv')->store('denombrements', 'public');
        }

        $validated['contract_vente_id'] = $contractVente->id;

        \App\Models\Denombrement::updateOrCreate(
            ['contract_vente_id' => $contractVente->id],
            $validated
        );

        ActivityLogger::log('create', 'Dénombrement enregistré', Article::class, $article->id);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Dénombrement enregistré avec succès.');
    }

    /**
     * Download PV du dénombrement
     */
    public function downloadDenombrementPv(Article $article)
    {
        $contractVente = $article->contractVentes()->firstOrFail();
        $denombrement = \App\Models\Denombrement::where('contract_vente_id', $contractVente->id)->firstOrFail();

        abort_unless(
            $denombrement->fichier_pv && \Illuminate\Support\Facades\Storage::disk('public')->exists($denombrement->fichier_pv),
            404,
            'Fichier PV introuvable.'
        );

        return \Illuminate\Support\Facades\Storage::disk('public')->download($denombrement->fichier_pv);
    }

    /**
     * Show PV d'Installation form
     */
    public function pvInstallation(Article $article)
    {
        $article->load(['essences', 'forets', 'communes']);
        
        $contractVente = $article->contractVentes()->with('chargeApayer.payments')->first();
        
        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer un PV d\'Installation.');
        }

        if (!$contractVente->permisExploiter) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Le permis d\'exploiter doit être créé avant de générer un PV d\'Installation.');
        }

        // Get existing PV Installation if exists
        $pvInstallation = \App\Models\PvInstallation::where('contract_vente_id', $contractVente->id)->first();

        $editMode = request()->boolean('edit');

        return view('articles.pv-installation', compact('article', 'contractVente', 'pvInstallation', 'editMode'));
    }

    /**
     * Print the PV d'Installation (standalone A4 view).
     */
    public function printPvInstallation(Article $article)
    {
        $article->load(['cession.dranef.dpanefs', 'forets', 'modeExploitations', 'natureDeCoupes']);

        $contractVente = $article->contractVentes()->with('exploitant')->latest()->first();

        abort_if(!$contractVente, 404, 'Aucun contrat de vente trouvé.');

        $pvInstallation = \App\Models\PvInstallation::where('contract_vente_id', $contractVente->id)
            ->with('contractVente.exploitant')
            ->first();

        abort_if(!$pvInstallation, 404, 'PV d\'installation non trouvé.');

        return view('articles.pv-installation-print', compact('article', 'pvInstallation'));
    }

    /**
     * Store and generate PV d'Installation
     */
    public function storePvInstallation(Request $request, Article $article)
    {
        $validated = $request->validate([
            'pvn' => 'required|string|max:255',
            'date' => 'required|date',
            'participants' => 'required|string',
            'exploitant' => 'required|string|max:255',
            'reserve' => 'nullable|string',
            'emo' => 'nullable|string|max:255',
            'charbonniére' => 'nullable|string|max:255',
            'mise_en_charge' => 'nullable|string|max:255',
            'ravalement_souches' => 'nullable|string|max:255',
            'remarient' => 'nullable|string|max:255',
            'mise_en_defens' => 'nullable|string|max:255',
            'invitation_caporal' => 'nullable|string|max:255',
        ]);

        $contractVente = $article->contractVentes()->with('permisExploiter')->first();
        
        if (!$contractVente) {
            return redirect()->back()
                ->with('error', 'Un contrat de vente doit être créé.');
        }

        if (!$contractVente->permisExploiter) {
            return redirect()->back()
                ->with('error', 'Le permis d\'exploiter doit être créé avant de générer un PV d\'Installation.');
        }

        try {
            DB::beginTransaction();

            // Check if PV Installation already exists
            $pvInstallation = \App\Models\PvInstallation::where('contract_vente_id', $contractVente->id)->first();

            if ($pvInstallation) {
                $pvInstallation->update($validated);
                ActivityLogger::log('update', 'PV d\'Installation modifié', \App\Models\PvInstallation::class, $pvInstallation->id);
                $message = 'PV d\'Installation modifié avec succès.';
            } else {
                $validated['contract_vente_id'] = $contractVente->id;
                $pvInstallation = \App\Models\PvInstallation::create($validated);
                ActivityLogger::log('create', 'PV d\'Installation créé', \App\Models\PvInstallation::class, $pvInstallation->id);
                $message = 'PV d\'Installation créé avec succès. Vous pouvez maintenant importer le PV signé.';

            }

            DB::commit();

            return redirect()->route('articles.pv-installation', $article)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating/updating PV d\'Installation: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du PV d\'Installation: ' . $e->getMessage());
        }
    }
}

