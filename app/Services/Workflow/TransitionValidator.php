<?php

namespace App\Services\Workflow;

use App\Models\ServiceRequest;
use App\Models\StageTransition;
use RuntimeException;

/**
 * Class TransitionValidator
 *
 * Проверяет можно ли выполнить переход
 * между стадиями workflow.
 */
class TransitionValidator
{
    protected StageConditionService $conditionService;

    public function __construct(StageConditionService $conditionService)
    {
        $this->conditionService = $conditionService;
    }

    /**
     * Проверка перехода
     */
    public function validate(ServiceRequest $request, StageTransition $transition): void
    {
        /**
         * Проверяем условия перехода
         */
        if (!$this->conditionService->check($request, $transition)) {
            throw new RuntimeException(
                'Условия перехода между стадиями не выполнены'
            );
        }
    }
}
