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
        Schema::create('consultation_document_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('consultation_level_id');
            $table->unsignedInteger('act_type_id');
            $table->timestamps();
        });

        Schema::create('consultation_document_type_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('consultation_document_type_id');
            $table->unique(['locale', 'consultation_document_type_id']);
            $table->foreign('consultation_document_type_id')
                ->references('id')
                ->on('consultation_document_type');

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
        Schema::dropIfExists('consultation_document_type_translations');
        Schema::dropIfExists('consultation_document_type');
    }
};
