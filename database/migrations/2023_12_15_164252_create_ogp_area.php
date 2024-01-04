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
        Schema::create('ogp_status', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default('1');
            $table->string('css_class')->nullable();
            $table->boolean('can_edit')->default('0');
            $table->unsignedInteger('type')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('ogp_status_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_status_id');
            $table->string('locale')->index();
            $table->string('name');

            $table->unique(['locale', 'ogp_status_id']);
            $table->foreign('ogp_status_id')
                ->references('id')
                ->on('ogp_status')
                ->onDelete('cascade');
        });

        //област
        Schema::create('ogp_area', function (Blueprint $table) {
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

        Schema::create('ogp_area_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_area_id');
            $table->string('locale')->index();
            $table->string('name');

            $table->unique(['locale', 'ogp_area_id']);
            $table->foreign('ogp_area_id')
                ->references('id')
                ->on('ogp_area')
                ->onDelete('cascade');
        });

        //предложение
        Schema::create('ogp_area_offer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_area_id');
            $table->foreign('ogp_area_id')
                ->references('id')
                ->on('ogp_area')
                ->onDelete('cascade');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')
                ->references('id')
                ->on('users');
            $table->timestamps();
            $table->softDeletes();
        });

        //ангажимент
        Schema::create('ogp_area_commitment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_area_offer_id');
            $table->foreign('ogp_area_offer_id')
                ->references('id')
                ->on('ogp_area_offer');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        //мерки
        Schema::create('ogp_area_arrangement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_area_commitment_id');
            $table->foreign('ogp_area_commitment_id')
                ->references('id')
                ->on('ogp_area_commitment');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        //мерки полета
        Schema::create('ogp_area_arrangement_field', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_area_arrangement_id');
            $table->foreign('ogp_area_arrangement_id')
                ->references('id')
                ->on('ogp_area_arrangement');
            $table->boolean('is_system')->default(1);
            $table->string('name');
            $table->text('content');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ogp_area_arrangement_comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogp_area_arrangement_id');
            $table->foreign('ogp_area_arrangement_id')
                ->references('id')
                ->on('ogp_area');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')
                ->references('id')
                ->on('users');
            $table->text('content');
            $table->softDeletes();
        });

        \Illuminate\Support\Facades\Artisan::call('db:seed OgpStatusSeeder');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ogp_area_arrangement_comment');
        Schema::dropIfExists('ogp_area_arrangement_field');
        Schema::dropIfExists('ogp_area_arrangement');
        Schema::dropIfExists('ogp_area_commitment');
        Schema::dropIfExists('ogp_area_offer');
        Schema::dropIfExists('ogp_area_translations');
        Schema::dropIfExists('ogp_area');
        Schema::dropIfExists('ogp_status_translations');
        Schema::dropIfExists('ogp_status');
    }
};
