<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_answer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('poll_id');
            $table->timestamps();
        });

        Schema::create('poll_answer_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('poll_answer_id');
            $table->unique(['poll_answer_id', 'locale']);
            $table->foreign('poll_answer_id')
                ->references('id')
                ->on('poll_answer');

            $table->string('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poll_answer_translations');
        Schema::dropIfExists('poll_answer');
    }
};
