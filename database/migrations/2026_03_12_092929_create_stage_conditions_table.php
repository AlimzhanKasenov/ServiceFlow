<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateStageConditionsTable
 *
 * Таблица условий переходов между стадиями workflow.
 *
 * Позволяет ограничивать переходы
 * на основе данных заявки.
 *
 * Пример:
 *
 * transition_id: 5
 * field: approved
 * operator: =
 * value: true
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stage_conditions', function (Blueprint $table) {

            /**
             * Primary key
             */
            $table->id();

            /**
             * Transition
             */
            $table->foreignId('transition_id')
                ->constrained('stage_transitions')
                ->cascadeOnDelete();

            /**
             * Поле заявки
             */
            $table->string('field');

            /**
             * Оператор
             *
             * =, !=, >, <, >=, <=
             */
            $table->string('operator');

            /**
             * Значение
             */
            $table->string('value');

            /**
             * Laravel timestamps
             */
            $table->timestamps();
        });
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stage_conditions');
    }
};
