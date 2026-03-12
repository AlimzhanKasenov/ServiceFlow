<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePipelinePermissionsTable
 *
 * Таблица прав ролей на pipeline.
 *
 * Определяет какие действия роль может выполнять
 * внутри конкретной воронки процессов.
 *
 * Пример:
 *
 * Pipeline: HR
 * Role: manager
 * can_create: true
 * can_move: true
 * can_close: false
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Создаёт таблицу pipeline_permissions.
     */
    public function up(): void
    {
        Schema::create('pipeline_permissions', function (Blueprint $table) {

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
             * Role
             */
            $table->foreignId('role_id')
                ->constrained()
                ->cascadeOnDelete();

            /**
             * Разрешения внутри pipeline
             */

            $table->boolean('can_view')->default(false);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_move')->default(false);
            $table->boolean('can_close')->default(false);
            $table->boolean('can_manage')->default(false);

            /**
             * Laravel timestamps
             */
            $table->timestamps();

            /**
             * Уникальность
             */
            $table->unique([
                'pipeline_id',
                'role_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipeline_permissions');
    }
};
