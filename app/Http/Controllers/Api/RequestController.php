<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\StageTransition;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Stage;
use App\Models\AuditLog;

class RequestController extends Controller
{
    /**
     * Список заявок
     */
    public function index(): JsonResponse
    {
        return response()->json(ServiceRequest::all());
    }

    public function move(Request $request, ServiceRequest $serviceRequest): JsonResponse
    {
        $data = $request->validate([
            'stage_id' => ['required', 'exists:stages,id']
        ]);

        $fromStageId = $serviceRequest->stage_id;
        $toStageId = $data['stage_id'];

        $transition = StageTransition::where('from_stage_id', $fromStageId)
            ->where('to_stage_id', $toStageId)
            ->first();

        if (!$transition) {
            return response()->json([
                'success' => false,
                'message' => 'Transition not allowed'
            ], 422);
        }

        $serviceRequest->stage_id = $toStageId;
        $serviceRequest->save();

        AuditLog::create([
            'request_id' => $serviceRequest->id,
            'action' => 'stage_changed',
            'from_stage_id' => $fromStageId,
            'to_stage_id' => $toStageId,
            'user_id' => 1
        ]);

        return response()->json([
            'success' => true,
            'request' => $serviceRequest->fresh()
        ]);
    }
}
