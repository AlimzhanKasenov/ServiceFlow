<?php

namespace App\Listeners;

use App\Events\RequestStageChanged;
use App\Services\Webhooks\WebhookService;

class HandleWebhookAfterStageChange
{
    protected WebhookService $service;

    public function __construct(WebhookService $service)
    {
        $this->service = $service;
    }

    public function handle(RequestStageChanged $event): void
    {
        $this->service->sendStageChanged(
            $event->request,
            $event->fromStageId,
            $event->toStageId
        );
    }
}
