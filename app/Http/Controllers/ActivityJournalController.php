<?php

namespace App\Http\Controllers;

use App\Models\ActivityJournal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivityJournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $activityJournals = ActivityJournal::where('user_id', Auth::id())
            ->orderBy('Date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('activity-journals.index', compact('activityJournals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('activity-journals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'Objet' => 'required|string|max:255',
            'Date' => 'required|date',
            'Lieu' => 'nullable|string|max:255',
            'Participants' => 'nullable|string',
            'Description' => 'nullable|string',
            'Recommandations' => 'nullable|string',
            'Conclusion' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        
        ActivityJournal::create($validated);

        return redirect()->route('auth.profile')->with('success', 'Entrée du journal d\'activités créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityJournal $activityJournal): View
    {
        // Ensure the user can only view their own journals
        if ($activityJournal->user_id !== Auth::id()) {
            abort(403);
        }

        return view('activity-journals.show', compact('activityJournal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityJournal $activityJournal): View
    {
        // Ensure the user can only edit their own journals
        if ($activityJournal->user_id !== Auth::id()) {
            abort(403);
        }

        return view('activity-journals.edit', compact('activityJournal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivityJournal $activityJournal): RedirectResponse
    {
        // Ensure the user can only update their own journals
        if ($activityJournal->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'Objet' => 'required|string|max:255',
            'Date' => 'required|date',
            'Lieu' => 'nullable|string|max:255',
            'Participants' => 'nullable|string',
            'Description' => 'nullable|string',
            'Recommandations' => 'nullable|string',
            'Conclusion' => 'nullable|string',
        ]);

        $activityJournal->update($validated);

        return redirect()->route('auth.profile')->with('success', 'Entrée du journal d\'activités mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityJournal $activityJournal): RedirectResponse
    {
        // Ensure the user can only delete their own journals
        if ($activityJournal->user_id !== Auth::id()) {
            abort(403);
        }

        $activityJournal->delete();

        return redirect()->route('auth.profile')->with('success', 'Entrée du journal d\'activités supprimée avec succès.');
    }
}
