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
        Schema::create('authority_accepting_strategic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::create('authority_accepting_strategic_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('authority_accepting_strategic_id');
            $table->unique(['locale', 'authority_accepting_strategic_id']);
            $table->foreign('authority_accepting_strategic_id')
                ->references('id')
                ->on('authority_accepting_strategic');

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
        Schema::dropIfExists('authority_accepting_strategic_translations');
        Schema::dropIfExists('authority_accepting_strategic');
    }
};
