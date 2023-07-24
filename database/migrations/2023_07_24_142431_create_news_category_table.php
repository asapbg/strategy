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
        Schema::create('news_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::create('news_category_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('news_category_id');
            $table->unique(['news_category_id', 'locale']);
            $table->foreign('news_category_id')
                ->references('id')
                ->on('news_category');

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
        Schema::dropIfExists('news_category_translations');
        Schema::dropIfExists('news_category');
    }
};
