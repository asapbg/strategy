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
        if (!Schema::hasColumn('pris_change_pris', 'full_text')) {
            Schema::table('pris_change_pris', function (Blueprint $table) {
                $table->string('full_text')->nullable();
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
        if (Schema::hasColumn('pris_change_pris', 'full_text')) {
            Schema::table('pris_change_pris', function (Blueprint $table) {
                $table->dropColumn('full_text');
            });
        }
    }
};
