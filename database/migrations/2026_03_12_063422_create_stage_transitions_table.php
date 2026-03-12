<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Таблица переходов между стадиями процесса.
 *
 * StageTransition определяет разрешённые переходы
 * между стадиями внутри pipeline.
 *
 * Пример:
 *
 * start → processing
 * processing → approval
 * approval → completed
 *
 * Это позволяет строить workflow и валидировать
 * движение заявки между этапами.
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
             * Pipeline которому принадлежит переход
             */
            $table->foreignId('pipeline_id')
                ->constrained()
                ->cascadeOnDelete();

            /**
             * Стадия ОТКУДА происходит переход
             */
            $table->foreignId('from_stage_id')
                ->constrained('stages')
                ->cascadeOnDelete();

            /**
             * Стадия КУДА происходит переход
             */
            $table->foreignId('to_stage_id')
                ->constrained('stages')
                ->cascadeOnDelete();

            /**
             * Название перехода
             */
            $table->string('name');

            /**
             * Требуется ли согласование
             */
            $table->boolean('requires_approval')
                ->default(false);

            /**
             * Позиция для UI
             */
            $table->integer('position')
                ->default(0);

            $table->timestamps();
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
