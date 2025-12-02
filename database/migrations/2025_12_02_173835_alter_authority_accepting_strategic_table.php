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
        if (!Schema::hasColumn('authority_accepting_strategic', 'active')) {
            Schema::table('authority_accepting_strategic', function (Blueprint $table) {
                $table->boolean('active')->default('1');
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
        if (Schema::hasColumn('authority_accepting_strategic', 'active')) {
            Schema::table('authority_accepting_strategic', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }
    }
};
