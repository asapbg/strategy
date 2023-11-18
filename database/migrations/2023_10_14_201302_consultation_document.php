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
        Schema::create('consultation_document', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('public_consultation_id');
            $table->foreign('public_consultation_id')->references('id')->on('public_consultation');
            $table->json('active_columns');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('consultation_document_row', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('consultation_document_id');
            $table->foreign('consultation_document_id')->references('id')->on('consultation_document');
            $table->unsignedInteger('dynamic_structures_column_id');
            $table->foreign('dynamic_structures_column_id')->references('id')->on('dynamic_structures_column');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultation_document_row');
        Schema::dropIfExists('consultation_document');
    }
};
