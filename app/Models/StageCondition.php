<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
