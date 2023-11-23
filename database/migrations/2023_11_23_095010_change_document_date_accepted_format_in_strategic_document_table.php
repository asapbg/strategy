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

            $table->string('document_date_accepted')->nullable()->change();
            $table->string('document_date_expiring')->nullable()->change();
        });
        Schema::table('strategic_document', function (Blueprint $table) {

            $table->timestamp('document_date_accepted')->nullable()->change();
            $table->timestamp('document_date_expiring')->nullable()->change();
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
            $table->date('document_date_accepted')->nullable()->change();
            $table->date('document_date_expiring')->nullable()->change();
        });
    }
};
