<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AutomationRule
 *
 * Правило автоматизации workflow.
 *
 * Пример:
 *
 * Event: stage_enter
 * Stage: approval
 *
 * Actions:
 * - send_email
 * - create_task
 *
 * Таблица: automation_rules
 */
class AutomationRule extends Model
{
    protected $table = 'automation_rules';

    protected $fillable = [
        'pipeline_id',
        'stage_id',
        'event',
        'name',
        'active'
    ];

    /**
     * Pipeline
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Stage
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Actions
     */
    public function actions(): HasMany
    {
        return $this->hasMany(AutomationAction::class, 'rule_id')
            ->orderBy('position');
    }
}
