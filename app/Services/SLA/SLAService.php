<?php

namespace App\Services\SLA;

use App\Models\ServiceRequest;
use Carbon\Carbon;

/**
 * Class SLAService
 *
 * Сервис проверки SLA заявок.
 */
class SLAService
{
    /**
     * Проверить все заявки с SLA
     */
    public function check(): void
    {
        $now = Carbon::now();

        ServiceRequest::query()
            ->whereNotNull('sla_due_at')
            ->where('sla_breached', false)
            ->where('sla_due_at', '<', $now)
            ->chunkById(100, function ($requests) {
                foreach ($requests as $request) {
                    $request->sla_breached = true;
                    $request->save();
                }
            });
    }
}
