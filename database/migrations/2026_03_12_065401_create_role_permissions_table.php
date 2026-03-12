<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRolePermissionsTable
 *
 * Связь ролей и разрешений.
 *
 * Один Role → много Permissions
 * Один Permission → много Roles
 *
 * Это pivot таблица RBAC системы.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {

            /**
             * Primary key
             */
            $table->id();

            /**
             * Роль
             */
            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();

            /**
             * Разрешение
             */
            $table->foreignId('permission_id')
                ->constrained('permissions')
                ->cascadeOnDelete();

            /**
             * Уникальность связи
             */
            $table->unique([
                'role_id',
                'permission_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
