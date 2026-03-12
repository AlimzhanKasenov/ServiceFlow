<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePermissionsTable
 *
 * Таблица разрешений системы ServiceFlow.
 *
 * Разрешения определяют какие действия может выполнять роль.
 *
 * Примеры permissions:
 *
 * request.create
 * request.view
 * request.move
 * request.close
 * request.approve
 * pipeline.manage
 * system.admin
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Создание таблицы permissions.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {

            /**
             * Primary key
             */
            $table->id();

            /**
             * Название разрешения
             */
            $table->string('name');

            /**
             * Системный код разрешения
             */
            $table->string('code')->unique();

            /**
             * Описание
             */
            $table->text('description')->nullable();

            /**
             * Стандартные поля Laravel
             */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Удаление таблицы permissions.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
