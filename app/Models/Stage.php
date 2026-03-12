<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StageTransition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property int $id Уникальный идентификатор стадии
 * @property int $pipeline_id Воронка к которой относится стадия
 * @property string $name Название стадии
 * @property int $position Позиция стадии в воронке (порядок этапов)
 * @property string $type Тип стадии (start, process, approval, end)
 * @property \Illuminate\Support\Carbon|null $created_at Дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at Дата обновления
 *
 * Связи модели:
 *
 * @property Pipeline $pipeline Воронка процесса
 * @property \Illuminate\Database\Eloquent\Collection|ServiceRequest[] $requests Заявки на данной стадии
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
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Переходы ИЗ текущей стадии
     *
     * Например:
     * Processing → Approval
     * Processing → Done
     */
    public function outgoingTransitions(): HasMany
    {
        return $this->hasMany(StageTransition::class, 'from_stage_id');
    }

    /**
     * Переходы В текущую стадию
     *
     * Например:
     * New → Processing
     */
    public function incomingTransitions(): HasMany
    {
        return $this->hasMany(StageTransition::class, 'to_stage_id');
    }
}
