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
            $table->tinyInteger('actual')->default(0);
        });

        Schema::table('legislative_program', function (Blueprint $table) {
            $table->tinyInteger('actual')->default(0);
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
            $table->dropColumn('actual');
        });

        Schema::table('legislative_program', function (Blueprint $table) {
            $table->dropColumn('actual');
        });
    }
};
