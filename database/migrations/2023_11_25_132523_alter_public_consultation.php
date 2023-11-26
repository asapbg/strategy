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
            $table->unsignedBigInteger('field_of_actions_id')->nullable();
            $table->foreign('field_of_actions_id')->references('id')->on('field_of_actions');
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
            $table->dropColumn('field_of_actions_id');
        });
    }
};
