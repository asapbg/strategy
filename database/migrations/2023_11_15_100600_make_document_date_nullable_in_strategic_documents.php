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
        Schema::table('strategic_document', function (Blueprint $table) {
            // Modify the existing column to make it nullable
            $table->date('document_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('strategic_document', function (Blueprint $table) {
            // If you need to rollback, you can add the reverse operation here
            // For example, if you made the column nullable, you can make it non-nullable here
            $table->date('document_date')->change();
        });
    }
};
