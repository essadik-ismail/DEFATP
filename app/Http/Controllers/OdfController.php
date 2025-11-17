<?php

namespace App\Http\Controllers;

use App\Models\Odf;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Log ODFs view
        ActivityLogger::log('view', 'Consultation de la liste des ODFs', Odf::class);
        
        $query = Odf::with('user');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('présidente', 'like', "%{$search}%")
                  ->orWhere('vice_présidente', 'like', "%{$search}%")
                  ->orWhere('trésorière', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            switch ($request->get('status')) {
                case 'active':
                    $query->whereNull('deleted_at');
                    break;
                case 'deleted':
                    $query->onlyTrashed();
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }
        
        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $allowedSortFields = ['id', 'présidente', 'vice_présidente', 'trésorière', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }
        
        $odfs = $query->paginate($perPage);
        
        // Calculate statistics
        $stats = [
            'total' => Odf::count(),
            'active' => Odf::whereNull('deleted_at')->count(),
            'deleted' => Odf::onlyTrashed()->count(),
            'recent' => Odf::where('created_at', '>=', now()->subDays(30))->count(),
        ];
        
        return view('odfs.index', compact('odfs', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
