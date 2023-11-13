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
        Schema::table('public_consultation', function (Blueprint $table){
            $table->unsignedBigInteger('operational_program_row_id')->nullable();
            $table->unsignedBigInteger('legislative_program_row_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_consultation', function (Blueprint $table){
            $table->dropColumn('operational_program_row_id');
            $table->dropColumn('legislative_program_row_id');
        });
    }
};
