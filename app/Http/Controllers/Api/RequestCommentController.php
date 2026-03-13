<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RequestComment;
use Illuminate\Http\Request;

class RequestCommentController extends Controller
{
    public function index($requestId)
    {
        return RequestComment::with('user')
            ->where('request_id', $requestId)
            ->latest()
            ->get();
    }

    public function store(Request $request, $requestId)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $comment = RequestComment::create([
            'request_id' => $requestId,
            'user_id' => 1,
            'comment' => $request->comment
        ]);

        return response()->json(
            $comment->load('user')
        );
    }
}
