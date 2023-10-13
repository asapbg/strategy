<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
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
        Schema::table('dynamic_structure_column', function (Blueprint $table){
            $table->tinyInteger('in_group')->default(0);
        });

        Artisan::call('db:seed DynamicStructureSeeder');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dynamic_structure_column', function (Blueprint $table){
            $table->dropColumn('in_group');
        });
    }
};
