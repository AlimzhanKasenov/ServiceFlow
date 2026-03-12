<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ServiceRequest
 *
 * Основная модель заявки ServiceFlow.
 *
 * Заявка проходит через стадии pipeline
 * и фиксирует бизнес-процесс внутри системы.
 *
 * Примеры:
 *
 * IT заявка
 * HR заявка
 * Финансовое согласование
 * Запрос оборудования
 *
 * @property int $id Уникальный идентификатор заявки
 * @property int $organization_id Организация (tenant)
 * @property int $pipeline_id Воронка процесса
 * @property int $stage_id Текущая стадия
 * @property int $created_by Автор заявки
 * @property int|null $assigned_to Ответственный сотрудник
 * @property string $title Заголовок заявки
 * @property string|null $description Описание
 * @property string $status Статус заявки
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * Связи модели:
 *
 * @property Organization $organization
 * @property Pipeline $pipeline
 * @property Stage $stage
 * @property User $creator
 * @property User|null $assignee
 */
class ServiceRequest extends Model
{
    protected $fillable = [
        'organization_id',
        'pipeline_id',
        'stage_id',
        'created_by',
        'assigned_to',
        'title',
        'description',
        'status'
    ];

    /**
     * Организация заявки.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Воронка процесса.
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Текущая стадия заявки.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Автор заявки.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Ответственный исполнитель.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
