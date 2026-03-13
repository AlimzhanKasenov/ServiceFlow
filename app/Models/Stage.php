<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    protected $fillable = [
        'pipeline_id',
        'name',
        'position',
        'type',
    ];

    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Переходы из текущей стадии
     */
    public function outgoingTransitions(): HasMany
    {
        return $this->hasMany(StageTransition::class, 'from_stage_id');
    }

    /**
     * Переходы в текущую стадию
     */
    public function incomingTransitions(): HasMany
    {
        return $this->hasMany(StageTransition::class, 'to_stage_id');
    }

    /**
     * Заявки в этой стадии
     */
    public function requests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'stage_id');
    }
}
