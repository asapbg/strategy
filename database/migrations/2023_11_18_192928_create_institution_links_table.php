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
        Schema::create('institution_link', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')->on('institution')->references('id');
            $table->string('link', 1000);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('institution_link_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('institution_link_id');
            $table->unique(['institution_link_id', 'locale']);
            $table->foreign('institution_link_id')
                ->references('id')
                ->on('institution_link');
            $table->string('title', 500);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institution_links_translations');
        Schema::dropIfExists('institution_link');
    }
};
