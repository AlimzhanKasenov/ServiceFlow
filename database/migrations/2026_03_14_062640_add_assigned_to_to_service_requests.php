<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddAssignedToToRequestsTable
 *
 * Добавляет в таблицу requests поле assigned_to
 * для назначения исполнителя заявки.
 */
return new class extends Migration
{
    /**
     * Применить миграцию
     */
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            /**
             * Исполнитель заявки
             *
             * nullable:
             * заявка может быть ещё не назначена
             *
             * constrained('users'):
             * внешний ключ на таблицу users
             *
             * nullOnDelete():
             * если пользователя удалят, assigned_to станет null
             */
            if (! Schema::hasColumn('requests', 'assigned_to')) {
                $table->foreignId('assigned_to')
                    ->nullable()
                    ->after('created_by')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Откатить миграцию
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            /**
             * Сначала удаляем внешний ключ,
             * потом само поле
             */
            if (Schema::hasColumn('requests', 'assigned_to')) {
                $table->dropConstrainedForeignId('assigned_to');
            }
        });
    }
};
