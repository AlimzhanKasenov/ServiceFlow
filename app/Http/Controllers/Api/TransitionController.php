<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\JsonResponse;

/**
 * Class TransitionController
 *
 * Возвращает доступные переходы из текущей стадии.
 */
class TransitionController extends Controller
{
    /**
     * Получить доступные переходы
     */
    public function index(Stage $stage): JsonResponse
    {
        $transitions = $stage->outgoingTransitions()
            ->with('toStage')
            ->orderBy('position')
            ->get();

        return response()->json([
            'success' => true,
            'transitions' => $transitions
        ]);
    }
}
