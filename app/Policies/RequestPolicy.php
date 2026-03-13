<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class RequestPolicy
{
    /**
     * Может ли пользователь двигать заявку
     */
    public function move(User $user, ServiceRequest $request): bool
    {
        return true;
    }
}
