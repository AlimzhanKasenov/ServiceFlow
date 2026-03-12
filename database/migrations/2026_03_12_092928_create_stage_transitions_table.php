<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateStageTransitionsTable
 *
 * Таблица описывает допустимые переходы между стадиями workflow.
 *
 * Пример:
 *
 * start → processing
 * processing → approval
 * approval → completed
 *
 * Система использует эту таблицу чтобы:
 * - проверять допустимость переходов
 * - строить workflow
 * - валидировать перемещение заявок
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stage_transitions', function (Blueprint $table) {

            $table->id();

            /**
             * Pipeline к которому относится переход
             */
            $table->foreignId('pipeline_id')
                ->constrained()
                ->cascadeOnDelete();

            /**
             * Стадия ИЗ которой происходит переход
             */
            $table->foreignId('from_stage_id')
                ->constrained('stages')
                ->cascadeOnDelete();

            /**
             * Стадия В которую происходит переход
             */
            $table->foreignId('to_stage_id')
                ->constrained('stages')
                ->cascadeOnDelete();

            /**
             * Название перехода
             *
             * Пример:
             * "Отправить на согласование"
             * "Закрыть заявку"
             */
            $table->string('name')->nullable();

            /**
             * Требуется ли согласование
             */
            $table->boolean('requires_approval')
                ->default(false);

            /**
             * Позиция перехода
             * (для UI порядка кнопок)
             */
            $table->integer('position')
                ->default(0);

            $table->timestamps();

            /**
             * Индексы
             */
            $table->index(['pipeline_id']);
            $table->index(['from_stage_id']);
            $table->index(['to_stage_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stage_transitions');
    }
};
