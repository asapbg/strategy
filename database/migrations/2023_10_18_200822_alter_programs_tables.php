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
        Schema::table('operational_program', function (Blueprint $table) {
            $table->tinyInteger('locked')->default(0);
            $table->unsignedBigInteger('public_consultation_id')->nullable();
        });

        Schema::table('legislative_program', function (Blueprint $table) {
            $table->tinyInteger('locked')->default(0);
            $table->unsignedBigInteger('public_consultation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operational_program', function (Blueprint $table) {
            $table->dropColumn('locked');
            $table->dropColumn('public_consultation_id');
        });

        Schema::table('legislative_program', function (Blueprint $table) {
            $table->dropColumn('locked');
            $table->dropColumn('public_consultation_id');
        });
    }
};
