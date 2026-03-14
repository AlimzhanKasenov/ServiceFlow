<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AutomationAction
 *
 * Действие автоматизации.
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

    public function rule(): BelongsTo
    {
        return $this->belongsTo(AutomationRule::class);
    }
}
