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
        if (!Schema::hasColumn('strategic_document', 'pris_act_id')) {
            Schema::table('strategic_document', function (Blueprint $table) {
                $table->integer('pris_act_id')->nullable();
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
        if (Schema::hasColumn('strategic_document', 'pris_act_id')) {
            Schema::table('strategic_document', function (Blueprint $table) {
                // Reverse the changes made in the 'up' method
                $table->dropColumn('pris_act_id');
            });
        }
    }
};
