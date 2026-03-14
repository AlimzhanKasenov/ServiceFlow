<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automation_rules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pipeline_id')->constrained();

            /**
             * событие
             * stage_changed
             * request_created
             */
            $table->string('event');

            /**
             * стадия
             */
            $table->foreignId('stage_id')->nullable()->constrained('stages');

            /**
             * действие
             * assign_user
             * webhook
             * add_comment
             */
            $table->string('action');

            /**
             * параметры действия
             */
            $table->json('config')->nullable();

            $table->integer('position')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_rules');
    }
};
