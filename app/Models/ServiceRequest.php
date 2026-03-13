<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ServiceRequest
 *
 * Основная модель заявки системы ServiceFlow.
 */
class ServiceRequest extends Model
{
    /**
     * Таблица заявок
     */
    protected $table = 'requests';

    /**
     * Разрешённые поля для массового заполнения
     */
    protected $fillable = [
        'title',
        'stage_id',
        'pipeline_id',
        'organization_id',
        'request_type_id',
        'status',
        'created_by',
        'assigned_to',
        'description',
        'priority',
        'data',
    ];

    /**
     * Организация заявки
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Тип процесса
     */
    public function requestType(): BelongsTo
    {
        return $this->belongsTo(RequestType::class);
    }

    /**
     * Воронка процесса
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Стадия заявки
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Автор заявки
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Исполнитель
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(RequestComment::class, 'request_id')
            ->latest();
    }
}
