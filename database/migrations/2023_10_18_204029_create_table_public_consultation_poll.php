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
        Schema::create('public_consultation_poll', function (Blueprint $table) {
            $table->unsignedBigInteger('public_consultation_id');
            $table->foreign('public_consultation_id')->references('id')->on('public_consultation');
            $table->unsignedBigInteger('poll_id');
            $table->foreign('poll_id')->references('id')->on('poll');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_consultation_poll');
    }
};
