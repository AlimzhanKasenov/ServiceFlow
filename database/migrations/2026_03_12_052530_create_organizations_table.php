<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrganizationsTable
 *
 * Миграция создаёт таблицу organizations.
 *
 * Organization — это tenant системы ServiceFlow.
 * Каждая организация полностью изолирована и имеет:
 *
 * - своих пользователей
 * - свои типы процессов
 * - свои воронки
 * - свои заявки
 *
 * Multi-tenant архитектура строится на этом уровне.
 *
 * Структура:
 * organizations
 * ├── id
 * ├── name
 * ├── slug
 * ├── created_at
 * └── updated_at
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
        Schema::create('organizations', function (Blueprint $table) {

            // Уникальный идентификатор организации
            $table->id();

            // Название организации
            $table->string('name');

            // Уникальный slug (используется в URL, API, поддоменах)
            // Например: aurora-group
            $table->string('slug')->unique();

            // Временные метки
            $table->timestamps();

            /*
             |--------------------------------------------------------------
             | Индексы
             |--------------------------------------------------------------
             | Slug уже уникальный → индекс создаётся автоматически.
             | Можно добавить индекс по name если планируется поиск.
             */
            $table->index('name');
        });
    }

    /**
     * Откат миграции.
     *
     * Важно: если есть внешние ключи (users, requests и т.д.),
     * откат может потребовать предварительного удаления зависимых таблиц.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
