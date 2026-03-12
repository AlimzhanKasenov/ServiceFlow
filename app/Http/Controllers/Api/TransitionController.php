<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\JsonResponse;

/**
 * Class TransitionController
 *
 * API для получения доступных переходов между стадиями.
 */
class TransitionController extends Controller
{
    /**
     * Получить переходы из стадии
     *
     * GET /api/stages/{stage}/transitions
     */
    public function index(Stage $stage): JsonResponse
    {
        $transitions = $stage
            ->outgoingTransitions()
            ->with(['toStage', 'conditions'])
            ->orderBy('position')
            ->get();

        return response()->json($transitions);
    }
}
