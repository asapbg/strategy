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
            $table->bigIncrements('id');
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

        Schema::create('poll_question', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('poll_id');
            $table->tinyInteger('type')->default(1);
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('poll_question_option', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('poll_question_id');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_poll', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('poll_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_poll_option', function (Blueprint $table) {
            $table->unsignedBigInteger('user_poll_id');
            $table->unsignedBigInteger('poll_question_option_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_poll_option');
        Schema::dropIfExists('user_poll');
        Schema::dropIfExists('poll_question_option');
        Schema::dropIfExists('poll_question');
        Schema::dropIfExists('poll');
    }
};
