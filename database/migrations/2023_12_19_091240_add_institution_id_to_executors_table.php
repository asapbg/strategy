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
        if (!Schema::hasColumn('executors', 'institution_id')) {
            Schema::table('executors', function (Blueprint $table) {
                $table->integer('institution_id')->nullable();
                $table->foreign('institution_id')->references('id')->on('institution');
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
        if (Schema::hasColumn('executors', 'institution_id')) {
            Schema::table('executors', function (Blueprint $table) {
                $table->dropColumn('institution_id');
            });
        }
    }
};
