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
        Schema::table('files', function (Blueprint $table){
           $table->string('locale', 2)->default('bg');
           $table->unsignedBigInteger('lang_pair')->nullable();
           $table->string('version', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table){
            $table->dropColumn('locale');
            $table->dropColumn('lang_pair');
            $table->dropColumn('version');
        });
    }
};
