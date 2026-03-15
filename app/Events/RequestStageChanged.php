<?php

namespace App\Events;

use App\Models\ServiceRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Событие смены стадии заявки
 */
class RequestStageChanged
{
    use Dispatchable, SerializesModels;

    public ServiceRequest $request;

    public int $fromStageId;
    public int $toStageId;

    public function __construct(
        ServiceRequest $request,
        int $fromStageId,
        int $toStageId
    ) {
        $this->request = $request;
        $this->fromStageId = $fromStageId;
        $this->toStageId = $toStageId;
    }
}
