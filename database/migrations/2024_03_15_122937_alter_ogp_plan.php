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
        Schema::table('ogp_plan', function (Blueprint $table){
            $table->unsignedBigInteger('develop_plan_id')->nullable();
            $table->foreign('develop_plan_id')->on('ogp_plan')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ogp_plan', function (Blueprint $table){
            $table->dropColumn('develop_plan_id');
        });
    }
};
