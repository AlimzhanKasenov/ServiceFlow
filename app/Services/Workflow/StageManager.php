<?php

namespace App\Services\Workflow;

use App\Models\ServiceRequest;
use App\Models\StageTransition;
use RuntimeException;

/**
 * Class StageManager
 *
 * Отвечает за проверку переходов между стадиями workflow.
 */
class StageManager
{
    /**
     * Проверить разрешён ли переход
     */
    public function validateTransition(ServiceRequest $request, int $toStageId): void
    {
        $exists = StageTransition::query()
            ->where('pipeline_id', $request->pipeline_id)
            ->where('from_stage_id', $request->stage_id)
            ->where('to_stage_id', $toStageId)
            ->exists();

        if (! $exists) {
            throw new RuntimeException('Переход между стадиями не найден');
        }
    }
}
