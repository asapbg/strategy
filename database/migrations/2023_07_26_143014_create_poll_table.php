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
        Schema::create('poll', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('consultation_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->tinyInteger('is_once')->default(0);
            $table->tinyInteger('only_registered')->default(0);
            $table->tinyInteger('has_entry')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('poll_questions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('poll_id');
            $table->tinyInteger('type')->default(1);
            $table->foreign('poll_id')->references('id')->on('polls');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('poll_question_options', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('poll_question_id');
            $table->foreign('poll_question_id')->references('id')->on('poll_questions');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_polls', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('poll_id');
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_poll_options', function (Blueprint $table) {
            $table->bigInteger('user_poll_id');
            $table->bigInteger('poll_question_option_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_poll_options');
        Schema::dropIfExists('user_polls');
        Schema::dropIfExists('poll_question_options');
        Schema::dropIfExists('poll_questions');
        Schema::dropIfExists('poll');
    }
};
