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
        Schema::create('pris_change_pris', function (Blueprint $table){
            $table->unsignedBigInteger('pris_id');
            $table->foreign('pris_id')->references('id')->on('pris');
            $table->unsignedBigInteger('changed_pris_id');
            $table->foreign('changed_pris_id')->references('id')->on('pris');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pris_change_pris');
    }
};
