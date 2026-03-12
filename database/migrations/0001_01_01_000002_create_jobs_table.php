<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateJobsTables
 *
 * Миграция создаёт инфраструктурные таблицы очередей Laravel.
 *
 * Используется если в .env:
 *
 * QUEUE_CONNECTION=database
 *
 * Таблицы:
 * - jobs         → очередь задач
 * - job_batches  → пакетная обработка задач
 * - failed_jobs  → неуспешные задачи
 *
 * В production рекомендуется Redis,
 * но database driver подходит для fallback или простых инсталляций.
 */
return new class extends Migration
{
    /**
     * Выполнение миграции.
     *
     * @return void
     */
    public function up(): void
    {
        /*
         |--------------------------------------------------------------
         | Таблица jobs
         |--------------------------------------------------------------
         | Хранит задачи, ожидающие выполнения.
         */
        Schema::create('jobs', function (Blueprint $table) {

            // Уникальный идентификатор задачи
            $table->id();

            // Имя очереди (например: default, notifications, sla)
            $table->string('queue');

            // Сериализованный payload задачи
            $table->longText('payload');

            // Количество попыток выполнения
            $table->unsignedTinyInteger('attempts');

            // Время резервирования (когда worker взял задачу)
            $table->unsignedInteger('reserved_at')->nullable();

            // Когда задача станет доступной
            $table->unsignedInteger('available_at');

            // Время создания задачи
            $table->unsignedInteger('created_at');

            // Индекс для быстрого выбора задач
            $table->index(['queue', 'reserved_at', 'available_at']);
        });

        /*
         |--------------------------------------------------------------
         | Таблица job_batches
         |--------------------------------------------------------------
         | Используется для пакетной обработки задач (Bus::batch()).
         */
        Schema::create('job_batches', function (Blueprint $table) {

            // ID batch-а
            $table->string('id')->primary();

            // Название пакета
            $table->string('name');

            // Общее количество задач
            $table->integer('total_jobs');

            // Оставшиеся задачи
            $table->integer('pending_jobs');

            // Количество проваленных задач
            $table->integer('failed_jobs');

            // Список ID проваленных задач
            $table->longText('failed_job_ids');

            // Дополнительные опции
            $table->mediumText('options')->nullable();

            // Время отмены batch-а
            $table->integer('cancelled_at')->nullable();

            // Дата создания
            $table->integer('created_at');

            // Дата завершения
            $table->integer('finished_at')->nullable();
        });

        /*
         |--------------------------------------------------------------
         | Таблица failed_jobs
         |--------------------------------------------------------------
         | Хранит задачи, которые завершились с ошибкой.
         */
        Schema::create('failed_jobs', function (Blueprint $table) {

            // ID записи
            $table->id();

            // UUID задачи
            $table->string('uuid')->unique();

            // Соединение очереди
            $table->text('connection');

            // Имя очереди
            $table->text('queue');

            // Payload задачи
            $table->longText('payload');

            // Текст исключения
            $table->longText('exception');

            // Время ошибки
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Откат миграции.
     *
     * Удаляем в обратном порядке.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
    }
};
