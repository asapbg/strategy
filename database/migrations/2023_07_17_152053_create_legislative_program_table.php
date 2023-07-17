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
        Schema::create('legislative_program', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('effective_from');
            $table->date('effective_to');
            $table->boolean('active')->nullable();
            $table->timestamps();
        });

        Schema::create('legislative_program_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('legislative_program_id');
            $table->unique(['legislative_program_id', 'locale']);
            $table->foreign('legislative_program_id')
                ->references('id')
                ->on('legislative_program');

            $table->text('title');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legislative_program_translations');
        Schema::dropIfExists('legislative_program');
    }
};
