<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePipelinesTable
 *
 * Миграция создаёт таблицу pipelines (воронки процессов).
 *
 * Воронка определяет последовательность стадий,
 * через которые проходит заявка определённого типа.
 *
 * Пример:
 *
 * IT Support:
 *   - Новая
 *   - В работе
 *   - Согласование
 *   - Завершено
 *
 * Таблица используется в multi-tenant архитектуре,
 * поэтому каждая воронка принадлежит организации.
 *
 * Структура:
 * pipelines
 * ├── id
 * ├── organization_id
 * ├── request_type_id
 * ├── name
 * ├── created_at
 * └── updated_at
 */
return new class extends Migration
{
    /**
     * Выполнение миграции.
     *
     * Создаёт таблицу pipelines.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('pipelines', function (Blueprint $table) {

            // Уникальный идентификатор воронки
            $table->id();

            // Организация (tenant системы)
            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            // Тип процесса (IT, HR, Finance и т.д.)
            $table->foreignId('request_type_id')
                ->constrained()
                ->cascadeOnDelete();

            // Название воронки
            $table->string('name');

            // Временные метки
            $table->timestamps();

            /*
             |--------------------------------------------------------------
             | Индексы
             |--------------------------------------------------------------
             | Частые запросы:
             | - получение всех воронок организации
             | - получение воронок по типу процесса
             */
            $table->index('organization_id');
            $table->index('request_type_id');
        });
    }

    /**
     * Откат миграции.
     *
     * Полностью удаляет таблицу pipelines.
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
        // Удаляем таблицу воронок
        Schema::dropIfExists('pipelines');
    }
};
