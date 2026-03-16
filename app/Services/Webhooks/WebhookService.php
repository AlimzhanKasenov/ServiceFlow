<?php

namespace App\Services\Webhooks;

use App\Models\ServiceRequest;
use App\Models\Webhook;
use Illuminate\Support\Facades\Http;

/**
 * Отправка webhook
 */
class WebhookService
{
    public function sendStageChanged(
        ServiceRequest $request,
        int $fromStageId,
        int $toStageId
    ): void {

        $webhooks = Webhook::query()
            ->where('event', 'stage_changed')
            ->where('active', true)
            ->get();

        foreach ($webhooks as $webhook) {

            $payload = [

                'event' => 'stage_changed',

                'request_id' => $request->id,

                'pipeline_id' => $request->pipeline_id,

                'from_stage_id' => $fromStageId,

                'to_stage_id' => $toStageId,

                'priority' => $request->priority,

                'timestamp' => now()->toISOString()

            ];

            Http::post($webhook->url, $payload);
        }
    }
}
