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
            $table->json('active_columns');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('consultation_document_row', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('consultation_document_id');
            $table->unsignedInteger('dynamic_structures_column_id');
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
