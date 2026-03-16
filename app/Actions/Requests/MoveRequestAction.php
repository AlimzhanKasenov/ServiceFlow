<?php

namespace App\Actions\Requests;

use App\Events\RequestStageChanged;
use App\Models\RequestStageHistory;
use App\Models\ServiceRequest;
use App\Services\Workflow\StageManager;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Перемещение заявки между стадиями workflow
 */
class MoveRequestAction
{
    protected StageManager $stageManager;

    public function __construct(StageManager $stageManager)
    {
        $this->stageManager = $stageManager;
    }

    /**
     * Выполнить переход заявки в новую стадию
     */
    public function execute(
        ServiceRequest $request,
        int $toStageId,
        ?int $userId = null
    ): ServiceRequest {
        return DB::transaction(function () use ($request, $toStageId, $userId) {
            $fromStageId = (int) $request->stage_id;

            if ($fromStageId === $toStageId) {
                return $request;
            }

            $this->stageManager->validateTransition($request, $toStageId);

            $request->stage_id = $toStageId;
            $request->save();

            RequestStageHistory::create([
                'request_id' => $request->id,
                'from_stage_id' => $fromStageId,
                'to_stage_id' => $toStageId,
                'user_id' => $userId,
                'moved_at' => now(),
            ]);

            event(new RequestStageChanged(
                $request->fresh(),
                $fromStageId,
                $toStageId
            ));

            return $request->fresh();
        });
    }
}
