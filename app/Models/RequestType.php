<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class RequestType
 *
 * Модель типа процесса ServiceFlow.
 *
 * Тип процесса определяет бизнес-направление заявок.
 * Примеры:
 * - IT Support
 * - HR
 * - Finance
 * - Legal
 *
 * Каждый тип процесса:
 * - принадлежит организации (multi-tenant)
 * - содержит одну или несколько воронок (pipelines)
 *
 * @property int $id / уникальный идентификатор типа процесса
 * @property int $organization_id / организация (tenant системы)
 * @property string $name / название типа процесса
 * @property string|null $description / описание типа процесса
 * @property string|null $icon / иконка (название или путь к иконке)
 * @property \Illuminate\Support\Carbon|null $created_at / дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at / дата обновления
 *
 * Связи модели:
 *
 * @property Organization $organization / организация владельца
 * @property \Illuminate\Database\Eloquent\Collection|Pipeline[] $pipelines / воронки данного типа процесса
 */
class RequestType extends Model
{
    /**
     * Массово заполняемые поля.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'icon'
    ];

    /**
     * Организация, к которой относится тип процесса.
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Воронки данного типа процесса.
     *
     * Один тип процесса может иметь несколько воронок.
     *
     * @return HasMany
     */
    public function pipelines(): HasMany
    {
        return $this->hasMany(Pipeline::class);
    }
}
