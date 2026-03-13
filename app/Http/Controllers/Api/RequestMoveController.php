<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\StageTransition;
use App\Models\RequestActivity;
use Illuminate\Http\Request;

class RequestMoveController extends Controller
{
    /**
     * Перемещение заявки между стадиями
     */
    public function move(Request $request, ServiceRequest $req)
    {
        $validated = $request->validate([
            'to_stage_id' => 'required|exists:stages,id'
        ]);

        $fromStage = $req->stage_id;
        $toStage = $validated['to_stage_id'];

        /**
         * Проверяем допустимость перехода
         */
        $allowed = StageTransition::where('from_stage_id', $fromStage)
            ->where('to_stage_id', $toStage)
            ->exists();

        if (!$allowed) {
            return response()->json([
                'error' => 'Transition not allowed'
            ], 403);
        }

        /**
         * Перемещение заявки
         */
        $req->stage_id = $toStage;
        $req->save();

        /**
         * Лог активности
         */
        RequestActivity::create([
            'request_id' => $req->id,
            'user_id' => auth()->id(),
            'type' => 'stage_changed',
            'data' => [
                'from_stage' => $fromStage,
                'to_stage' => $toStage
            ]
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
