<?php

namespace App\Services\Automation;

use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Log;

/**
 * Class AutomationService
 *
 * Выполняет действия automation
 */
class AutomationService
{
    /**
     * Выполнить действие
     */
    public function execute($action, ServiceRequest $request): void
    {
        Log::info('AutomationService: execute action', [
            'type' => $action->type,
            'config' => $action->config
        ]);

        switch ($action->type) {

            case 'assign_user':
                $this->assignUser($request, $action->config);
                break;

            case 'change_priority':
                $this->changePriority($request, $action->config);
                break;
        }
    }

    /**
     * Назначение пользователя
     */
    protected function assignUser(ServiceRequest $request, array $config): void
    {
        $userId = $config['user_id'] ?? null;

        if (!$userId) {
            return;
        }

        $request->update([
            'assigned_to' => $userId
        ]);
    }

    /**
     * Смена приоритета
     */
    protected function changePriority(ServiceRequest $request, array $config): void
    {
        $priority = $config['priority'] ?? null;

        if (!$priority) {
            return;
        }

        $request->update([
            'priority' => $priority
        ]);
    }
}
