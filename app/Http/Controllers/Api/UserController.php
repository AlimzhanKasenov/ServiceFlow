<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * Class UserController
 *
 * API-контроллер для работы с пользователями.
 */
class UserController extends Controller
{
    /**
     * Получить список пользователей
     *
     * Используется в карточке заявки для выбора исполнителя.
     */
    public function index(): JsonResponse
    {
        $users = User::query()
            ->select(['id', 'name', 'email'])
            ->orderBy('name')
            ->get();

        return response()->json($users);
    }
}
