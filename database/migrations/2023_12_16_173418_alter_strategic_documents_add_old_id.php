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
        if (!Schema::hasColumn('strategic_document', 'old_id')) {
            Schema::table('strategic_document', function (Blueprint $table) {
                $table->unsignedBigInteger('old_id')->nullable();
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
        if (Schema::hasColumn('strategic_document', 'old_id')) {
            Schema::table('strategic_document', function (Blueprint $table) {
                $table->dropColumn('old_id');
            });
        }
    }
};
