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
        if (!Schema::hasColumn('strategic_document', 'document_date')) {
            Schema::table('strategic_document', function (Blueprint $table) {
                $table->timestamp('document_date')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('strategic_document', 'document_date')) {
            Schema::table('strategic_document', function (Blueprint $table) {
                $table->dropColumn('document_date');
            });
        }
    }
};
