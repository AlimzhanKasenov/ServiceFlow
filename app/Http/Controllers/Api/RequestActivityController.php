<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RequestActivity;
use App\Models\RequestStageHistory;

/**
 * Контроллер истории действий заявки
 */
class RequestActivityController extends Controller
{
    public function index($id): \Illuminate\Http\JsonResponse
    {
        $activities = RequestActivity::with('user')
            ->where('request_id', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'data' => $item->data,
                    'user' => $item->user
                        ? ['name' => $item->user->name]
                        : null,
                    'created_at' => $item->created_at
                ];
            });

        $stageHistory = RequestStageHistory::with(['fromStage', 'toStage', 'user'])
            ->where('request_id', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'stage-'.$item->id,
                    'type' => 'stage_change',
                    'data' => [
                        'from_stage' => $item->fromStage?->name,
                        'to_stage' => $item->toStage?->name
                    ],
                    'user' => $item->user
                        ? ['name' => $item->user->name]
                        : null,
                    'created_at' => $item->moved_at
                ];
            });

        $timeline = $activities
            ->merge($stageHistory)
            ->sortByDesc('created_at')
            ->values();

        return response()->json($timeline);
    }
}
