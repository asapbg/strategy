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
        if (!Schema::hasColumn('strategic_document', 'public_consultation_id')) {
            Schema::table('strategic_document', function (Blueprint $table) {
                $table->unsignedBigInteger('public_consultation_id');

                $table->foreign('public_consultation_id')
                    ->references('id')
                    ->on('public_consultation')
                    ->onDelete('cascade');
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
        if (Schema::hasColumn('strategic_document', 'public_consultation_id')) {
            Schema::table('strategic_document', function (Blueprint $table) {
                $table->dropForeign(['public_consultation_id']);

                $table->dropColumn('public_consultation_id');
            });
        }
    }
};
