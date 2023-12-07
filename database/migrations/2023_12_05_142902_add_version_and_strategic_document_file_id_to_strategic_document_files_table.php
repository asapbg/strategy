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
        Schema::table('strategic_document_file', function (Blueprint $table) {
            $table->unsignedBigInteger('strategic_document_file_id')->nullable();
            $table->foreign('strategic_document_file_id')->references('id')->on('strategic_document_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('strategic_document_file', function (Blueprint $table) {
            $table->dropForeign(['strategic_document_file_id']);
            $table->dropColumn('strategic_document_file_id');
        });
    }
};
