<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRequestActivitiesTable
 *
 * Таблица истории действий заявки.
 *
 * Используется для построения таймлайна заявки:
 *
 * examples:
 *
 * - заявка создана
 * - заявка перемещена на стадию
 * - изменён приоритет
 * - назначен исполнитель
 * - добавлен комментарий
 *
 * Это основа audit trail системы.
 */
return new class extends Migration
{
    /**
     * Выполнение миграции
     */
    public function up(): void
    {
        Schema::create('request_activities', function (Blueprint $table) {

            $table->id();

            /**
             * Заявка
             */
            $table->foreignId('request_id')
                ->constrained('requests')
                ->cascadeOnDelete();

            /**
             * Пользователь совершивший действие
             */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /**
             * Тип действия
             *
             * created
             * stage_changed
             * priority_changed
             * assigned
             * comment_added
             */
            $table->string('type');

            /**
             * Дополнительные данные
             *
             * JSON:
             * {
             *   "from_stage": 1,
             *   "to_stage": 2
             * }
             */
            $table->json('data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Откат миграции
     */
    public function down(): void
    {
        Schema::dropIfExists('request_activities');
    }
};
