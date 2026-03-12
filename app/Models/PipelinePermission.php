<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PipelinePermission
 *
 * Модель прав роли внутри pipeline.
 *
 * Используется для ограничения действий
 * в конкретной воронке процессов.
 *
 * Таблица: pipeline_permissions
 *
 * @property int $id
 * @property int $pipeline_id
 * @property int $role_id
 * @property bool $can_view
 * @property bool $can_create
 * @property bool $can_move
 * @property bool $can_close
 * @property bool $can_manage
 */
class PipelinePermission extends Model
{
    /**
     * Таблица
     */
    protected $table = 'pipeline_permissions';

    /**
     * Mass assignable
     */
    protected $fillable = [
        'pipeline_id',
        'role_id',
        'can_view',
        'can_create',
        'can_move',
        'can_close',
        'can_manage'
    ];

    /**
     * Pipeline
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Role
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
