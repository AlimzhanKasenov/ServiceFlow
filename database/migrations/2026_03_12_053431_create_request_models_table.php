<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRequestsTable
 *
 * Миграция создаёт основную таблицу заявок системы ServiceFlow.
 *
 * Таблица хранит:
 * - базовую информацию о заявке
 * - принадлежность к организации (multi-tenant)
 * - тип процесса
 * - текущую воронку и стадию
 * - ответственных пользователей
 * - произвольные данные процесса
 *
 * Структура таблицы:
 *
 * requests
 * ├── id
 * ├── organization_id   → организация (tenant)
 * ├── request_type_id   → тип процесса
 * ├── pipeline_id       → воронка
 * ├── stage_id          → текущая стадия
 * ├── title             → название заявки
 * ├── description       → описание
 * ├── status            → статус заявки
 * ├── created_by        → кто создал
 * ├── assigned_to       → исполнитель
 * ├── priority          → приоритет
 * ├── data              → json данные процесса
 * ├── created_at
 * └── updated_at
 *
 * Используется в ядре workflow-движка ServiceFlow.
 */
return new class extends Migration
{
    /**
     * Выполнение миграции.
     *
     * Создаёт таблицу requests.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {

            // Primary key заявки
            $table->id();

            // Организация (multi-tenant архитектура)
            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            // Тип процесса (IT, HR, Finance и т.д.)
            $table->foreignId('request_type_id')
                ->constrained()
                ->cascadeOnDelete();

            // Воронка процесса
            $table->foreignId('pipeline_id')
                ->constrained()
                ->cascadeOnDelete();

            // Текущая стадия заявки
            $table->foreignId('stage_id')
                ->constrained()
                ->cascadeOnDelete();

            // Название заявки
            $table->string('title');

            // Подробное описание
            $table->text('description')->nullable();

            // Статус заявки
            // open | closed | cancelled | paused
            $table->string('status')->default('open');

            // Пользователь создавший заявку
            $table->foreignId('created_by')
                ->constrained('users');

            // Ответственный исполнитель
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users');

            // Приоритет заявки
            // low | normal | high | urgent
            $table->string('priority')->default('normal');

            // Дополнительные данные процесса
            // хранит динамические поля заявки
            $table->json('data')->nullable();

            // Временные метки
            $table->timestamps();
        });
    }

    /**
     * Откат миграции.
     *
     * Полностью удаляет таблицу requests.
     *
     * Используется при:
     * - rollback миграций
     * - reset базы
     * - тестировании
     *
     * @return void
     */
    public function down(): void
    {
        // Удаляем таблицу заявок
        Schema::dropIfExists('requests');
    }
};
