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
            $table->string('locale')->nullable()->after('ord');

            $table->unsignedBigInteger('parent_lang_id')->nullable()->after('locale');
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
            $table->dropColumn('locale');

            $table->dropColumn('parent_lang_id');
        });
    }
};
