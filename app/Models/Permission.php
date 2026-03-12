<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Permission
 *
 * Модель разрешения системы ServiceFlow.
 *
 * Разрешение определяет возможность выполнять
 * конкретное действие в системе.
 *
 * Примеры:
 *
 * request.create
 * request.move
 * request.close
 * pipeline.manage
 *
 * Таблица: permissions
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * Связи:
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<Role> $roles
 */
class Permission extends Model
{
    /**
     * Таблица
     */
    protected $table = 'permissions';

    /**
     * Mass assignable
     */
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    /**
     * Роли с данным разрешением
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions'
        );
    }
}
