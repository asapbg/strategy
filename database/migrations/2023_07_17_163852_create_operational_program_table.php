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
        Schema::create('operational_program', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('effective_from');
            $table->date('effective_to');
            $table->boolean('active')->nullable();
            $table->timestamps();
        });

        Schema::create('operational_program_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('operational_program_id');
            $table->unique(['operational_program_id', 'locale']);
            $table->foreign('operational_program_id')
                ->references('id')
                ->on('operational_program');

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
        Schema::dropIfExists('operational_program_translations');
        Schema::dropIfExists('operational_program');
    }
};
