<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Организация пользователя
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Заявки созданные пользователем
     */
    public function createdRequests(): HasMany
    {
        return $this->hasMany(RequestModel::class, 'created_by');
    }

    /**
     * Назначенные заявки
     */
    public function assignedRequests(): HasMany
    {
        return $this->hasMany(RequestModel::class, 'assigned_to');
    }

    /**
     * Роли пользователя
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'user_roles'
        );
    }

    /**
     * Проверка роли
     */
    public function hasRole(string $roleCode): bool
    {
        return $this->roles()
            ->where('code', $roleCode)
            ->exists();
    }

    /**
     * Проверка разрешения
     */
    public function hasPermission(string $permissionCode): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permissionCode) {

                $q->where('code', $permissionCode);

            })
            ->exists();
    }

    /**
     * Проверка любого разрешения
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {

            if ($this->hasPermission($permission)) {
                return true;
            }

        }

        return false;
    }
}
