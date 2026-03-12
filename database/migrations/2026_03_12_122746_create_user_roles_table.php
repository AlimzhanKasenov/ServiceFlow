<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Связь пользователей и ролей
 */
return new class extends Migration
{
    /**
     * Run migrations
     */
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {

            $table->id();

            /**
             * Пользователь
             */
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            /**
             * Роль
             */
            $table->foreignId('role_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'user_id',
                'role_id'
            ]);

        });
    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
