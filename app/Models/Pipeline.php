<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Pipeline
 *
 * Модель воронки процесса ServiceFlow.
 *
 * Воронка определяет последовательность стадий,
 * через которые проходит заявка.
 *
 * Пример:
 *
 * IT Support:
 *   1. Новая заявка
 *   2. В работе
 *   3. Согласование
 *   4. Закрыта
 *
 * Каждая воронка:
 *
 * - принадлежит организации (multi-tenant)
 * - связана с типом процесса
 * - содержит набор стадий
 *
 * @property int $id Уникальный идентификатор воронки
 * @property int $organization_id Организация (tenant системы)
 * @property int $request_type_id Тип процесса (IT, HR, Finance и т.д.)
 * @property string $name Название воронки
 * @property \Illuminate\Support\Carbon|null $created_at Дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at Дата обновления
 *
 * Связи модели:
 *
 * @property Organization $organization Организация
 * @property RequestType $requestType Тип процесса
 * @property \Illuminate\Database\Eloquent\Collection|Stage[] $stages Стадии данной воронки
 * @property \Illuminate\Database\Eloquent\Collection|ServiceRequest[] $requests Заявки в данной воронке
 */
class Pipeline extends Model
{
    /**
     * Массово заполняемые поля.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'organization_id',
        'request_type_id',
        'name'
    ];

    /**
     * Связь с организацией (tenant).
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Стадии воронки.
     *
     * Возвращает стадии, отсортированные по позиции.
     *
     * @return HasMany
     */
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class)
            ->orderBy('position');
    }

    /**
     * Тип процесса.
     *
     * @return BelongsTo
     */
    public function requestType(): BelongsTo
    {
        return $this->belongsTo(RequestType::class);
    }

    /**
     * Заявки данной воронки.
     *
     * @return HasMany
     */
    public function requests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }
}
