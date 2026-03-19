<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Таблица наблюдателей заявки
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_watchers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('request_id')
                ->constrained('requests')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['request_id', 'user_id']); // чтобы не дублировать
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_watchers');
    }
};
