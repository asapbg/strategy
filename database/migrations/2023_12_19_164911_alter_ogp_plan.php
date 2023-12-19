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
        Schema::table('ogp_plan', function (Blueprint $table) {
            $table->text('version_after_public_consultation_pdf');
            $table->text('final_version_pdf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ogp_plan', function (Blueprint $table) {
            $table->dropColumn('version_after_public_consultation_pdf');
            $table->dropColumn('final_version_pdf');
        });
    }
};
