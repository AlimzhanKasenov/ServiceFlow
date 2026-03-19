<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\User;

/**
 * Проверка прав доступа к заявке
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
        // 🔥 DEV РЕЖИМ — берём первого пользователя
        $user = User::first();

        if (!$user) {
            return response()->json([
                'error' => 'Нет пользователей'
            ], 500);
        }

        // получаем заявку
        $requestId = $request->route('id')
            ?? $request->route('serviceRequest')
            ?? $request->route('req');

        $serviceRequest = ServiceRequest::find($requestId);

        if (!$serviceRequest) {
            return $next($request); // пропускаем если не про заявку
        }

        // 👑 админ (пока считаем user_id=1 админом)
        if ($user->id === 1) {
            return $next($request);
        }

        // 🧑‍💼 исполнитель
        if ($serviceRequest->assigned_to === $user->id) {
            return $next($request);
        }

        // 👁 watcher
        if ($serviceRequest->watchers()
            ->where('user_id', $user->id)
            ->exists()
        ) {
            if ($request->isMethod('get')) {
                return $next($request);
            }

            return response()->json([
                'error' => 'Только просмотр (watcher)'
            ], 403);
        }

        // 👤 создатель
        if ($serviceRequest->created_by === $user->id) {
            return $next($request);
        }

        return response()->json([
            'error' => 'Нет доступа'
        ], 403);
    }
}
