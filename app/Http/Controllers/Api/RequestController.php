<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ServiceRequest;
use App\Models\StageTransition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Список заявок
     */
    public function index(): JsonResponse
    {
        return response()->json(ServiceRequest::with(['stage', 'pipeline'])->get());
    }

    /**
     * Создание заявки
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'stage_id' => ['required', 'exists:stages,id'],
        ]);

        $serviceRequest = ServiceRequest::create([
            'title' => $data['title'],
            'stage_id' => $data['stage_id'],
            'pipeline_id' => 1,
            'organization_id' => 1,
            'request_type_id' => 1,
            'status' => 'open',
            'created_by' => 1,
        ]);

        return response()->json([
            'success' => true,
            'request' => $serviceRequest,
        ], 201);
    }

    /**
     * Перемещение заявки между стадиями
     */
    public function move(Request $request, ServiceRequest $serviceRequest): JsonResponse
    {
        $data = $request->validate([
            'stage_id' => ['required', 'exists:stages,id'],
        ]);

        $fromStageId = $serviceRequest->stage_id;
        $toStageId = $data['stage_id'];

        $transition = StageTransition::query()
            ->where('from_stage_id', $fromStageId)
            ->where('to_stage_id', $toStageId)
            ->first();

        if (!$transition) {
            return response()->json([
                'success' => false,
                'message' => 'Transition not allowed',
            ], 422);
        }

        $serviceRequest->stage_id = $toStageId;
        $serviceRequest->save();

        AuditLog::create([
            'request_id' => $serviceRequest->id,
            'action' => 'stage_changed',
            'from_stage_id' => $fromStageId,
            'to_stage_id' => $toStageId,
            'user_id' => 1,
        ]);

        return response()->json([
            'success' => true,
            'request' => $serviceRequest->fresh(),
        ]);
    }
}
