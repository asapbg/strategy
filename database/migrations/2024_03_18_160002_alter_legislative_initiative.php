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
            $table->unsignedBigInteger('law_id')->nullable();
        });
        DB::statement('ALTER TABLE legislative_initiative ALTER operational_program_id drop not null');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legislative_initiative', function (Blueprint $table) {
            $table->dropColumn('law_id')->nullable();
        });
    }
};
