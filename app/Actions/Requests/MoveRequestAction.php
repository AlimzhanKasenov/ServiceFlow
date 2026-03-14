<?php

namespace App\Actions\Requests;

use App\Models\RequestActivity;
use App\Models\RequestStageHistory;
use App\Models\ServiceRequest;
use App\Models\StageTransition;
use App\Services\StageConditionService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Class MoveRequestAction
 *
 * Отвечает за перемещение заявки между стадиями workflow.
 */
class MoveRequestAction
{
    protected StageConditionService $conditionService;

    public function __construct(StageConditionService $conditionService)
    {
        $this->conditionService = $conditionService;
    }

    /**
     * Выполнить перемещение заявки
     */
    public function execute(ServiceRequest $request, int $toStageId, ?int $userId = null): ServiceRequest
    {
        /**
         * Ищем переход
         */
        $transition = StageTransition::query()
            ->with(['fromStage', 'toStage', 'conditions'])
            ->where('pipeline_id', $request->pipeline_id)
            ->where('from_stage_id', $request->stage_id)
            ->where('to_stage_id', $toStageId)
            ->first();

        if (!$transition) {
            throw new RuntimeException('Переход между стадиями не найден');
        }

        /**
         * Проверка условий перехода
         */
        if (!$this->conditionService->check($request, $transition)) {
            throw new RuntimeException('Условия перехода не выполнены');
        }

        /**
         * Если стадия не меняется
         */
        if ($request->stage_id == $toStageId) {
            return $request->fresh();
        }

        return DB::transaction(function () use ($request, $toStageId, $transition, $userId) {

            $fromStageId = $request->stage_id;

            /**
             * Меняем стадию заявки
             */
            $request->update([
                'stage_id' => $toStageId
            ]);

            /**
             * Пишем историю перехода
             */
            RequestStageHistory::create([
                'request_id' => $request->id,
                'from_stage_id' => $fromStageId,
                'to_stage_id' => $toStageId,
                'user_id' => $userId,
                'moved_at' => now()
            ]);

            /**
             * Пишем activity
             */
            RequestActivity::create([
                'request_id' => $request->id,
                'user_id' => $userId,
                'type' => 'stage_changed',
                'data' => [
                    'transition_id' => $transition->id,
                    'transition_name' => $transition->name,
                    'from_stage_id' => $fromStageId,
                    'to_stage_id' => $toStageId,
                    'from_stage_name' => $transition->fromStage?->name,
                    'to_stage_name' => $transition->toStage?->name
                ]
            ]);

            return $request->fresh();

        });
    }
}
