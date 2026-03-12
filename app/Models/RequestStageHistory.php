<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RequestStageHistory
 *
 * История переходов заявки между стадиями.
 *
 * Используется для:
 *
 * - audit trail
 * - построения таймлайна заявки
 * - SLA аналитики
 * - истории workflow
 *
 * Пример записи:
 *
 * request_id: 15
 * from_stage: new
 * to_stage: review
 * user_id: 3
 * moved_at: 2026-03-12 14:00
 *
 * @property int $id
 * @property int $request_id
 * @property int|null $from_stage_id
 * @property int $to_stage_id
 * @property int|null $user_id
 * @property \Carbon\Carbon $moved_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class RequestStageHistory extends Model
{
    /**
     * Таблица
     */
    protected $table = 'request_stage_history';

    /**
     * Массово заполняемые поля
     */
    protected $fillable = [
        'request_id',
        'from_stage_id',
        'to_stage_id',
        'user_id',
        'moved_at'
    ];

    /**
     * Приведение типов
     */
    protected $casts = [
        'moved_at' => 'datetime',
    ];

    /**
     * Связь с заявкой
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    /**
     * Стадия из которой произошёл переход
     */
    public function fromStage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'from_stage_id');
    }

    /**
     * Стадия в которую произошёл переход
     */
    public function toStage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'to_stage_id');
    }

    /**
     * Пользователь совершивший переход
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
