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
        Schema::table('authority_accepting_strategic', function (Blueprint $table){
            $table->tinyInteger('nomenclature_level_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authority_accepting_strategic', function (Blueprint $table){
            $table->dropColumn('nomenclature_level_id');
        });
    }
};
