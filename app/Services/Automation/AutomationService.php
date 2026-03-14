<?php

namespace App\Services\Automation;

use App\Models\ServiceRequest;
use App\Models\AutomationAction;
use App\Models\RequestActivity;

/**
 * Class AutomationService
 *
 * Выполняет действия автоматизации.
 */
class AutomationService
{
    public function execute(AutomationAction $action, ServiceRequest $request): void
    {
        match ($action->type) {

            'assign_user' => $this->assignUser($action, $request),

            'add_comment' => $this->addComment($action, $request),

            'set_priority' => $this->setPriority($action, $request),

            default => null
        };
    }

    /**
     * Назначить пользователя
     */
    protected function assignUser(AutomationAction $action, ServiceRequest $request): void
    {
        $userId = $action->config['user_id'] ?? null;

        if (!$userId) {
            return;
        }

        $request->update([
            'assigned_to' => $userId
        ]);
    }

    /**
     * Добавить комментарий
     */
    protected function addComment(AutomationAction $action, ServiceRequest $request): void
    {
        RequestActivity::create([
            'request_id' => $request->id,
            'user_id' => 1,
            'type' => 'automation_comment',
            'data' => [
                'message' => $action->config['message'] ?? 'Automation message'
            ]
        ]);
    }

    /**
     * Изменить приоритет
     */
    protected function setPriority(AutomationAction $action, ServiceRequest $request): void
    {
        $priority = $action->config['priority'] ?? null;

        if (!$priority) {
            return;
        }

        $request->update([
            'priority' => $priority
        ]);
    }
}
