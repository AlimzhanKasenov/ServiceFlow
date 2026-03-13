<?php

namespace App\Services\Workflow;

use App\Models\ServiceRequest;

class TransitionValidator
{
    /**
     * Проверка перехода
     */
    public function validate(ServiceRequest $request, int $targetStageId): bool
    {
        // позже тут будут правила перехода

        return true;
    }
}
