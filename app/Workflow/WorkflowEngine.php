<?php

namespace App\Workflow;

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
