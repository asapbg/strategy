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
        Schema::create('act_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('consultation_level_id');
            $table->timestamps();
        });

        Schema::create('act_type_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('act_type_id');
            $table->unique(['act_type_id', 'locale']);
            $table->foreign('act_type_id')
                ->references('id')
                ->on('act_type');

            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('act_type_translations');
        Schema::dropIfExists('act_type');
    }
};
