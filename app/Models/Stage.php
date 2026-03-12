<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Stage
 *
 * Модель стадии (этапа) воронки процесса ServiceFlow.
 *
 * Стадия определяет текущий шаг обработки заявки.
 * Каждая стадия принадлежит определённой воронке (pipeline)
 * и может содержать множество заявок.
 *
 * Пример стадий:
 *
 * start        → новая заявка
 * process      → обработка
 * approval     → согласование
 * completed    → завершено
 *
 * @property int $id / уникальный идентификатор стадии
 * @property int $pipeline_id / воронка к которой относится стадия
 * @property string $name / название стадии
 * @property int $position / позиция стадии в воронке (порядок этапов)
 * @property string $type / тип стадии (start, process, approval, end)
 * @property \Illuminate\Support\Carbon|null $created_at / дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at / дата обновления
 *
 * Связи модели:
 *
 * @property Pipeline $pipeline / воронка процесса
 * @property \Illuminate\Database\Eloquent\Collection|RequestModel[] $requests / заявки находящиеся на этой стадии
 */
class Stage extends Model
{
    /**
     * Массово заполняемые поля.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'pipeline_id',
        'name',
        'position',
        'type'
    ];

    /**
     * Связь со воронкой процесса.
     *
     * Одна стадия принадлежит одной воронке.
     *
     * @return BelongsTo
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Связь со заявками.
     *
     * На одной стадии может находиться множество заявок.
     *
     * @return HasMany
     */
    public function requests(): HasMany
    {
        return $this->hasMany(RequestModel::class);
    }
}
