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
        Schema::create('authority_advisory_board', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::create('authority_advisory_board_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('authority_advisory_board_id');
            $table->unique(['locale', 'authority_advisory_board_id']);
            $table->foreign('authority_advisory_board_id')
                ->references('id')
                ->on('authority_advisory_board');

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
        Schema::dropIfExists('authority_advisory_board_translations');
        Schema::dropIfExists('authority_advisory_board');
    }
};
