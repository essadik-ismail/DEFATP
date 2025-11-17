<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class AssignAllPermissionsToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-all-permissions {ppr : The PPR of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign all permissions to a user by PPR';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ppr = $this->argument('ppr');
        
        // Remove brackets if user accidentally included them
        $ppr = trim($ppr, '[]');
        
        // Find user by PPR
        $user = User::where('ppr', $ppr)->first();
        
        if (!$user) {
            $this->error("User with PPR '{$ppr}' not found.");
            $this->info("Available users with PPR:");
            User::select('id', 'name', 'ppr')->get()->each(function ($u) {
                $this->line("  - {$u->name} (PPR: {$u->ppr})");
            });
            return Command::FAILURE;
        }
        
        // Get all permissions
        $permissions = Permission::all();
        
        if ($permissions->isEmpty()) {
            $this->error('No permissions found in the database.');
            return Command::FAILURE;
        }
        
        // Assign all permissions to the user
        $user->givePermissionTo($permissions);
        
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $this->info("Successfully assigned {$permissions->count()} permissions to user: {$user->name} (PPR: {$ppr})");
        
        return Command::SUCCESS;
    }
}
