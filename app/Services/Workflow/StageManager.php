<?php

namespace App\Services\Workflow;

use App\Models\ServiceRequest;
use App\Models\StageTransition;
use RuntimeException;

/**
 * Class StageManager
 *
 * Управляет переходами между стадиями workflow.
 *
 * Отвечает за:
 * - поиск transition
 * - валидацию перехода
 */
class StageManager
{
    protected TransitionValidator $validator;

    public function __construct(TransitionValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Проверить разрешён ли переход
     */
    public function validateTransition(ServiceRequest $request, int $toStageId): void
    {
        /**
         * Ищем переход
         */
        $transition = StageTransition::query()
            ->where('pipeline_id', $request->pipeline_id)
            ->where('from_stage_id', $request->stage_id)
            ->where('to_stage_id', $toStageId)
            ->first();

        if (! $transition) {
            throw new RuntimeException('Переход между стадиями не найден');
        }

        /**
         * Проверяем условия перехода
         */
        $this->validator->validate($request, $transition);
    }

    /**
     * Получить список доступных переходов
     */
    public function getAllowedTransitions(ServiceRequest $request): array
    {
        return StageTransition::query()
            ->where('pipeline_id', $request->pipeline_id)
            ->where('from_stage_id', $request->stage_id)
            ->pluck('to_stage_id')
            ->toArray();
    }
}
