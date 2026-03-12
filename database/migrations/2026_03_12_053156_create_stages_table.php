<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateStagesTable
 *
 * Миграция создаёт таблицу stages (этапы воронки).
 *
 * Стадия — это шаг процесса внутри pipeline.
 * Пример:
 *
 * 1. Новая заявка (start)
 * 2. В работе (process)
 * 3. Согласование (approval)
 * 4. Завершено (end)
 *
 * Таблица используется в workflow-движке ServiceFlow.
 *
 * Структура:
 * stages
 * ├── id
 * ├── pipeline_id
 * ├── name
 * ├── position
 * ├── type
 * ├── created_at
 * └── updated_at
 */
return new class extends Migration
{
    /**
     * Выполнение миграции.
     *
     * Создаёт таблицу stages.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('stages', function (Blueprint $table) {

            // Уникальный идентификатор стадии
            $table->id();

            // Воронка, к которой относится стадия
            $table->foreignId('pipeline_id')
                ->constrained()
                ->cascadeOnDelete();

            // Название стадии (например: "В работе")
            $table->string('name');

            // Позиция стадии в воронке (порядок отображения)
            $table->integer('position');

            // Тип стадии:
            // start     - стартовая
            // process   - обычный этап
            // approval  - согласование
            // end       - завершение
            // cancel    - отмена
            $table->string('type');

            // Временные метки
            $table->timestamps();

            /*
             |--------------------------------------------------------------
             | Индексы (важно для производительности)
             |--------------------------------------------------------------
             | Часто будут выполняться запросы:
             | WHERE pipeline_id = ?
             | ORDER BY position
             |
             | Поэтому добавляем составной индекс.
             */
            $table->index(['pipeline_id', 'position']);
        });
    }

    /**
     * Откат миграции.
     *
     * Полностью удаляет таблицу stages.
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
        // Удаляем таблицу стадий
        Schema::dropIfExists('stages');
    }
};
