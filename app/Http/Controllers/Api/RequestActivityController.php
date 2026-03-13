<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RequestActivity;
use Illuminate\Http\Request;

/**
 * Контроллер истории действий заявки
 */
class RequestActivityController extends Controller
{
    /**
     * Получить историю действий заявки
     */
    public function index($id)
    {
        $activities = RequestActivity::with('user')
            ->where('request_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($activities);
    }
}
