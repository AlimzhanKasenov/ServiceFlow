<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SLA\SLAService;

/**
 * Проверка SLA заявок
 */
class CheckSLA extends Command
{
    protected $signature = 'sla:check';

    protected $description = 'Проверка SLA заявок';

    public function handle(SLAService $slaService)
    {
        $slaService->check();

        $this->info('SLA проверены');
    }
}
