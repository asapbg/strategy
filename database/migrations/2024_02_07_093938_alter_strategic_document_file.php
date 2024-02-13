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
        Schema::table('strategic_document_file', function (Blueprint $table){
            $table->string('description', 2000)->nullable();
            $table->text('file_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('strategic_document_file', function (Blueprint $table){
            $table->dropColumn('description');
            $table->dropColumn('file_info');
        });
    }
};
