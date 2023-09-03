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
        //        Control for dynamic structures
        Schema::create('dynamic_structure', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type'); //Законодателна програма, Оперативна програма ...
            $table->tinyInteger('active')->default(1); //if we need it
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dynamic_structure_column', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('dynamic_structure_id');
            $table->string('type'); //{text, number...}
            $table->tinyInteger('ord'); //order
            $table->timestamps();
            $table->softDeletes(); //we will use it to hide column from new structures but still will be able to show it in old structures
        });
        Schema::create('dynamic_structure_column_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('dynamic_structure_column_id');
            $table->unique(['dynamic_structure_column_id', 'locale']);
            $table->string('label'); //column label/name
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic_structure_column_translations');
        Schema::dropIfExists('dynamic_structure_column');
        Schema::dropIfExists('dynamic_structure');
    }
};
