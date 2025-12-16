<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ArchiveController extends Controller
{
    public function index(Request $request): View
    {
        $archives = Archive::query()
            ->withCount('documents')
            // Global search
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('numero', 'like', '%' . $request->search . '%')
                        ->orWhere('expediteur', 'like', '%' . $request->search . '%')
                        ->orWhere('departement', 'like', '%' . $request->search . '%')
                        ->orWhere('service', 'like', '%' . $request->search . '%')
                        ->orWhere('object', 'like', '%' . $request->search . '%');
                });
            })
            // Specific filters
            ->when($request->filled('numero'), function ($query) use ($request) {
                $query->where('numero', 'like', '%' . $request->numero . '%');
            })
            ->when($request->filled('expediteur'), function ($query) use ($request) {
                $query->where('expediteur', 'like', '%' . $request->expediteur . '%');
            })
            ->when($request->filled('departement'), function ($query) use ($request) {
                $query->where('departement', $request->departement);
            })
            ->when($request->filled('service'), function ($query) use ($request) {
                $query->where('service', $request->service);
            })
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('date', $request->date);
            })
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // For select options in filters
        $departements = [
            "l’Economie Forestièr",
            "l’Animation Territoriale et du Partenariat",
        ];

        $services = [
            'FUP et de l\'accueil du public',
            'des études et des l\'inventaire forestier national',
            'organisation de l\'exploitation forestiére',
            'la valorisation des produit forstiers',
            'animation territoriale et partenariat',
            'parcours forestiers et sylvopastoraux',
        ];

        return view('archives.index', compact('archives', 'departements', 'services'));
    }

    public function create(): View
    {
        return view('archives.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['nullable', 'date'],
            'numero' => ['nullable', 'string', 'max:255'],
            'expediteur' => ['nullable', 'string', 'max:255'],
            'num_expediteur' => ['nullable', 'string', 'max:255'],
            'date_expediteur' => ['nullable', 'date'],
            'object' => ['nullable', 'string', 'max:255'],
            'departement' => [
                'nullable',
                'string',
                Rule::in([
                    "l’Economie Forestièr",
                    "l’Animation Territoriale et du Partenariat",
                ]),
            ],
            'service' => [
                'nullable',
                'string',
                Rule::in([
                    "FUP et de l'accueil du public",
                    "des études et des l'inventaire forestier national",
                    "organisation de l'exploitation forestiére",
                    "la valorisation des produit forstiers",
                    "animation territoriale et partenariat",
                    "parcours forestiers et sylvopastoraux",
                ]),
            ],
            'placement' => ['nullable', 'string', 'max:255'],
            'suite_file' => ['nullable', 'file', 'max:10240'],
            'document_files' => ['nullable', 'array'],
            'document_files.*' => ['file', 'max:10240'],
        ]);

        $archive = Archive::create($data);

        if ($request->hasFile('suite_file')) {
            $suitePath = $request->file('suite_file')->store('archives/suites', 'public');
            $archive->suite = $suitePath;
            $archive->save();
        }

        if ($request->hasFile('document_files')) {
            foreach ($request->file('document_files') as $file) {
                $this->storeDocument($archive, $file, null);
            }
        }

        return redirect()->route('archives.index')->with('success', 'Archive créée avec succès.');
    }

    public function show(Archive $archive): View
    {
        $archive->load('documents');

        return view('archives.show', compact('archive'));
    }

    public function edit(Archive $archive): View
    {
        $archive->load('documents');

        return view('archives.edit', compact('archive'));
    }

    public function update(Request $request, Archive $archive): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['nullable', 'date'],
            'numero' => ['nullable', 'string', 'max:255'],
            'expediteur' => ['nullable', 'string', 'max:255'],
            'num_expediteur' => ['nullable', 'string', 'max:255'],
            'date_expediteur' => ['nullable', 'date'],
            'object' => ['nullable', 'string', 'max:255'],
            'departement' => [
                'nullable',
                'string',
                Rule::in([
                    "l’Economie Forestièr",
                    "l’Animation Territoriale et du Partenariat",
                ]),
            ],
            'service' => [
                'nullable',
                'string',
                Rule::in([
                    "FUP et de l'accueil du public",
                    "des études et des l'inventaire forestier national",
                    "organisation de l'exploitation forestiére",
                    "la valorisation des produit forstiers",
                    "animation territoriale et partenariat",
                    "parcours forestiers et sylvopastoraux",
                ]),
            ],
            'placement' => ['nullable', 'string', 'max:255'],
            'suite_file' => ['nullable', 'file', 'max:10240'],
            'document_files' => ['nullable', 'array'],
            'document_files.*' => ['file', 'max:10240'],
        ]);

        $archive->update($data);

        if ($request->hasFile('suite_file')) {
            // Remove old suite file if exists
            if ($archive->suite) {
                Storage::disk('public')->delete($archive->suite);
            }

            $suitePath = $request->file('suite_file')->store('archives/suites', 'public');
            $archive->suite = $suitePath;
            $archive->save();
        }

        if ($request->hasFile('document_files')) {
            foreach ($request->file('document_files') as $file) {
                $this->storeDocument($archive, $file, null);
            }
        }

        return redirect()->route('archives.edit', $archive)->with('success', 'Archive mise à jour.');
    }

    public function destroy(Archive $archive): RedirectResponse
    {
        foreach ($archive->documents as $document) {
            if ($document->path) {
                Storage::disk('public')->delete($document->path);
            }
        }

        if ($archive->suite) {
            Storage::disk('public')->delete($archive->suite);
        }

        $archive->delete();

        return redirect()->route('archives.index')->with('success', 'Archive supprimée.');
    }

    protected function storeDocument(Archive $archive, $file, ?string $name = null): Document
    {
        $storedPath = $file->store('archives', 'public');

        return $archive->documents()->create([
            'name' => $name ?: $file->getClientOriginalName(),
            'path' => $storedPath,
            'file' => $file->getClientOriginalName(),
        ]);
    }
}

