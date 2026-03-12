<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddOrganizationAndRoleToUsersTable
 *
 * Добавляет в таблицу users:
 *
 * - organization_id → принадлежность к организации (multi-tenant)
 * - role            → роль пользователя внутри организации
 *
 * Это необходимо для:
 * - разграничения доступа
 * - управления правами
 * - workflow логики
 */
return new class extends Migration
{
    /**
     * Выполнение миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            /*
             |--------------------------------------------------------------
             | organization_id
             |--------------------------------------------------------------
             | Пользователь принадлежит организации.
             | nullable — на случай супер-админа системы.
             */
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            /*
             |--------------------------------------------------------------
             | role
             |--------------------------------------------------------------
             | Роль пользователя внутри организации.
             |
             | Примеры:
             | - admin
             | - manager
             | - employee
             | - user (базовая)
             */
            $table->string('role')->default('user');

            // Индекс для ускорения выборок по организации
            $table->index('organization_id');
        });
    }

    /**
     * Откат миграции.
     *
     * Важно:
     * - сначала удаляем внешний ключ
     * - потом колонку
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Удаляем внешний ключ
            $table->dropForeign(['organization_id']);

            // Удаляем индекс
            $table->dropIndex(['organization_id']);

            // Удаляем колонки
            $table->dropColumn(['organization_id', 'role']);
        });
    }
};
