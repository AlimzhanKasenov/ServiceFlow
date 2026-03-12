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
     * Pipeline со стадиями
     */
    public function show(int $id): JsonResponse
    {
        $pipeline = Pipeline::with('stages')->findOrFail($id);

        return response()->json($pipeline);
    }
}
