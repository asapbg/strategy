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
        Schema::create('ogp_plan_arrangement_action', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('ogp_plan_arrangement_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ogp_plan_arrangement_action_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('ogp_plan_arrangement_action_id');
            $table->unique(['ogp_plan_arrangement_action_id', 'locale']);
            $table->foreign('ogp_plan_arrangement_action_id')
                ->references('id')
                ->on('ogp_plan_arrangement_action');

            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ogp_plan_arrangement_action_translations');
        Schema::dropIfExists('ogp_plan_arrangement_action');
    }
};
