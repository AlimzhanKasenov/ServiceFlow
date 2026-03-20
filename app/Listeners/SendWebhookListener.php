<?php

namespace App\Listeners;

use App\Events\RequestStageChanged;
use Illuminate\Support\Facades\Log;

class SendWebhookListener
{
    /**
     * Handle the event.
     */
    public function handle(RequestStageChanged $event): void
    {
        Log::info('Webhook: стадия изменена', [
            'request_id' => $event->request->id,
            'from' => $event->fromStageId,
            'to' => $event->toStageId,
        ]);
    }
}
