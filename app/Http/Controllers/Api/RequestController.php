<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ServiceRequest;
use App\Models\StageTransition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\RequestStageHistory;

class RequestController extends Controller
{
    /**
     * Список заявок
     */
    public function index(): JsonResponse
    {
        return response()->json(
            ServiceRequest::with(['stage', 'pipeline'])->get()
        );
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

        /**
         * SLA время заявки
         * Пока делаем фиксированное значение — 2 часа
         */
        $slaMinutes = 120;

        $serviceRequest = ServiceRequest::create([
            'title' => $data['title'],
            'stage_id' => $data['stage_id'],
            'pipeline_id' => 1,
            'organization_id' => 1,
            'request_type_id' => 1,
            'status' => 'open',
            'created_by' => 1,

            /**
             * SLA поля
             */
            'sla_minutes' => $slaMinutes,
            'sla_started_at' => now(),
            'sla_due_at' => now()->addMinutes($slaMinutes),
            'sla_breached' => false
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
        $userId = 1;

        if ($serviceRequest->assigned_to && $serviceRequest->assigned_to != $userId) {
            return response()->json([
                'error' => 'Только исполнитель может менять стадию'
            ], 403);
        }

        $data = $request->validate([
            'stage_id' => ['required', 'exists:stages,id']
        ]);

        $fromStageId = $serviceRequest->stage_id;
        $toStageId = $data['stage_id'];

        /**
         * В режиме разработки разрешаем любые переходы
         */

        if ($fromStageId == $toStageId) {
            return response()->json([
                'success' => true,
                'request' => $serviceRequest
            ]);
        }

        $serviceRequest->stage_id = $toStageId;
        $serviceRequest->save();

        /**
         * Логируем изменение стадии
         */
        RequestStageHistory::create([
            'request_id' => $serviceRequest->id,
            'from_stage_id' => $fromStageId,
            'to_stage_id' => $toStageId,
            'user_id' => 1,
            'moved_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'request' => $serviceRequest->fresh()
        ]);
    }

    /**
     * Получить карточку заявки
     */
    public function show($id)
    {
        $request = ServiceRequest::with([
            'stage',
            'pipeline',
            'creator',
            'assignee',
            'comments.user',
            'activities.user'
        ])->findOrFail($id);

        $user = auth()->user();

        // если нет авторизации — временно считаем user_id = 1
        $userId = $user?->id ?? 1;

        $isCreator = $request->created_by === $userId;
        $isAssignee = $request->assigned_to === $userId;

        $request->can_edit_title = $isCreator;
        $request->can_change_stage = $isAssignee;
        $request->can_change_priority = $isCreator || $isAssignee;
        $request->can_change_assignee = $isCreator || $isAssignee;

        return response()->json($request);
    }

    /**
     * Назначить исполнителя заявки
     */
    public function assign(Request $request, ServiceRequest $serviceRequest): JsonResponse
    {
        $userId = 1;

        $isCreator = $serviceRequest->created_by == $userId;
        $isAssignee = $serviceRequest->assigned_to == $userId;

        if (!$isCreator && !$isAssignee) {
            return response()->json([
                'error' => 'Нет прав менять исполнителя'
            ], 403);
        }

        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id']
        ]);

        $oldAssignee = $serviceRequest->assigned_to;

        /**
         * Обновляем исполнителя
         */
        $serviceRequest->assigned_to = $data['user_id'] ?? null;
        $serviceRequest->save();

        $oldUser = $oldAssignee ? \App\Models\User::find($oldAssignee) : null;
        $newUser = $data['user_id'] ? \App\Models\User::find($data['user_id']) : null;

        $activity = $serviceRequest->activities()->create([
            'user_id' => 1,
            'type' => 'assignment',
            'data' => [
                'old_assignee' => $oldUser?->name,
                'new_assignee' => $newUser?->name
            ]
        ]);

        return response()->json([
            'success' => true,
            'request' => $serviceRequest->fresh(),
            'activity' => $activity->load('user')
        ]);
    }

    /**
     * Обновление заявки
     */
    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $userId = 1;

        $isCreator = $serviceRequest->created_by == $userId;
        $isAssignee = $serviceRequest->assigned_to == $userId;

        $data = [];

        // ✏️ title — только creator
        if ($request->has('title') && $isCreator) {
            $data['title'] = $request->input('title');
        }

        // ⚡ priority — creator + assignee
        if ($request->has('priority') && ($isCreator || $isAssignee)) {
            $data['priority'] = $request->input('priority');
        }

        $serviceRequest->update($data);

        return response()->json($serviceRequest->fresh());
    }
}
