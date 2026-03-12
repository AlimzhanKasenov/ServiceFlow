<?php

namespace App\Services\Automation;

use App\Models\AutomationRule;
use App\Models\Request;

/**
 * Class AutomationService
 *
 * Выполняет правила автоматизации workflow.
 */
class AutomationService
{
    /**
     * Выполнить автоматизацию
     */
    public function handle(string $event, Request $request): void
    {
        $rules = AutomationRule::query()
            ->where('event', $event)
            ->where('pipeline_id', $request->pipeline_id)
            ->where('active', true)
            ->get();

        foreach ($rules as $rule) {

            foreach ($rule->actions as $action) {

                $this->executeAction($action->type, $action->config, $request);
            }
        }
    }

    /**
     * Выполнение действия
     */
    protected function executeAction(string $type, array $config, Request $request): void
    {
        switch ($type) {

            case 'send_email':

                // отправка email

                break;

            case 'create_task':

                // создание задачи

                break;

            case 'webhook':

                // webhook

                break;
        }
    }
}
