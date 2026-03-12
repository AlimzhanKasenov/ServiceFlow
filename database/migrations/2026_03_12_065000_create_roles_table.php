<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRolesTable
 *
 * Создаёт таблицу ролей пользователей системы ServiceFlow.
 *
 * Таблица используется системой авторизации
 * и определяет уровень доступа пользователей.
 *
 * Примеры ролей:
 *
 * admin
 * manager
 * approver
 * user
 *
 * Структура таблицы:
 *
 * id          — первичный ключ
 * name        — человеко-читаемое название роли
 * code        — системный код роли
 * created_at  — дата создания
 * updated_at  — дата обновления
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Создание таблицы roles.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {

            /**
             * Primary key
             */
            $table->id();

            /**
             * Название роли
             * Пример: "Administrator"
             */
            $table->string('name');

            /**
             * Системный код роли
             * Пример: admin
             */
            $table->string('code')->unique();

            /**
             * Стандартные поля Laravel
             *
             * created_at
             * updated_at
             */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Удаляет таблицу roles.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
