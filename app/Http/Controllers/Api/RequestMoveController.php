<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\RequestActivity;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RequestMoveController extends Controller
{
    /**
     * Перемещение заявки между стадиями
     */
    public function move(Request $request, ServiceRequest $req): JsonResponse
    {
        $validated = $request->validate([
            'stage_id' => ['required', 'exists:stages,id']
        ]);

        $fromStageId = $req->stage_id;
        $toStageId = $validated['stage_id'];

        /**
         * Если стадия не изменилась — просто возвращаем успех
         */
        if ($fromStageId == $toStageId) {
            return response()->json([
                'success' => true,
                'request' => $req
            ]);
        }

        /**
         * Получаем стадии
         */
        $fromStage = Stage::find($fromStageId);
        $toStage = Stage::find($toStageId);

        /**
         * Меняем стадию заявки
         */
        $req->stage_id = $toStageId;
        $req->save();

        /**
         * Записываем историю действий
         */
        $activity = RequestActivity::create([
            'request_id' => $req->id,
            'user_id' => auth()->id() ?? 1, // пока fallback
            'type' => 'stage_changed',
            'data' => [
                'from_stage' => $fromStage?->name,
                'to_stage' => $toStage?->name
            ]
        ]);

        /**
         * Подгружаем пользователя для frontend
         */
        $activity->load('user');

        return response()->json([
            'success' => true,
            'request' => $req->fresh(),
            'activity' => $activity
        ]);
    }
}
