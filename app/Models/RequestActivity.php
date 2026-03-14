<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RequestActivity
 *
 * Лог действий по заявке.
 */
class RequestActivity extends Model
{
    /**
     * Mass assignable
     */
    protected $fillable = [
        'request_id',
        'user_id',
        'type',
        'data',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Заявка
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }

    /**
     * Пользователь
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
