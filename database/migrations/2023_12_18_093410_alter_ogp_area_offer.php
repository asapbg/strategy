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
        Schema::table('ogp_area_offer', function (Blueprint $table) {
            $table->unsignedBigInteger('likes_cnt')->default(0);
            $table->unsignedBigInteger('dislikes_cnt')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ogp_area_offer', function (Blueprint $table) {
            $table->dropColumn('likes_cnt');
            $table->dropColumn('dislikes_cnt');
        });
    }
};
