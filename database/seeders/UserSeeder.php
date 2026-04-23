<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'ppr'      => 'ADM001',
                'name'     => 'Administrateur',
                // 'email'    => 'admin@anef.ma',
                'role'     => UserRole::Admin,
                'spatie'   => UserRole::Admin->value,
                'password' => 'password',
            ],
            [
                'ppr'      => 'CEN001',
                'name'     => 'Agent Central',
                // 'email'    => 'central@anef.ma',
                'role'     => UserRole::Central,
                'spatie'   => UserRole::Central->value,
                'password' => 'password',
            ],
            [
                'ppr'      => 'DRN001',
                'name'     => 'Directeur RANEF',
                // 'email'    => 'dranef@anef.ma',
                'role'     => UserRole::Dranef,
                'spatie'   => UserRole::Dranef->value,
                'password' => 'password',
            ],
            [
                'ppr'      => 'DPN001',
                'name'     => 'Directeur PANEF',
                // 'email'    => 'dpanef@anef.ma',
                'role'     => UserRole::Dpanef,
                'spatie'   => UserRole::Dpanef->value,
                'password' => 'password',
            ],
            [
                'ppr'      => 'ZDT001',
                'name'     => 'Chef de Zone DTF',
                // 'email'    => 'zdtf@anef.ma',
                'role'     => UserRole::Zdtf,
                'spatie'   => UserRole::Zdtf->value,
                'password' => 'password',
            ],
            [
                'ppr'      => 'CPF001',
                'name'     => 'Agent CPF',
                // 'email'    => 'cpf@anef.ma',
                'role'     => UserRole::Cpf,
                'spatie'   => UserRole::Cpf->value,
                'password' => 'password',
            ],
            [
                'ppr'      => 'ZDP001',
                'name'     => 'Chef Zone DTF/PANEF',
                //  'email'    => 'zdtfdpanef@anef.ma',
                'role'     => UserRole::ZdtfDpanef,
                'spatie'   => UserRole::ZdtfDpanef->value,
                'password' => 'password',
            ],
            [
                'ppr'      => 'BRI001',
                'name'     => 'Agent Brigade',
                // 'email'    => 'brigade@anef.ma',
                'role'     => UserRole::Brigade,
                'spatie'   => UserRole::Brigade->value,
                'password' => 'password',
            ],
            [
                'ppr'      => 'DFP001',
                'name'     => 'Agent DFP',
                // 'email'    => 'dfp@anef.ma',
                'role'     => UserRole::Dfp,
                'spatie'   => UserRole::Dfp->value,
                'password' => 'password',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['ppr' => $data['ppr']],
                [
                    'name'     => $data['name'],
                    // 'email'    => $data['email'],
                    'role'     => $data['role'],
                    'password' => Hash::make($data['password']),
                ]
            );

            // Sync the Spatie role (idempotent — safe to re-run)
            $user->syncRoles([$data['spatie']]);
        }

        $this->command->info('✓ Users seeded: ' . count($users) . ' accounts.');
    }
}
