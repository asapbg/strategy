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
        Schema::create('law_institution', function (Blueprint $table) {
            $table->unsignedBigInteger('law_id');
            $table->foreign('law_id')->on('law')->references('id');
            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')->on('institution')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('law_institution');
    }
};
