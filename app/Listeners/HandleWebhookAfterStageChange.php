<?php

namespace App\Listeners;

use App\Events\RequestStageChanged;
use App\Services\Webhooks\WebhookService;
use Illuminate\Support\Facades\Log;

/**
 * Class HandleWebhookAfterStageChange
 *
 * Отвечает за отправку webhook после смены стадии заявки.
 */
class HandleWebhookAfterStageChange
{
    protected WebhookService $service;

    public function __construct(WebhookService $service)
    {
        $this->service = $service;
    }

    /**
     * Обработка события смены стадии
     */
    public function handle(RequestStageChanged $event): void
    {
        /**
         * 🔍 ЛОГ ДЛЯ ПРОВЕРКИ (временно)
         */
        Log::info('Webhook listener сработал', [
            'request_id' => $event->request->id,
            'from' => $event->fromStageId,
            'to' => $event->toStageId,
        ]);

        /**
         * Основная логика (у тебя уже есть 👍)
         */
        $this->service->sendStageChanged(
            $event->request,
            $event->fromStageId,
            $event->toStageId
        );
    }
}
