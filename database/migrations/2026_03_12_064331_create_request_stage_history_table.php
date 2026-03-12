<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRequestStageHistoryTable
 *
 * Таблица истории переходов заявок между стадиями.
 *
 * Хранит полный аудит workflow:
 *
 * - какая заявка была переведена
 * - из какой стадии
 * - в какую стадию
 * - кем выполнен переход
 * - когда произошёл переход
 *
 * Это основа audit trail системы ServiceFlow.
 *
 * Пример записи:
 *
 * request_id: 25
 * from_stage: new
 * to_stage: approval
 * user: 4
 * moved_at: 2026-03-12 12:45
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Создаёт таблицу истории переходов стадий заявок.
     */
    public function up(): void
    {
        Schema::create('request_stage_history', function (Blueprint $table) {

            /**
             * Primary key
             */
            $table->id();

            /**
             * Заявка
             */
            $table->foreignId('request_id')
                ->constrained()
                ->cascadeOnDelete();

            /**
             * Стадия из которой произошёл переход
             */
            $table->foreignId('from_stage_id')
                ->nullable()
                ->constrained('stages')
                ->nullOnDelete();

            /**
             * Стадия в которую перешла заявка
             */
            $table->foreignId('to_stage_id')
                ->constrained('stages')
                ->cascadeOnDelete();

            /**
             * Пользователь совершивший переход
             */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /**
             * Время перехода
             */
            $table->timestamp('moved_at');

            /**
             * Стандартные поля Laravel
             *
             * created_at
             * updated_at
             */
            $table->timestamps();

            /**
             * Индексы для быстрого поиска
             */
            $table->index('request_id');
            $table->index('moved_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Удаляет таблицу истории переходов.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_stage_history');
    }
};
