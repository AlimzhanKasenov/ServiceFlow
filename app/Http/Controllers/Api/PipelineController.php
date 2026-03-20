<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pipeline;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PipelineController extends Controller
{
    #[OA\Get(
        path: "/api/pipelines",
        operationId: "getPipelines",
        summary: "Список pipeline",
        tags: ["Pipelines"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of pipelines"
            )
        ]
    )]

    public function index(): JsonResponse
    {
        return response()->json(Pipeline::all());
    }

    /**
     * Показать pipeline вместе со стадиями и заявками
     */
    public function show($id): JsonResponse
    {
        $pipeline = Pipeline::with([
            'stages.requests'
        ])->findOrFail($id);

        /**
         * Добавляем allowed_transitions в каждую заявку
         */
        $stageManager = app(\App\Services\Workflow\StageManager::class);

        foreach ($pipeline->stages as $stage) {
            foreach ($stage->requests as $request) {
                $request->allowed_transitions = $stageManager->getAllowedTransitions($request);
            }
        }

        return response()->json($pipeline);
    }
}
