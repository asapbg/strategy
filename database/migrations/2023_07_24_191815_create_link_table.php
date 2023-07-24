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
        Schema::create('link', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('link_category_id');
            $table->string('url');
            $table->boolean('active')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('link_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('link_id');
            $table->unique(['link_id', 'locale']);
            $table->foreign('link_id')
                ->references('id')
                ->on('link');

            $table->string('title');
            $table->string('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_translations');
        Schema::dropIfExists('link');
    }
};
