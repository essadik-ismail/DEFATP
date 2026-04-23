<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Services\AlertService;
use Illuminate\Console\Command;

class RunDailyAlertChecks extends Command
{
    protected $signature   = 'alerts:daily-check';
    protected $description = 'Run daily alert checks: contract expirations, overdue payments, colportage volumes, etc.';

    public function handle(AlertService $alertService): int
    {
        $before = Alert::active()->count();

        $this->info('[alerts:daily-check] Démarrage des vérifications...');
        $alertService->runDailyChecks();

        $after    = Alert::active()->count();
        $critical = Alert::active()->critical()->count();
        $diff     = $after - $before;
        $sign     = $diff >= 0 ? '+' : '';

        $this->info("[alerts:daily-check] Terminé. Actives: {$after} ({$sign}{$diff} depuis hier). Critiques: {$critical}.");

        return Command::SUCCESS;
    }
}
