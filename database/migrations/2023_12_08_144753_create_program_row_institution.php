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
        Schema::create('legislative_program_row_institution', function (Blueprint $table) {
            $table->unsignedBigInteger('legislative_program_row_id');
            $table->unsignedBigInteger('institution_id');
        });
        Schema::create('operational_program_row_institution', function (Blueprint $table) {
            $table->unsignedBigInteger('operational_program_row_id');
            $table->unsignedBigInteger('institution_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legislative_program_row_institution');
        Schema::dropIfExists('operational_program_row_institution');
    }
};
