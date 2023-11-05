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
        Schema::create('institution_level', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('system_name');
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
        });

        Schema::create('institution_level_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('institution_level_id');
            $table->unique(['institution_level_id', 'locale']);
            $table->foreign('institution_level_id')
                ->references('id')
                ->on('institution_level');

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
        Schema::dropIfExists('country_translations');
        Schema::dropIfExists('country');
    }
};
