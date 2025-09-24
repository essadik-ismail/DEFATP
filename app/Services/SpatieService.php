<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SpatieService
{
    /**
     * Create a new role with permissions.
     */
    public function createRole(string $name, array $permissions = []): Role
    {
        $role = Role::firstOrCreate(['name' => $name]);
        
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }
        
        return $role;
    }

    /**
     * Create a new permission.
     */
    public function createPermission(string $name, string $guard = 'web'): Permission
    {
        return Permission::firstOrCreate(['name' => $name, 'guard_name' => $guard]);
    }

    /**
     * Assign role to user.
     */
    public function assignRoleToUser(User $user, string $roleName): void
    {
        $user->assignRole($roleName);
    }

    /**
     * Remove role from user.
     */
    public function removeRoleFromUser(User $user, string $roleName): void
    {
        $user->removeRole($roleName);
    }

    /**
     * Give permission to user.
     */
    public function givePermissionToUser(User $user, string $permissionName): void
    {
        $user->givePermissionTo($permissionName);
    }

    /**
     * Revoke permission from user.
     */
    public function revokePermissionFromUser(User $user, string $permissionName): void
    {
        $user->revokePermissionTo($permissionName);
    }

    /**
     * Check if user has permission.
     */
    public function userHasPermission(User $user, string $permissionName): bool
    {
        return $user->can($permissionName);
    }

    /**
     * Check if user has role.
     */
    public function userHasRole(User $user, string $roleName): bool
    {
        return $user->hasRole($roleName);
    }

    /**
     * Get all roles.
     */
    public function getAllRoles()
    {
        return Role::all();
    }

    /**
     * Get all permissions.
     */
    public function getAllPermissions()
    {
        return Permission::all();
    }

    /**
     * Get user roles.
     */
    public function getUserRoles(User $user)
    {
        return $user->roles;
    }

    /**
     * Get user permissions (direct and through roles).
     */
    public function getUserPermissions(User $user)
    {
        return $user->getAllPermissions();
    }

    /**
     * Sync user roles.
     */
    public function syncUserRoles(User $user, array $roleNames): void
    {
        $user->syncRoles($roleNames);
    }

    /**
     * Sync role permissions.
     */
    public function syncRolePermissions(string $roleName, array $permissionNames): void
    {
        $role = Role::findByName($roleName);
        $role->syncPermissions($permissionNames);
    }

    /**
     * Get users with specific role.
     */
    public function getUsersWithRole(string $roleName)
    {
        return User::role($roleName)->get();
    }

    /**
     * Get users with specific permission.
     */
    public function getUsersWithPermission(string $permissionName)
    {
        return User::permission($permissionName)->get();
    }

    /**
     * Create multiple permissions at once.
     */
    public function createPermissions(array $permissions): array
    {
        $createdPermissions = [];
        
        foreach ($permissions as $permission) {
            $createdPermissions[] = $this->createPermission($permission);
        }
        
        return $createdPermissions;
    }

    /**
     * Create multiple roles at once.
     */
    public function createRoles(array $roles): array
    {
        $createdRoles = [];
        
        foreach ($roles as $roleName => $permissions) {
            $createdRoles[] = $this->createRole($roleName, $permissions);
        }
        
        return $createdRoles;
    }

    /**
     * Get role statistics.
     */
    public function getRoleStatistics(): array
    {
        $roles = Role::withCount('users')->get();
        
        return $roles->map(function ($role) {
            return [
                'name' => $role->name,
                'users_count' => $role->users_count,
                'permissions_count' => $role->permissions->count(),
            ];
        })->toArray();
    }

    /**
     * Get permission statistics.
     */
    public function getPermissionStatistics(): array
    {
        $permissions = Permission::withCount(['roles', 'users'])->get();
        
        return $permissions->map(function ($permission) {
            return [
                'name' => $permission->name,
                'roles_count' => $permission->roles_count,
                'users_count' => $permission->users_count,
            ];
        })->toArray();
    }
}
