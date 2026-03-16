<?php

namespace App\Services\Workflow;

use App\Models\ServiceRequest;

class WorkflowEngine
{
    /**
     * Выполнить автоматические действия
     */
    public function run(ServiceRequest $request): void
    {
        // здесь будут automation rules
    }
}
