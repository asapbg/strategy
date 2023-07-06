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
        Schema::create('document_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('consultation_level_id');
            $table->unsignedInteger('act_type_id');
            $table->timestamps();
        });

        Schema::create('document_type_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('document_type_id');
            $table->unique(['locale', 'document_type_id']);
            $table->foreign('document_type_id')
                ->references('id')
                ->on('document_type');

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
        Schema::dropIfExists('document_type_translations');
        Schema::dropIfExists('document_type');
    }
};
