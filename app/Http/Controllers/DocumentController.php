<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request, Archive $archive): RedirectResponse
    {
        $data = $request->validate([
            'document_files' => ['required', 'array'],
            'document_files.*' => ['file', 'max:10240'],
        ]);

        foreach ($request->file('document_files') as $file) {
            $storedPath = $file->store('archives', 'public');

            $archive->documents()->create([
                'name' => $file->getClientOriginalName(),
                'path' => $storedPath,
                'file' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->route('archives.edit', $archive)->with('success', 'Document ajouté.');
    }

    public function destroy(Archive $archive, Document $document): RedirectResponse
    {
        if ($document->archive_id !== $archive->id) {
            abort(404);
        }

        if ($document->path) {
            Storage::disk('public')->delete($document->path);
        }

        $document->delete();

        return redirect()->route('archives.edit', $archive)->with('success', 'Document supprimé.');
    }
}

