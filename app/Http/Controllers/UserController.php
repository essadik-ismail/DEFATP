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
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\ActivityLogger;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Log users view
        ActivityLogger::log('view', 'Consultation de la liste des utilisateurs', User::class);
        
        $query = User::with('roles');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ppr', 'like', "%{$search}%");
            });
        }
        
        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
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
        
        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $allowedSortFields = ['id', 'name', 'email', 'ppr', 'is_deleted', 'created_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }
        
        $users = $query->paginate($perPage)->withQueryString();
        
        // Calculate statistics
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_deleted', false)->count(),
            'inactive' => User::where('is_deleted', true)->count(),
        ];
        
        // Optimize: Load only necessary role fields
        $roles = Role::select('id', 'name')->orderBy('name')->get();

        return view('users.index', compact('users', 'roles', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Optimize: Load only necessary role fields
        $roles = Role::select('id', 'name')->orderBy('name')->get();
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
        $allRoles = Role::orderBy('name')->get();
        $allPermissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });
        
        // Log the activity
        ActivityLogger::logView(
            User::class,
            $user->id,
            "Utilisateur {$user->name}",
            request()
        );
        
        return view('users.show', compact('user', 'allRoles', 'allPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        // Optimize: Load only necessary role fields
        $roles = Role::select('id', 'name')->orderBy('name')->get();
        
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
     * Assign roles and permissions to a user.
     */
    public function assignRolesPermissions(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);
        
        // Sync roles
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
            ActivityLogger::log('update', "Rôles assignés à l'utilisateur {$user->name}", User::class, $user->id, $request);
        } else {
            $user->roles()->detach();
            ActivityLogger::log('update', "Tous les rôles retirés de l'utilisateur {$user->name}", User::class, $user->id, $request);
        }
        
        // Sync direct permissions
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
            ActivityLogger::log('update', "Permissions assignées à l'utilisateur {$user->name}", User::class, $user->id, $request);
        } else {
            $user->permissions()->detach();
            ActivityLogger::log('update', "Toutes les permissions directes retirées de l'utilisateur {$user->name}", User::class, $user->id, $request);
        }
        
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        return redirect()->route('users.show', $user)
            ->with('success', 'Rôles et permissions mis à jour avec succès.');
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
