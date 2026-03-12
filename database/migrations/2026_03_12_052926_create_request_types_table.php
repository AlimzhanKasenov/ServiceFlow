<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRequestTypesTable
 *
 * Миграция создаёт таблицу request_types (типы процессов).
 *
 * Тип процесса — это бизнес-направление заявок внутри организации.
 *
 * Примеры:
 * - IT Support
 * - HR
 * - Finance
 * - Legal
 *
 * Каждый тип:
 * - принадлежит организации (multi-tenant архитектура)
 * - может содержать несколько воронок (pipelines)
 *
 * Структура:
 * request_types
 * ├── id
 * ├── organization_id
 * ├── name
 * ├── description
 * ├── icon
 * ├── created_at
 * └── updated_at
 */
return new class extends Migration
{
    /**
     * Выполнение миграции.
     *
     * Создаёт таблицу request_types.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('request_types', function (Blueprint $table) {

            // Уникальный идентификатор типа процесса
            $table->id();

            // Организация (tenant системы)
            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            // Название типа процесса (например: IT Support)
            $table->string('name');

            // Подробное описание типа процесса
            $table->text('description')->nullable();

            // Иконка (название, класс иконки или путь к файлу)
            $table->string('icon')->nullable();

            // Временные метки
            $table->timestamps();

            /*
             |--------------------------------------------------------------
             | Индексы
             |--------------------------------------------------------------
             | Частые запросы:
             | - получение типов по организации
             | - поиск по названию
             */
            $table->index('organization_id');

            /*
             |--------------------------------------------------------------
             | Уникальность
             |--------------------------------------------------------------
             | Запрещаем создавать два одинаковых типа процесса
             | внутри одной организации.
             */
            $table->unique(['organization_id', 'name']);
        });
    }

    /**
     * Откат миграции.
     *
     * Полностью удаляет таблицу request_types.
     *
     * Используется при:
     * - rollback
     * - reset базы
     * - тестировании
     *
     * @return void
     */
    public function down(): void
    {
        // Удаляем таблицу типов процессов
        Schema::dropIfExists('request_types');
    }
};
