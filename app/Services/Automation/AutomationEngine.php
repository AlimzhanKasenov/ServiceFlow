<?php

namespace App\Services\Automation;

use App\Models\ServiceRequest;
use App\Models\AutomationRule;
use Illuminate\Support\Facades\Log;

/**
 * Class AutomationEngine
 *
 * Главный движок автоматизации.
 *
 * Отвечает за:
 * - поиск правил
 * - проверку условий
 * - выполнение действий
 */
class AutomationEngine
{
    protected AutomationService $automationService;

    public function __construct(AutomationService $automationService)
    {
        $this->automationService = $automationService;
    }

    /**
     * Запуск automation при смене стадии
     */
    public function handleStageChanged(ServiceRequest $request): void
    {
        Log::info('AutomationEngine: stage_changed triggered', [
            'request_id' => $request->id,
            'stage_id' => $request->stage_id
        ]);

        // получаем правила для события
        $rules = AutomationRule::where('trigger', 'stage_changed')->get();

        foreach ($rules as $rule) {

            // проверяем условие
            if (!$this->checkCondition($rule, $request)) {
                continue;
            }

            // выполняем действия
            foreach ($rule->actions as $action) {
                $this->automationService->execute($action, $request);
            }
        }
    }

    /**
     * Проверка условия правила
     */
    protected function checkCondition(AutomationRule $rule, ServiceRequest $request): bool
    {
        if (!$rule->condition) {
            return true;
        }

        $condition = json_decode($rule->condition, true);

        // пример: stage_id = 3
        if (isset($condition['stage_id'])) {
            return $request->stage_id == $condition['stage_id'];
        }

        return false;
    }
}
