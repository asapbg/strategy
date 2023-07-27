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
        Schema::create('regulatory_act', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('regulatory_act_type_id');
            $table->integer('number');
            $table->timestamps();
        });

        Schema::create('regulatory_act_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('regulatory_act_id');
            $table->unique(['regulatory_act_id', 'locale']);
            $table->foreign('regulatory_act_id')
                ->references('id')
                ->on('regulatory_act');

            $table->string('name');
            $table->string('institution');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regulatory_act_translations');
        Schema::dropIfExists('regulatory_act');
    }
};
