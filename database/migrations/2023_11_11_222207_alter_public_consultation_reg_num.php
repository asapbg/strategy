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
            $table->string('reg_num')->unique()->nullable();
            $table->string('monitorstat')->nullable();
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
            $table->dropColumn('reg_num');
            $table->dropColumn('monitorstat');
        });
    }
};
