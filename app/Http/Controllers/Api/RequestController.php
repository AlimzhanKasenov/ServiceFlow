<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RequestController extends Controller
{
    /**
     * Список заявок
     */
    public function index(): JsonResponse
    {
        return response()->json(ServiceRequest::all());
    }

    /**
     * Перемещение заявки между стадиями
     */
    public function move(Request $request, int $id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);

        $serviceRequest->stage_id = $request->stage_id;

        $serviceRequest->save();

        return response()->json([
            'success' => true,
            'request' => $serviceRequest
        ]);
    }
}
