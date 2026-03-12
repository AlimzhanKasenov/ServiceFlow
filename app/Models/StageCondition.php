<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class StageCondition
 *
 * Условие перехода между стадиями workflow.
 *
 * Проверяет данные заявки перед переходом.
 *
 * Пример:
 *
 * field = approved
 * operator = =
 * value = true
 *
 * Таблица: stage_conditions
 *
 * @property int $id
 * @property int $transition_id
 * @property string $field
 * @property string $operator
 * @property string $value
 */
class StageCondition extends Model
{
    /**
     * Таблица
     */
    protected $table = 'stage_conditions';

    /**
     * Mass assignable
     */
    protected $fillable = [
        'transition_id',
        'field',
        'operator',
        'value'
    ];

    /**
     * Transition
     */
    public function transition(): BelongsTo
    {
        return $this->belongsTo(StageTransition::class);
    }
}
