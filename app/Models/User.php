<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class User
 *
 * Модель пользователя системы ServiceFlow.
 *
 * Пользователь:
 * - принадлежит организации (multi-tenant)
 * - может создавать заявки
 * - может быть назначенным исполнителем
 *
 * @property int $id / уникальный идентификатор пользователя
 * @property int|null $organization_id / организация (tenant системы)
 * @property string $name / имя пользователя
 * @property string $email / email пользователя
 * @property string|null $email_verified_at / дата подтверждения email
 * @property string $password / хэшированный пароль
 * @property string|null $remember_token / токен "запомнить меня"
 * @property \Illuminate\Support\Carbon|null $created_at / дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at / дата обновления
 *
 * Связи модели:
 *
 * @property Organization|null $organization / организация пользователя
 * @property \Illuminate\Database\Eloquent\Collection|RequestModel[] $createdRequests / заявки созданные пользователем
 * @property \Illuminate\Database\Eloquent\Collection|RequestModel[] $assignedRequests / заявки назначенные пользователю
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Массово заполняемые поля.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id', // организация пользователя
        'name',            // имя
        'email',           // email
        'password',        // пароль
    ];

    /**
     * Поля скрытые при сериализации.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Приведение типов атрибутов.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Организация пользователя.
     *
     * Один пользователь принадлежит одной организации.
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Заявки созданные пользователем.
     *
     * @return HasMany
     */
    public function createdRequests(): HasMany
    {
        return $this->hasMany(RequestModel::class, 'created_by');
    }

    /**
     * Заявки назначенные пользователю.
     *
     * @return HasMany
     */
    public function assignedRequests(): HasMany
    {
        return $this->hasMany(RequestModel::class, 'assigned_to');
    }
}
