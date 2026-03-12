<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 */
class StageTransition extends Model
{
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
}
