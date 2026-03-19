<?php

namespace App\Listeners;

use App\Events\RequestStageChanged;
use App\Services\Automation\AutomationEngine;

/**
 * Listener запуска автоматизации
 */
class HandleAutomationAfterStageChange
{
    protected AutomationEngine $engine;

    public function __construct(AutomationEngine $engine)
    {
        $this->engine = $engine;
    }

    public function handle(RequestStageChanged $event): void
    {
        app(\App\Services\Automation\AutomationEngine::class)
            ->handleStageChanged($event->request);
    }
}
