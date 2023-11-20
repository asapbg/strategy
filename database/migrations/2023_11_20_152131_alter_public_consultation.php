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
            $table->foreign('legislative_program_id')->references('id')->on('legislative_program');
            $table->foreign('operational_program_id')->references('id')->on('operational_program');
//            $table->foreign('regulatory_act_id')->references('id')->on('regulatory_act');
            $table->foreign('importer_institution_id')->references('id')->on('institution');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
