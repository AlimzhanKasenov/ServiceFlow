<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class StageTransition
 *
 * Описывает переход между стадиями workflow.
 *
 * Позволяет системе понимать:
 * из какой стадии можно перейти
 * в какую стадию.
 *
 * Пример:
 *
 * start → processing
 * processing → approval
 * approval → completed
 *
 * Таблица: stage_transitions
 *
 * @property int $id
 * @property int $pipeline_id
 * @property int $from_stage_id
 * @property int $to_stage_id
 * @property string $name
 * @property bool $requires_approval
 * @property int $position
 *
 * Relations:
 *
 * @property Pipeline $pipeline
 * @property Stage $fromStage
 * @property Stage $toStage
 * @property \Illuminate\Database\Eloquent\Collection<StageCondition> $conditions
 */
class StageTransition extends Model
{
    /**
     * Mass assignable
     */
    protected $fillable = [
        'pipeline_id',
        'from_stage_id',
        'to_stage_id',
        'name',
        'requires_approval',
        'position'
    ];

    /**
     * Pipeline перехода
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Стадия из которой идём
     */
    public function fromStage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'from_stage_id');
    }

    /**
     * Стадия в которую идём
     */
    public function toStage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'to_stage_id');
    }

    /**
     * Условия перехода
     *
     * Например:
     * approved = true
     * amount > 1000
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(StageCondition::class, 'transition_id');
    }
}
