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
        Schema::create('ogp_plan_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_plan_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ogp_plan_id')->references('id')->on('ogp_plan');
        });

        Schema::create('ogp_plan_schedule_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('ogp_plan_schedule_id');
            $table->unique(['ogp_plan_schedule_id', 'locale']);
            $table->foreign('ogp_plan_schedule_id')
                ->references('id')
                ->on('ogp_plan_schedule');

            $table->string('name', 2000);
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ogp_plan_schedule_translations');
        Schema::dropIfExists('ogp_plan_schedule');
    }
};
