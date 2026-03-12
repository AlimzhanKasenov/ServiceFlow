<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AutomationAction
 *
 * Действие правила автоматизации.
 *
 * Примеры действий:
 *
 * send_email
 * create_task
 * webhook
 *
 * config хранится в JSON.
 *
 * Таблица: automation_actions
 */
class AutomationAction extends Model
{
    protected $table = 'automation_actions';

    protected $fillable = [
        'rule_id',
        'type',
        'config',
        'position'
    ];

    protected $casts = [
        'config' => 'array'
    ];

    /**
     * Rule
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(AutomationRule::class);
    }
}
