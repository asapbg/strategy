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
        Schema::table('page', function (Blueprint $table) {
            $table->tinyInteger('is_system')->default(0);
            $table->string('system_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page', function (Blueprint $table) {
            $table->dropColumn('is_system');
            $table->dropColumn('system_name');
        });
    }
};
