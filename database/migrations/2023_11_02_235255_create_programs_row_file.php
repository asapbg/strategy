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
        Schema::create('legislative_program_row_file', function (Blueprint $table) {
            $table->unsignedBigInteger('legislative_program_id');
            $table->unsignedBigInteger('file_id');
            $table->bigInteger('row');
            $table->string('month');
        });

        Schema::create('operational_program_row_file', function (Blueprint $table) {
            $table->unsignedBigInteger('operational_program_id');
            $table->unsignedBigInteger('file_id');
            $table->bigInteger('row');
            $table->string('month');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legislative_program_row_file');
        Schema::dropIfExists('operational_program_row_file');
    }
};
