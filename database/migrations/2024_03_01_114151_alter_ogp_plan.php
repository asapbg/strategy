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
            $table->date('from_date_develop')->nullable();
            $table->date('to_date_develop')->nullable();
            $table->tinyInteger('national_plan')->default(0);
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
            $table->dropColumn('from_date_develop');
            $table->dropColumn('to_date_develop');
            $table->tinyInteger('national_plan')->default(0);
        });
    }
};
