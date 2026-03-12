<?php

namespace App\Services\Workflow;

use App\Models\Request;
use App\Models\Stage;
use App\Models\StageTransition;
use App\Models\RequestStageHistory;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\PipelinePermission;
use App\Models\User;

/**
 * Class StageManager
 *
 * Сервис управления workflow переходами заявок.
 *
 * Отвечает за:
 *
 * - проверку допустимости перехода
 * - изменение стадии заявки
 * - запись истории переходов
 * - атомарность операции (через DB transaction)
 *
 * Используется API контроллерами и системой автоматизации.
 */
class StageManager
{
    /**
     * Выполнить переход заявки в другую стадию.
     *
     * @param Request $request  Заявка
     * @param int $toStageId    ID стадии назначения
     * @param int|null $userId  Пользователь выполнивший переход
     *
     * @return Request
     *
     * @throws Exception
     */
    public function move(Request $request, int $toStageId, ?int $userId = null): Request
    {
        return DB::transaction(function () use ($request, $toStageId, $userId) {

            /**
             * Текущая стадия заявки
             */
            $fromStageId = $request->stage_id;

            /**
             * Проверяем что стадия назначения существует
             */
            $toStage = Stage::find($toStageId);

            if (!$toStage) {
                throw new Exception('Target stage not found');
            }

            /**
             * Проверяем допустимость перехода
             */
            $transition = StageTransition::query()
                ->where('from_stage_id', $fromStageId)
                ->where('to_stage_id', $toStageId)
                ->first();

            if (!$transition) {
                throw new Exception('Stage transition not allowed');
            }

            /**
             * Меняем стадию заявки
             */
            $request->stage_id = $toStageId;
            $request->save();

            /**
             * Записываем историю перехода
             */
            RequestStageHistory::create([
                'request_id' => $request->id,
                'from_stage_id' => $fromStageId,
                'to_stage_id' => $toStageId,
                'user_id' => $userId,
                'moved_at' => now()
            ]);

            return $request->fresh();
        });
    }

    /**
     * Проверка прав пользователя на pipeline
     */
    protected function checkPipelinePermission(int $userId, Request $request): void
    {
        $user = User::findOrFail($userId);

        $permission = PipelinePermission::query()
            ->where('pipeline_id', $request->pipeline_id)
            ->where('role_id', $user->role_id)
            ->first();

        if (!$permission || !$permission->can_move) {
            throw new \Exception('User cannot move request in this pipeline');
        }
    }
}
