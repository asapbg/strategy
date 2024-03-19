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
        Schema::create('legislative_initiative_institution', function (Blueprint $table) {
            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')->on('institution')->references('id');
            $table->unsignedBigInteger('legislative_initiative_id');
            $table->foreign('legislative_initiative_id')->on('legislative_initiative')->references('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legislative_initiative_institution');
    }
};
