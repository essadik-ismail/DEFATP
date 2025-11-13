<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        // If this is an AJAX request for DataTables
        if ($request->ajax()) {
            return $this->getUsersData($request);
        }

        $roles = Role::all();
        return view('users.index', compact('roles'));
    }

    /**
     * Get users data for DataTables (AJAX)
     */
    private function getUsersData(Request $request): JsonResponse
    {
        $query = User::with('roles');

        // Get DataTables parameters
        $draw = $request->get('draw');
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $searchValue = $request->get('search')['value'] ?? '';
        $orderColumn = $request->get('order')[0]['column'] ?? 0;
        $orderDir = $request->get('order')[0]['dir'] ?? 'desc';

        // Column mapping for DataTables (0=id, 1=name, 2=email, 3=ppr, 4=roles, 5=is_deleted, 6=created_at, 7=actions)
        $columnMap = [
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'ppr',
            4 => 'created_at', // roles cannot be ordered, default to created_at
            5 => 'is_deleted',
            6 => 'created_at',
            7 => 'created_at', // actions cannot be ordered, default to created_at
        ];
        $orderColumnName = $columnMap[$orderColumn] ?? 'created_at';

        // Search functionality
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('email', 'like', "%{$searchValue}%")
                  ->orWhere('ppr', 'like', "%{$searchValue}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_deleted', false);
            } elseif ($request->status === 'inactive') {
                $query->where('is_deleted', true);
            }
        }

        // Get total records before filtering
        $totalRecords = User::count();
        $filteredRecords = $query->count();

        // Apply ordering and pagination
        $users = $query->orderBy($orderColumnName, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // Format data for DataTables
        $data = [];
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->map(function($role) {
                return '<span class="badge bg-primary me-1">' . e($role) . '</span>';
            })->implode('');

            $status = $user->is_deleted 
                ? '<span class="badge bg-danger">Inactif</span>'
                : '<span class="badge bg-success">Actif</span>';

            $actions = view('users.partials.actions', compact('user'))->render();

            $data[] = [
                $user->id,
                '<div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <span class="text-primary fw-semibold">' . strtoupper(substr($user->name, 0, 1)) . '</span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <div class="fw-semibold">' . e($user->name) . '</div>
                    </div>
                </div>',
                e($user->email),
                '<span class="badge bg-info">' . e($user->ppr) . '</span>',
                $roles ?: '<span class="text-muted">Aucun rôle</span>',
                $status,
                $user->created_at->format('d/m/Y H:i'),
                $actions,
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Hash password
            $validated['password'] = Hash::make($validated['password']);
            
            // Create user
            $user = User::create($validated);
            
            // Assign roles if specified
            if (!empty($validated['roles'])) {
                $user->syncRoles($validated['roles']);
            }
            
            // Log the activity
            ActivityLogger::logCreate(
                User::class,
                $user->id,
                "Utilisateur {$user->name}",
                $request
            );
            
            // Check if this is an AJAX request
            if ($request->ajax()) {
                $isCreateAndNext = $request->input('action') === 'create_and_next';
                
                return response()->json([
                    'success' => true,
                    'message' => 'Utilisateur créé avec succès.',
                    'create_and_next' => $isCreateAndNext,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'ppr' => $user->ppr
                    ]
                ]);
            }
            
            // Handle create_and_next action for regular requests
            if ($request->input('action') === 'create_and_next') {
                return redirect()->route('users.create')
                    ->with('success', 'Utilisateur créé avec succès. Vous pouvez créer un autre utilisateur.');
            }
            
            return redirect()->route('users.index')
                ->with('success', 'Utilisateur créé avec succès.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'utilisateur: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'utilisateur: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->load('roles.permissions');
        
        // Log the activity
        ActivityLogger::logView(
            User::class,
            $user->id,
            "Utilisateur {$user->name}",
            request()
        );
        
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();
        
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        
        // Hash password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Store old values for logging
        $oldValues = $user->only(['name', 'email', 'ppr']);
        
        // Update user
        $user->update($validated);
        
        // Sync roles if specified
        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }
        
        // Log the activity
        $changes = array_diff_assoc($user->only(['name', 'email', 'ppr']), $oldValues);
        ActivityLogger::logUpdate(
            User::class,
            $user->id,
            "Utilisateur {$user->name}",
            $changes,
            $request
        );
        
        return redirect()->route('users.show', $user)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $userName = $user->name;
        
        // Log the activity before deletion
        ActivityLogger::logDelete(
            User::class,
            $user->id,
            "Utilisateur {$userName}",
            request()
        );
        
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Toggle user status (active/inactive).
     */
    public function toggleStatus(User $user): JsonResponse
    {
        $oldStatus = $user->is_deleted ? 'Inactif' : 'Actif';
        $newStatus = $user->is_deleted ? 'Actif' : 'Inactif';
        
        $user->update(['is_deleted' => !$user->is_deleted]);
        
        // Log the status change
        ActivityLogger::logStatusChange(
            User::class,
            $user->id,
            "Utilisateur {$user->name}",
            $oldStatus,
            $newStatus,
            request()
        );
        
        return response()->json([
            'success' => true,
            'message' => "Statut de l'utilisateur changé avec succès.",
            'new_status' => $newStatus
        ]);
    }

    /**
     * Export users to CSV.
     */
    public function export(Request $request)
    {
        $query = User::with('roles');
        
        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('ppr', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_deleted', false);
            } else {
                $query->where('is_deleted', true);
            }
        }
        
        $users = $query->get();
        
        $filename = 'users_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Nom',
                'Email',
                'PPR',
                'Rôles',
                'Statut',
                'Date de création'
            ]);
            
            // Add data
            foreach ($users as $user) {
                $roles = $user->roles->pluck('name')->implode(', ');
                $status = $user->is_deleted ? 'Inactif' : 'Actif';
                
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->ppr,
                    $roles,
                    $status,
                    $user->created_at->format('d/m/Y H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        // Log the export activity
        ActivityLogger::logExport(
            'Utilisateurs',
            'CSV',
            request()
        );
        
        return response()->stream($callback, 200, $headers);
    }
}
