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

        Schema::dropIfExists('ogp_area_offer_vote');
        Schema::dropIfExists('ogp_area_arrangement_comment');
        Schema::dropIfExists('ogp_area_arrangement_field');
        Schema::dropIfExists('ogp_area_arrangement');
        Schema::dropIfExists('ogp_area_commitment');
        Schema::dropIfExists('ogp_area_offer_comment');
        Schema::dropIfExists('ogp_area_offer');

        //предложение
        Schema::create('ogp_plan_area_offer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_plan_area_id');
            $table->foreign('ogp_plan_area_id')
                ->references('id')
                ->on('ogp_plan_area')
                ->onDelete('cascade');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')
                ->references('id')
                ->on('users');
            $table->text('content');
            $table->unsignedBigInteger('likes_cnt')->default(0);
            $table->unsignedBigInteger('dislikes_cnt')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        //comment
        Schema::create('ogp_plan_area_offer_comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_plan_area_offer_id');
            $table->foreign('ogp_plan_area_offer_id')
                ->references('id')
                ->on('ogp_plan_area_offer');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')
                ->references('id')
                ->on('users');
            $table->text('content');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ogp_plan_area_offer_vote', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_plan_area_offer_id');
            $table->foreign('ogp_plan_area_offer_id')
                ->references('id')
                ->on('ogp_plan_area_offer');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')
                ->references('id')
                ->on('users');
            $table->boolean('is_like')->default(false);
            $table->timestamps();
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
            $table->dropColumn('content');
        });
    }
};
