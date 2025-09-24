<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SpatieService;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ManageRolesPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spatie:manage {action} {--role=} {--permission=} {--user=} {--permissions=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage roles and permissions using Spatie package';

    protected $spatieService;

    /**
     * Create a new command instance.
     */
    public function __construct(SpatieService $spatieService)
    {
        parent::__construct();
        $this->spatieService = $spatieService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list-roles':
                $this->listRoles();
                break;
            case 'list-permissions':
                $this->listPermissions();
                break;
            case 'create-role':
                $this->createRole();
                break;
            case 'create-permission':
                $this->createPermission();
                break;
            case 'assign-role':
                $this->assignRole();
                break;
            case 'remove-role':
                $this->removeRole();
                break;
            case 'give-permission':
                $this->givePermission();
                break;
            case 'revoke-permission':
                $this->revokePermission();
                break;
            case 'sync-role-permissions':
                $this->syncRolePermissions();
                break;
            case 'user-roles':
                $this->showUserRoles();
                break;
            case 'user-permissions':
                $this->showUserPermissions();
                break;
            case 'statistics':
                $this->showStatistics();
                break;
            default:
                $this->error('Invalid action. Available actions: list-roles, list-permissions, create-role, create-permission, assign-role, remove-role, give-permission, revoke-permission, sync-role-permissions, user-roles, user-permissions, statistics');
        }
    }

    protected function listRoles()
    {
        $roles = $this->spatieService->getAllRoles();
        
        $this->info('Available Roles:');
        $this->table(['Name', 'Users Count', 'Permissions Count'], 
            $roles->map(function ($role) {
                return [
                    $role->name,
                    $role->users->count(),
                    $role->permissions->count()
                ];
            })
        );
    }

    protected function listPermissions()
    {
        $permissions = $this->spatieService->getAllPermissions();
        
        $this->info('Available Permissions:');
        $this->table(['Name', 'Guard'], 
            $permissions->map(function ($permission) {
                return [$permission->name, $permission->guard_name];
            })
        );
    }

    protected function createRole()
    {
        $roleName = $this->option('role') ?: $this->ask('Enter role name');
        $permissions = $this->option('permissions') ? explode(',', $this->option('permissions')) : [];

        if (empty($roleName)) {
            $this->error('Role name is required');
            return;
        }

        $role = $this->spatieService->createRole($roleName, $permissions);
        $this->info("Role '{$role->name}' created successfully");
    }

    protected function createPermission()
    {
        $permissionName = $this->option('permission') ?: $this->ask('Enter permission name');

        if (empty($permissionName)) {
            $this->error('Permission name is required');
            return;
        }

        $permission = $this->spatieService->createPermission($permissionName);
        $this->info("Permission '{$permission->name}' created successfully");
    }

    protected function assignRole()
    {
        $userId = $this->option('user') ?: $this->ask('Enter user ID');
        $roleName = $this->option('role') ?: $this->ask('Enter role name');

        if (empty($userId) || empty($roleName)) {
            $this->error('User ID and role name are required');
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found');
            return;
        }

        $this->spatieService->assignRoleToUser($user, $roleName);
        $this->info("Role '{$roleName}' assigned to user '{$user->name}' successfully");
    }

    protected function removeRole()
    {
        $userId = $this->option('user') ?: $this->ask('Enter user ID');
        $roleName = $this->option('role') ?: $this->ask('Enter role name');

        if (empty($userId) || empty($roleName)) {
            $this->error('User ID and role name are required');
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found');
            return;
        }

        $this->spatieService->removeRoleFromUser($user, $roleName);
        $this->info("Role '{$roleName}' removed from user '{$user->name}' successfully");
    }

    protected function givePermission()
    {
        $userId = $this->option('user') ?: $this->ask('Enter user ID');
        $permissionName = $this->option('permission') ?: $this->ask('Enter permission name');

        if (empty($userId) || empty($permissionName)) {
            $this->error('User ID and permission name are required');
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found');
            return;
        }

        $this->spatieService->givePermissionToUser($user, $permissionName);
        $this->info("Permission '{$permissionName}' given to user '{$user->name}' successfully");
    }

    protected function revokePermission()
    {
        $userId = $this->option('user') ?: $this->ask('Enter user ID');
        $permissionName = $this->option('permission') ?: $this->ask('Enter permission name');

        if (empty($userId) || empty($permissionName)) {
            $this->error('User ID and permission name are required');
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found');
            return;
        }

        $this->spatieService->revokePermissionFromUser($user, $permissionName);
        $this->info("Permission '{$permissionName}' revoked from user '{$user->name}' successfully");
    }

    protected function syncRolePermissions()
    {
        $roleName = $this->option('role') ?: $this->ask('Enter role name');
        $permissions = $this->option('permissions') ? explode(',', $this->option('permissions')) : [];

        if (empty($roleName)) {
            $this->error('Role name is required');
            return;
        }

        $this->spatieService->syncRolePermissions($roleName, $permissions);
        $this->info("Permissions synced for role '{$roleName}' successfully");
    }

    protected function showUserRoles()
    {
        $userId = $this->option('user') ?: $this->ask('Enter user ID');

        if (empty($userId)) {
            $this->error('User ID is required');
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found');
            return;
        }

        $roles = $this->spatieService->getUserRoles($user);
        
        $this->info("Roles for user '{$user->name}':");
        $this->table(['Name', 'Guard'], 
            $roles->map(function ($role) {
                return [$role->name, $role->guard_name];
            })
        );
    }

    protected function showUserPermissions()
    {
        $userId = $this->option('user') ?: $this->ask('Enter user ID');

        if (empty($userId)) {
            $this->error('User ID is required');
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found');
            return;
        }

        $permissions = $this->spatieService->getUserPermissions($user);
        
        $this->info("Permissions for user '{$user->name}':");
        $this->table(['Name', 'Guard'], 
            $permissions->map(function ($permission) {
                return [$permission->name, $permission->guard_name];
            })
        );
    }

    protected function showStatistics()
    {
        $roleStats = $this->spatieService->getRoleStatistics();
        $permissionStats = $this->spatieService->getPermissionStatistics();

        $this->info('Role Statistics:');
        $this->table(['Role', 'Users Count', 'Permissions Count'], $roleStats);

        $this->info('Permission Statistics:');
        $this->table(['Permission', 'Roles Count', 'Users Count'], $permissionStats);
    }
}