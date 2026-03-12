<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsersTable
 *
 * Миграция создаёт таблицу пользователей системы ServiceFlow.
 *
 * Пользователь:
 * - принадлежит организации (multi-tenant)
 * - может создавать заявки
 * - может быть назначенным исполнителем
 * - имеет роль (admin, manager, employee)
 *
 * Также создаются служебные таблицы:
 * - password_reset_tokens
 * - sessions
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
        /*
         |--------------------------------------------------------------
         | Таблица пользователей
         |--------------------------------------------------------------
         */
        Schema::create('users', function (Blueprint $table) {

            // Уникальный идентификатор пользователя
            $table->id();

            // Организация (tenant системы)
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            // Имя пользователя
            $table->string('name');

            // Email (уникальный)
            $table->string('email')->unique();

            // Дата подтверждения email
            $table->timestamp('email_verified_at')->nullable();

            // Хэшированный пароль
            $table->string('password');

            // Роль пользователя
            // admin     - администратор организации
            // manager   - руководитель
            // employee  - сотрудник
            $table->string('role')->default('employee');

            // Remember me token
            $table->rememberToken();

            // Временные метки
            $table->timestamps();

            // Индекс по организации
            $table->index('organization_id');
        });

        /*
         |--------------------------------------------------------------
         | Таблица сброса паролей
         |--------------------------------------------------------------
         */
        Schema::create('password_reset_tokens', function (Blueprint $table) {

            // Email пользователя
            $table->string('email')->primary();

            // Токен сброса пароля
            $table->string('token');

            // Дата создания токена
            $table->timestamp('created_at')->nullable();
        });

        /*
         |--------------------------------------------------------------
         | Таблица сессий
         |--------------------------------------------------------------
         */
        Schema::create('sessions', function (Blueprint $table) {

            // ID сессии
            $table->string('id')->primary();

            // Пользователь (может быть null если гость)
            $table->foreignId('user_id')
                ->nullable()
                ->index();

            // IP-адрес
            $table->string('ip_address', 45)->nullable();

            // User-Agent браузера
            $table->text('user_agent')->nullable();

            // Данные сессии
            $table->longText('payload');

            // Последняя активность
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Откат миграции.
     * @return void
     */
    public function down(): void
    {
        // Сначала служебные таблицы
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');

        // Потом основную таблицу
        Schema::dropIfExists('users');
    }
};
