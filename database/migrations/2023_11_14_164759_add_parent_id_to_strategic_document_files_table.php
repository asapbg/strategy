<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('strategic_document_file', function (Blueprint $table) {
            // Add nullable parent_id column
            $table->unsignedBigInteger('parent_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('strategic_document_file', function (Blueprint $table) {
            // If needed, you can revert the change here
            $table->dropColumn('parent_id');
        });
    }
};
