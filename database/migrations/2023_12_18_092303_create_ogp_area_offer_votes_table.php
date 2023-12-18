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
        Schema::create('ogp_area_offer_vote', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_area_offer_id');
            $table->foreign('ogp_area_offer_id')
                ->references('id')
                ->on('ogp_area_offer');
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
        Schema::dropIfExists('ogp_area_offer_vote');
    }
};
