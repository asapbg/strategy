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
        Schema::table('ogp_area', function (Blueprint $table) {
            $table->dropColumn('ogp_status_id');
            $table->dropColumn('from_date');
            $table->dropColumn('to_date');
        });

        Schema::create('ogp_plan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_status_id');
            $table->foreign('ogp_status_id')
                ->references('id')
                ->on('ogp_status')
                ->onDelete('cascade');
            $table->date('from_date');
            $table->date('to_date');
            $table->boolean('active')->default('1');
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')
                ->references('id')
                ->on('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ogp_plan_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_plan_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('content');
            $table->unique(['locale', 'ogp_plan_id']);
            $table->foreign('ogp_plan_id')
                ->references('id')
                ->on('ogp_plan')
                ->onDelete('cascade');
        });

        Schema::create('ogp_plan_area', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_plan_id');
            $table->foreign('ogp_plan_id')
                ->references('id')
                ->on('ogp_plan')
                ->onDelete('cascade');
            $table->unsignedBigInteger('ogp_area_id');
            $table->foreign('ogp_area_id')
                ->references('id')
                ->on('ogp_area')
                ->onDelete('cascade');
            $table->softDeletes();
        });

        Schema::create('ogp_plan_arrangement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_plan_area_id');
            $table->foreign('ogp_plan_area_id')
                ->references('id')
                ->on('ogp_plan_area')
                ->onDelete('cascade');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ogp_plan_arrangement_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_plan_arrangement_id');
            $table->string('locale')->index();
            $table->string('content');
            $table->string('npo_partner')->nullable();
            $table->string('responsible_administration')->nullable();

            $table->unique(['locale', 'ogp_plan_arrangement_id']);
            $table->foreign('ogp_plan_arrangement_id')
                ->references('id')
                ->on('ogp_plan_arrangement')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ogp_area', function (Blueprint $table) {
            $table->unsignedBigInteger('ogp_status_id')->nullable();
            $table->foreign('ogp_status_id')
                ->references('id')
                ->on('ogp_status')
                ->onDelete('cascade');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
        });

        Schema::dropIfExists('ogp_plan_arrangement_translations');
        Schema::dropIfExists('ogp_plan_arrangement');
        Schema::dropIfExists('ogp_plan_translations');
        Schema::dropIfExists('ogp_plan_area');
        Schema::dropIfExists('ogp_plan');
    }
};
