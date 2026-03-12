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
 * IT Support:
 *   1. Новая заявка
 *   2. В работе
 *   3. Согласование
 *   4. Закрыта
 *
 * Каждая воронка:
 * - принадлежит организации (multi-tenant)
 * - связана с типом процесса
 * - содержит набор стадий
 *
 * @property int $id / уникальный идентификатор воронки
 * @property int $organization_id / организация (tenant системы)
 * @property int $request_type_id / тип процесса (IT, HR, Finance и т.д.)
 * @property string $name / название воронки
 * @property \Illuminate\Support\Carbon|null $created_at / дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at / дата обновления
 *
 * Связи модели:
 *
 * @property \Illuminate\Database\Eloquent\Collection|Stage[] $stages / стадии данной воронки
 * @property RequestType $requestType / тип процесса
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
     * Тип процесса, к которому относится воронка.
     *
     * @return BelongsTo
     */
    public function requestType(): BelongsTo
    {
        return $this->belongsTo(RequestType::class);
    }
}
