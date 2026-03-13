<?php

namespace App\Actions;

use App\Models\ServiceRequest;
use App\Services\Workflow\StageManager;

class MoveRequestAction
{
    protected StageManager $stageManager;

    public function __construct(StageManager $stageManager)
    {
        $this->stageManager = $stageManager;
    }

    /**
     * Переместить заявку в новую стадию
     */
    public function execute(ServiceRequest $request, int $stageId): ServiceRequest
    {
        // проверяем возможность перехода
        $this->stageManager->validateTransition($request, $stageId);

        // меняем стадию
        $request->stage_id = $stageId;

        $request->save();

        return $request;
    }
}
