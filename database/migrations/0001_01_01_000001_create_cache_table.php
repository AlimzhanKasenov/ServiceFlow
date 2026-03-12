<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCacheTables
 *
 * Миграция создаёт служебные таблицы для хранения кэша
 * при использовании драйвера cache = database.
 *
 * Таблицы:
 * - cache         → хранение кэшированных данных
 * - cache_locks   → механизм блокировок (atomic locks)
 *
 * Используется если в .env:
 *
 * CACHE_STORE=database
 *
 * В production рекомендуется Redis,
 * но database cache подходит для fallback-сценариев.
 */
return new class extends Migration
{
    /**
     * Выполнение миграции.
     *
     * Создаёт таблицы для database cache.
     *
     * @return void
     */
    public function up(): void
    {
        /*
         |--------------------------------------------------------------
         | Таблица cache
         |--------------------------------------------------------------
         | Хранит закэшированные данные приложения.
         |
         | key        → ключ кэша
         | value      → сериализованное значение
         | expiration → timestamp истечения срока действия
         */
        Schema::create('cache', function (Blueprint $table) {

            // Ключ кэша (уникальный)
            $table->string('key')->primary();

            // Значение кэша (сериализованные данные)
            $table->mediumText('value');

            // Время истечения (unix timestamp)
            $table->integer('expiration')->index();
        });

        /*
         |--------------------------------------------------------------
         | Таблица cache_locks
         |--------------------------------------------------------------
         | Используется для атомарных блокировок.
         | Например:
         | Cache::lock('process-lock')->get();
         */
        Schema::create('cache_locks', function (Blueprint $table) {

            // Ключ блокировки
            $table->string('key')->primary();

            // Владелец блокировки
            $table->string('owner');

            // Время истечения блокировки
            $table->integer('expiration')->index();
        });
    }

    /**
     * Откат миграции.
     *
     * Удаляет таблицы database cache.
     *
     * Важно: сначала удаляем таблицу блокировок.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }
};
