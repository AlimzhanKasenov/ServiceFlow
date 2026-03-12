<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Role
 *
 * Роль пользователя системы ServiceFlow.
 *
 * Роль определяет набор разрешений (permissions),
 * которые получает пользователь.
 *
 * Примеры ролей:
 *
 * admin
 * manager
 * approver
 * user
 *
 * Таблица: roles
 *
 * @property int $id
 * @property string $name
 * @property string $code
 *
 * Связи:
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<User> $users
 * @property-read \Illuminate\Database\Eloquent\Collection<Permission> $permissions
 */
class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'code'
    ];

    /**
     * Пользователи роли
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Разрешения роли
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions'
        );
    }
}
