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
        if (!Schema::hasColumn('executor_translations', 'hyperlink')) {
            Schema::table('executor_translations', function (Blueprint $table) {
                $table->string('hyperlink')->nullable();
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
        if (Schema::hasColumn('executor_translations', 'hyperlink')) {
            Schema::table('executor_translations', function (Blueprint $table) {
                $table->dropColumn('hyperlink');
            });
        }
    }
};
