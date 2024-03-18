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
        Schema::table('ogp_plan', function (Blueprint $table){
            $table->date('self_evaluation_published_at')->nullable();
            $table->date('report_evaluation_published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ogp_plan', function (Blueprint $table){
            $table->dropColumn('self_evaluation_published_at');
            $table->dropColumn('report_evaluation_published_at');
        });
    }
};
