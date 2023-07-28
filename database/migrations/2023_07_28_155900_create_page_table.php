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
        Schema::create('page', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type');
            $table->boolean('active')->nullable();
            $table->boolean('highlighted')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('page_id');
            $table->unique(['page_id', 'locale']);
            $table->foreign('page_id')
                ->references('id')
                ->on('page');

            $table->text('title');
            $table->text('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('page');
    }

};