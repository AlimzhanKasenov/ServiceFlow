<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAutomationActionsTable
 *
 * Таблица действий автоматизации.
 *
 * Пример действий:
 *
 * send_email
 * create_task
 * webhook
 */
return new class extends Migration
{
    /**
     * Run migrations
     */
    public function up(): void
    {
        Schema::create('automation_actions', function (Blueprint $table) {

            /**
             * Primary key
             */
            $table->id();

            /**
             * Automation rule
             */
            $table->foreignId('rule_id')
                ->constrained('automation_rules')
                ->cascadeOnDelete();

            /**
             * Тип действия
             */
            $table->string('type');

            /**
             * JSON параметры действия
             */
            $table->json('config')->nullable();

            /**
             * Порядок выполнения
             */
            $table->integer('position')->default(0);

            /**
             * timestamps
             */
            $table->timestamps();
        });
    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('automation_actions');
    }
};
