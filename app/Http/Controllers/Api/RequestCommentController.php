<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RequestComment;
use Illuminate\Http\Request;

class RequestCommentController extends Controller
{

    /**
     * Получить комментарии заявки
     */
    public function index($requestId)
    {
        return RequestComment::with('user')
            ->where('request_id', $requestId)
            ->latest()
            ->get();
    }

    /**
     * Добавить комментарий
     */
    public function store(Request $request, $requestId)
    {
        $comment = RequestComment::create([
            'request_id' => $requestId,
            'user_id' => 1, // пока временно
            'message' => $request->message
        ]);

        return $comment->load('user');
    }

}
