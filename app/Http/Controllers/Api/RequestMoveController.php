<?php

namespace App\Http\Controllers\Api;

use App\Actions\Requests\MoveRequestAction;
use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            'stage_id' => ['required', 'exists:stages,id'],
        ]);

        try {
            $updatedRequest = $moveAction->execute(
                $req,
                (int) $validated['stage_id'],
                1
            );

            return response()->json([
                'success' => true,
                'request' => $updatedRequest,
            ]);
        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
