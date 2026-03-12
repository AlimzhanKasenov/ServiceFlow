<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RequestModel
 *
 * Основная модель заявки системы ServiceFlow.
 *
 * Заявка — это центральная сущность workflow-движка.
 * Она проходит через:
 *  - тип процесса (RequestType)
 *  - воронку (Pipeline)
 *  - стадии (Stage)
 *
 * Каждая заявка принадлежит организации (multi-tenant архитектура)
 * и может иметь назначенного исполнителя.
 *
 * Таблица: requests
 *
 * Основная модель заявки системы ServiceFlow.
 *
 * @property int $id / уникальный идентификатор заявки
 * @property int $organization_id / организация (tenant системы)
 * @property int $request_type_id / тип процесса (IT, HR, Finance и т.д.)
 * @property int $pipeline_id / воронка процесса
 * @property int $stage_id / текущая стадия заявки
 * @property string $title / заголовок заявки
 * @property string|null $description / подробное описание заявки
 * @property string $status / статус заявки (open, closed, cancelled, paused)
 * @property int $created_by / пользователь создавший заявку
 * @property int|null $assigned_to / пользователь назначенный исполнителем
 * @property string $priority / приоритет заявки (low, normal, high, urgent)
 * @property array|null $data / JSON данные динамических полей заявки
 * @property \Illuminate\Support\Carbon|null $created_at / дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at / дата обновления
 *
 * Связи модели:
 *
 * @property Pipeline $pipeline / воронка заявки
 * @property Stage $stage / текущая стадия
 * @property User $creator / пользователь создавший заявку
 * @property User|null $assignee / назначенный исполнитель
 *
 * Используется как центральная модель системы ServiceFlow.
 */
class RequestModel extends Model
{
    /**
     * Имя таблицы в базе данных.
     *
     * @var string
     */
    protected $table = 'requests';

    /**
     * Массово заполняемые поля.
     *
     * Эти поля можно передавать через:
     * RequestModel::create()
     * RequestModel::update()
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'request_type_id',
        'pipeline_id',
        'stage_id',
        'title',
        'description',
        'status',
        'created_by',
        'assigned_to',
        'priority',
        'data',
    ];

    /**
     * Приведение типов атрибутов модели.
     *
     * data хранится в БД как JSON,
     * но в модели автоматически преобразуется в массив.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Воронка заявки.
     *
     * @return BelongsTo
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Текущая стадия заявки.
     *
     * @return BelongsTo
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Пользователь создавший заявку.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Пользователь назначенный исполнителем.
     *
     * Может быть null.
     *
     * @return BelongsTo
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
