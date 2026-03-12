<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAutomationRulesTable
 *
 * Таблица правил автоматизации workflow.
 *
 * Определяет когда запускается автоматизация.
 *
 * Пример:
 *
 * event: stage_enter
 * stage_id: 5
 * active: true
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('automation_rules', function (Blueprint $table) {

            /**
             * Primary key
             */
            $table->id();

            /**
             * Pipeline
             */
            $table->foreignId('pipeline_id')
                ->constrained()
                ->cascadeOnDelete();

            /**
             * Stage
             */
            $table->foreignId('stage_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            /**
             * Event
             *
             * stage_enter
             * stage_exit
             */
            $table->string('event');

            /**
             * Rule name
             */
            $table->string('name');

            /**
             * Активно ли правило
             */
            $table->boolean('active')->default(true);

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
        Schema::dropIfExists('automation_rules');
    }
};
