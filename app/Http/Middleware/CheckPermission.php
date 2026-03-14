<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Проверка permission пользователя
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();

        if (!$user) {

            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        }

        if (!$user->hasPermission($permission)) {

            return response()->json([
                'message' => 'Permission denied'
            ], 403);

        }

        return $next($request);
    }
}
