<?php

namespace App\Http\Controllers\Api;

use App\Actions\Requests\MoveRequestAction;
use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class RequestMoveController extends Controller
{
    /**
     * Перемещение заявки между стадиями
     */
    public function move(
        Request $request,
        ServiceRequest $req,
        MoveRequestAction $moveAction
    ): JsonResponse {

        $validated = $request->validate([
            'stage_id' => ['required', 'exists:stages,id']
        ]);

        try {

            $updatedRequest = $moveAction->execute(
                $req,
                $validated['stage_id'],
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'request' => $updatedRequest
            ]);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);

        }
    }
}
