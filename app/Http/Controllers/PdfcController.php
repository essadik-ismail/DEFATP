<?php

namespace App\Http\Controllers;

use App\Models\Pdfc;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PdfcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Log PDFCs view
        ActivityLogger::log('view', 'Consultation de la liste des PDFCs', Pdfc::class);
        
        $query = Pdfc::with(['user', 'localisation', 'situationAdministrative']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('etat', 'like', "%{$search}%");
            });
        }
        
        // État filter (PDFC states)
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }
        
        // Date range filter
        if ($request->filled('start_date')) {
            $query->where('date_de_début', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('date_de_fin', '<=', $request->end_date);
        }
        
        // Localisation filter
        if ($request->filled('localisation_id')) {
            $query->where('localisation_id', $request->localisation_id);
        }
        
        // Situation Administrative filter
        if ($request->filled('situation_administrative_id')) {
            $query->where('situation_administrative_id', $request->situation_administrative_id);
        }
        
        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $allowedSortFields = ['id', 'date_de_début', 'date_de_fin', 'etat', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }
        
        $pdfcs = $query->paginate($perPage);
        
        // Get filter options
        $localisations = \App\Models\Localisation::orderBy('CODE')->get();
        $situationAdministratives = \App\Models\SituationAdministrative::orderBy('commune')->get();
        
        return view('pdfcs.index', compact('pdfcs', 'localisations', 'situationAdministratives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::where('is_deleted', false)->orderBy('name')->get();
        $localisations = \App\Models\Localisation::orderBy('CODE')->get();
        $situationAdministratives = \App\Models\SituationAdministrative::orderBy('commune')->get();
        
        return view('pdfcs.create', compact('users', 'localisations', 'situationAdministratives'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date_de_début' => 'required|date',
            'date_de_fin' => 'required|date|after_or_equal:date_de_début',
            'user_id' => 'nullable|exists:users,id',
            'localisation_id' => 'nullable|exists:localisations,id',
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
            'steps.*.titre' => 'nullable|string|max:255',
            'steps.*.description' => 'nullable|string',
            'steps.*.document' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ]);

        // Set default state - always start with "Non élaboré"
        $validated['etat'] = 'Non élaboré';

        // Remove steps from main validated array before creating PDFC
        $stepsInput = $request->input('steps', []);
        unset(
            $validated['steps']
        );

        $pdfc = Pdfc::create($validated);

        // Map step number to model class
        $stepModels = [
            1 => \App\Models\Etape1DiagnosticCommune::class,
            2 => \App\Models\Etape2DiagnosticSituationForestiere::class,
            3 => \App\Models\Etape3AnalyseUsagersForet::class,
            4 => \App\Models\Etape4AnalyseDegreAcceptation::class,
            5 => \App\Models\Etape5AnalyseProgrammesAnterieur::class,
            6 => \App\Models\Etape6ElaborationProjetProgramme::class,
            7 => \App\Models\Etape7ConcertationPopulation::class,
            8 => \App\Models\Etape8ValidationDPANEF::class,
            9 => \App\Models\Etape9ValidationFinalePopulation::class,
            10 => \App\Models\Etape10FinalisationPCFC::class,
            11 => \App\Models\Etape11ValidationConseilCommunal::class,
            12 => \App\Models\Etape12MiseEnOeuvrePCFC::class,
            13 => \App\Models\Etape13SuiviMiseEnOeuvre::class,
        ];

        // Create step records if any data provided
        foreach ($stepsInput as $num => $stepData) {
            $modelClass = $stepModels[$num] ?? null;
            if (!$modelClass) {
                continue;
            }

            $titre = $stepData['titre'] ?? null;
            $description = $stepData['description'] ?? null;
            $file = $request->file("steps.$num.document");

            if (!$titre && !$description && !$file) {
                continue; // nothing to save for this step
            }

            $data = [
                'pdfc_id' => $pdfc->id,
                'user_id' => auth()->id(),
                'titre' => $titre,
                'description' => $description,
            ];

            if ($file) {
                $filename = time() . "_step{$num}_" . $file->getClientOriginalName();
                $path = $file->storeAs('pdfcs/steps', $filename, 'public');
                $data['document'] = $path;
            }

            $modelClass::create($data);
        }

        ActivityLogger::logCreate(
            Pdfc::class,
            $pdfc->id,
            "PDFC créé",
            $request
        );

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'PDFC créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pdfc $pdfc): View
    {
        $pdfc->load([
            'user',
            'phases.etapes',
            'etape1DiagnosticCommune',
            'etape2DiagnosticSituationForestiere',
            'etape3AnalyseUsagersForet',
            'etape4AnalyseDegreAcceptation',
            'etape5AnalyseProgrammesAnterieur',
            'etape6ElaborationProjetProgramme',
            'etape7ConcertationPopulation',
            'etape8ValidationDPANEF',
            'etape9ValidationFinalePopulation',
            'etape10FinalisationPCFC',
            'etape11ValidationConseilCommunal',
            'etape12MiseEnOeuvrePCFC',
            'etape13SuiviMiseEnOeuvre',
        ]);
        
        ActivityLogger::logView(
            Pdfc::class,
            $pdfc->id,
            "PDFC {$pdfc->id}",
            request()
        );

        return view('pdfcs.show', compact('pdfc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pdfc $pdfc): View
    {
        $pdfc->load([
            'etape1DiagnosticCommune',
            'etape2DiagnosticSituationForestiere',
            'etape3AnalyseUsagersForet',
            'etape4AnalyseDegreAcceptation',
            'etape5AnalyseProgrammesAnterieur',
            'etape6ElaborationProjetProgramme',
            'etape7ConcertationPopulation',
            'etape8ValidationDPANEF',
            'etape9ValidationFinalePopulation',
            'etape10FinalisationPCFC',
            'etape11ValidationConseilCommunal',
            'etape12MiseEnOeuvrePCFC',
            'etape13SuiviMiseEnOeuvre',
        ]);

        $users = User::where('is_deleted', false)->orderBy('name')->get();
        $localisations = \App\Models\Localisation::orderBy('CODE')->get();
        $situationAdministratives = \App\Models\SituationAdministrative::orderBy('commune')->get();
        
        return view('pdfcs.edit', compact('pdfc', 'users', 'localisations', 'situationAdministratives'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pdfc $pdfc): RedirectResponse
    {
        $validated = $request->validate([
            'date_de_début' => 'required|date',
            'date_de_fin' => 'required|date|after_or_equal:date_de_début',
            'user_id' => 'nullable|exists:users,id',
            'localisation_id' => 'nullable|exists:localisations,id',
            'situation_administrative_id' => 'nullable|exists:situation_administratives,id',
            'steps.*.titre' => 'nullable|string|max:255',
            'steps.*.description' => 'nullable|string',
            'steps.*.document' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ]);

        $oldValues = $pdfc->only(['date_de_début', 'date_de_fin', 'etat', 'user_id', 'localisation_id', 'situation_administrative_id']);
        $oldEtat = $pdfc->etat;
        
        // Don't update etat from form - it's managed automatically
        // Keep the current etat value
        $validated['etat'] = $pdfc->etat;
        
        $pdfc->update($validated);

        // Handle steps update / creation
        $stepsInput = $request->input('steps', []);

        $stepModels = [
            1 => \App\Models\Etape1DiagnosticCommune::class,
            2 => \App\Models\Etape2DiagnosticSituationForestiere::class,
            3 => \App\Models\Etape3AnalyseUsagersForet::class,
            4 => \App\Models\Etape4AnalyseDegreAcceptation::class,
            5 => \App\Models\Etape5AnalyseProgrammesAnterieur::class,
            6 => \App\Models\Etape6ElaborationProjetProgramme::class,
            7 => \App\Models\Etape7ConcertationPopulation::class,
            8 => \App\Models\Etape8ValidationDPANEF::class,
            9 => \App\Models\Etape9ValidationFinalePopulation::class,
            10 => \App\Models\Etape10FinalisationPCFC::class,
            11 => \App\Models\Etape11ValidationConseilCommunal::class,
            12 => \App\Models\Etape12MiseEnOeuvrePCFC::class,
            13 => \App\Models\Etape13SuiviMiseEnOeuvre::class,
        ];

        foreach ($stepModels as $num => $modelClass) {
            $stepData = $stepsInput[$num] ?? [];
            $titre = $stepData['titre'] ?? null;
            $description = $stepData['description'] ?? null;
            $file = $request->file("steps.$num.document");

            /** @var \Illuminate\Database\Eloquent\Model|null $existing */
            $existing = $modelClass::where('pdfc_id', $pdfc->id)->first();

            if (!$titre && !$description && !$file) {
                // If there is no new data and no existing step, nothing to do.
                // (We keep existing data unless user explicitly edits it.)
                continue;
            }

            if ($existing) {
                $existing->titre = $titre;
                $existing->description = $description;

                if ($file) {
                    if ($existing->document && Storage::disk('public')->exists($existing->document)) {
                        Storage::disk('public')->delete($existing->document);
                    }
                    $filename = time() . "_step{$num}_" . $file->getClientOriginalName();
                    $path = $file->storeAs('pdfcs/steps', $filename, 'public');
                    $existing->document = $path;
                }

                $existing->user_id = $existing->user_id ?? auth()->id();
                $existing->save();
            } else {
                $data = [
                    'pdfc_id' => $pdfc->id,
                    'user_id' => auth()->id(),
                    'titre' => $titre,
                    'description' => $description,
                ];

                if ($file) {
                    $filename = time() . "_step{$num}_" . $file->getClientOriginalName();
                    $path = $file->storeAs('pdfcs/steps', $filename, 'public');
                    $data['document'] = $path;
                }

                $modelClass::create($data);
            }
        }
        
        // Auto-transition: If dates are set and state is "Non élaboré", move to "élaboré"
        if ($pdfc->date_de_début && $pdfc->date_de_fin && $pdfc->etat == 'Non élaboré') {
            $pdfc->update(['etat' => 'élaboré']);
            ActivityLogger::log('update', "PDFC {$pdfc->id} : transition automatique vers 'élaboré'", Pdfc::class, $pdfc->id, null, $request);
        }

        $changes = array_diff_assoc($pdfc->only(['date_de_début', 'date_de_fin', 'etat', 'user_id', 'localisation_id', 'situation_administrative_id']), $oldValues);
        
        ActivityLogger::logUpdate(
            Pdfc::class,
            $pdfc->id,
            "PDFC {$pdfc->id}",
            $changes,
            $request
        );

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', 'PDFC mis à jour avec succès.');
    }

    /**
     * Transition PDFC to next state in cycle de vie.
     */
    public function transitionState(Request $request, Pdfc $pdfc): RedirectResponse
    {
        if (!$pdfc->canTransitionToNextState()) {
            return redirect()->route('pdfcs.show', $pdfc)
                ->with('error', 'Impossible de passer à l\'état suivant. Vérifiez que toutes les conditions sont remplies.');
        }

        $nextState = $pdfc->getNextState();
        $oldState = $pdfc->etat;
        
        $pdfc->update(['etat' => $nextState]);

        ActivityLogger::log('update', "PDFC {$pdfc->id} : transition de '{$oldState}' à '{$nextState}'", Pdfc::class, $pdfc->id, null, $request);

        return redirect()->route('pdfcs.show', $pdfc)
            ->with('success', "PDFC passé à l'état '{$nextState}' avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pdfc $pdfc): RedirectResponse
    {
        $pdfcId = $pdfc->id;
        $pdfc->delete();

        ActivityLogger::logDelete(
            Pdfc::class,
            $pdfcId,
            "PDFC {$pdfcId}",
            request()
        );

        return redirect()->route('pdfcs.index')
            ->with('success', 'PDFC supprimé avec succès.');
    }
}
