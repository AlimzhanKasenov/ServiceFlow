<?php

namespace App\Events;

use App\Models\ServiceRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class RequestStageChanged
 *
 * Domain Event: смена стадии заявки.
 *
 * Генерируется при любом перемещении заявки между стадиями.
 *
 * Используется для:
 *
 * - AutomationEngine (роботы)
 * - Webhooks
 * - Notifications
 * - SLA watchers
 * - Analytics
 *
 * Пример:
 *
 * Request #15
 * from_stage: New
 * to_stage: In Progress
 */
class RequestStageChanged
{
    use Dispatchable, SerializesModels;

    /**
     * Заявка
     */
    public ServiceRequest $request;

    /**
     * Стадия из которой перешли
     */
    public int $fromStageId;

    /**
     * Стадия в которую перешли
     */
    public int $toStageId;

    /**
     * Конструктор события
     */
    public function __construct(
        ServiceRequest $request,
        int $fromStageId,
        int $toStageId
    ) {
        $this->request = $request;
        $this->fromStageId = $fromStageId;
        $this->toStageId = $toStageId;
    }

    /**
     * Получить pipeline заявки
     */
    public function pipelineId(): int
    {
        return $this->request->pipeline_id;
    }

    /**
     * Получить ID заявки
     */
    public function requestId(): int
    {
        return $this->request->id;
    }
}
