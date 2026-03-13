<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\RequestActivity;
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
         * Пока в режиме разработки разрешаем любые переходы
         * Ограничения через StageTransition вернём позже
         */
        $req->stage_id = $toStageId;
        $req->save();

        /**
         * Пишем историю действий заявки
         */
        RequestActivity::create([
            'request_id' => $req->id,
            'user_id' => 1,
            'type' => 'stage_changed',
            'data' => [
                'from_stage' => $fromStageId,
                'to_stage' => $toStageId
            ]
        ]);

        return response()->json([
            'success' => true,
            'request' => $req->fresh()
        ]);
    }
}
