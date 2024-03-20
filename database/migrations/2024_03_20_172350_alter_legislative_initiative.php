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
        Schema::table('legislative_initiative', function (Blueprint $table) {
            $table->timestamp('active_support')->nullable();
            $table->timestamp('end_support_at')->nullable();
            $table->timestamp('send_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legislative_initiative', function (Blueprint $table) {
            $table->dropColumn('active_support');
            $table->dropColumn('end_support_at');
            $table->dropColumn('send_at');
        });
    }
};
