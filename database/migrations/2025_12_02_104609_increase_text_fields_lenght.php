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
        if (Schema::hasColumn('advisory_board_meeting_decisions', 'agenda')) {
            Schema::table('advisory_board_meeting_decisions', function (Blueprint $table) {
                $table->text('agenda')->nullable()->change();
            });
        }
        if (Schema::hasColumn('advisory_board_translations', 'name')) {
            Schema::table('advisory_board_translations', function (Blueprint $table) {
                $table->string('name', 800)->nullable()->change();
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
        //
    }
};
