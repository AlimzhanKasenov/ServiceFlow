<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use App\Models\StageTransition;
use Illuminate\Http\Request;

class RequestMoveController extends Controller
{
    /**
     * Перемещение заявки между стадиями
     */
    public function move(Request $request, RequestModel $req)
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
         * Перемещение
         */
        $req->stage_id = $toStage;
        $req->save();

        return response()->json([
            'success' => true
        ]);
    }
}
