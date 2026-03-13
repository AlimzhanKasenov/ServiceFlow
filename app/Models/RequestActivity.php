<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RequestActivity
 *
 * История действий по заявке.
 *
 * Используется для построения таймлайна заявки:
 *
 * - заявка создана
 * - смена стадии
 * - назначение исполнителя
 * - изменение приоритета
 * - добавление комментария
 *
 * Таблица: request_activities
 *
 * @property int $id
 * @property int $request_id
 * @property int|null $user_id
 * @property string $type
 * @property array|null $data
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class RequestActivity extends Model
{
    /**
     * Массово заполняемые поля
     */
    protected $fillable = [
        'request_id',
        'user_id',
        'type',
        'data'
    ];

    /**
     * Приведение типов
     */
    protected $casts = [
        'data' => 'array'
    ];

    /**
     * Связь с заявкой
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    /**
     * Пользователь
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
