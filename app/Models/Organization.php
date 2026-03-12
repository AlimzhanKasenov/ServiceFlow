<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Organization
 *
 * Модель организации (tenant) системы ServiceFlow.
 *
 * Организация — это изолированное пространство данных.
 * Вся система построена по multi-tenant архитектуре,
 * где каждая организация имеет:
 *
 * - своих пользователей
 * - свои типы процессов
 * - свои воронки
 * - свои заявки
 *
 * @property int $id / уникальный идентификатор организации
 * @property string $name / название организации
 * @property string $slug / уникальный символьный идентификатор (для URL, API, поддоменов)
 * @property \Illuminate\Support\Carbon|null $created_at / дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at / дата обновления
 *
 * Связи модели:
 *
 * @property \Illuminate\Database\Eloquent\Collection|User[] $users / пользователи организации
 * @property \Illuminate\Database\Eloquent\Collection|RequestType[] $requestTypes / типы процессов
 * @property \Illuminate\Database\Eloquent\Collection|Pipeline[] $pipelines / воронки организации
 * @property \Illuminate\Database\Eloquent\Collection|RequestModel[] $requests / заявки организации
 */
class Organization extends Model
{
    use HasFactory;

    /**
     * Массово заполняемые поля.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name', // название организации
        'slug', // уникальный slug (например: aurora-group)
    ];

    /**
     * Пользователи организации.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Типы процессов организации.
     *
     * @return HasMany
     */
    public function requestTypes(): HasMany
    {
        return $this->hasMany(RequestType::class);
    }

    /**
     * Воронки организации.
     *
     * @return HasMany
     */
    public function pipelines(): HasMany
    {
        return $this->hasMany(Pipeline::class);
    }

    /**
     * Заявки организации.
     *
     * @return HasMany
     */
    public function requests(): HasMany
    {
        return $this->hasMany(RequestModel::class);
    }
}
