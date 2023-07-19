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
        Schema::create('publication', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type');
            $table->bigInteger('publication_category_id');
            $table->date('event_date');
            $table->boolean('active')->nullable();
            $table->boolean('highlighted')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('publication_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('publication_id');
            $table->unique(['publication_id', 'locale']);
            $table->foreign('publication_id')
                ->references('id')
                ->on('publication');

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
        Schema::dropIfExists('publication_translations');
        Schema::dropIfExists('publication');
    }

};