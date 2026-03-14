<?php

namespace App\Services\Automation;

use App\Models\ServiceRequest;
use App\Models\AutomationRule;

/**
 * Class AutomationEngine
 *
 * Главный движок автоматизации.
 */
class AutomationEngine
{
    protected AutomationService $service;

    public function __construct(AutomationService $service)
    {
        $this->service = $service;
    }

    /**
     * Обработать событие
     */
    public function handle(string $event, ServiceRequest $request): void
    {
        $rules = AutomationRule::query()
            ->where('pipeline_id', $request->pipeline_id)
            ->where('event', $event)
            ->where('active', true)
            ->with('actions')
            ->get();

        foreach ($rules as $rule) {

            /**
             * если правило привязано к стадии
             */
            if ($rule->stage_id && $rule->stage_id !== $request->stage_id) {
                continue;
            }

            foreach ($rule->actions as $action) {
                $this->service->execute($action, $request);
            }
        }
    }
}
