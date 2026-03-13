<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Добавление SLA полей в таблицу заявок
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {

            /**
             * SLA время в минутах
             */
            $table->integer('sla_minutes')->nullable();

            /**
             * Время начала SLA
             */
            $table->timestamp('sla_started_at')->nullable();

            /**
             * Дедлайн SLA
             */
            $table->timestamp('sla_due_at')->nullable();

            /**
             * Нарушен ли SLA
             */
            $table->boolean('sla_breached')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {

            $table->dropColumn([
                'sla_minutes',
                'sla_started_at',
                'sla_due_at',
                'sla_breached'
            ]);
        });
    }
};
