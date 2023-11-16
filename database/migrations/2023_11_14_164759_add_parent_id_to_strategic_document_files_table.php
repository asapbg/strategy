<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('strategic_document', 'parent_id')) {
            Schema::table('strategic_document_file', function (Blueprint $table) {
                // Add nullable parent_id column
                $table->unsignedBigInteger('parent_id')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('strategic_document', 'parent_id')) {
            Schema::table('strategic_document_file', function (Blueprint $table) {
                $table->dropColumn('parent_id');
            });
        }
    }
};
