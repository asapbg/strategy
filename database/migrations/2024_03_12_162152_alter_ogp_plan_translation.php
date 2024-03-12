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
        Schema::table('ogp_plan_translations', function (Blueprint $table){
            $table->string('report_title', 2000)->nullable();
            $table->text('report_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ogp_plan_translations', function (Blueprint $table){
            $table->dropColumn('report_title', 2000);
            $table->dropColumn('report_content');
        });
    }
};
