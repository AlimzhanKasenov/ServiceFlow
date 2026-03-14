<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
