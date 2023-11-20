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
        Schema::create('pris', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('doc_num');
            $table->date('doc_date');
            $table->unsignedBigInteger('legal_act_type_id');
            $table->unsignedBigInteger('institution_id');
            $table->string('version', 10)->nullable();
            $table->string('protocol', 10)->nullable();
            $table->unsignedBigInteger('public_consultation_id')->nullable();
            $table->foreign('public_consultation_id')->references('id')->on('public_consultation');
            $table->bigInteger('newspaper_number')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pris_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('pris_id');
            $table->foreign('pris_id')->references('id')->on('pris');
            $table->unique(['pris_id', 'locale']);
            $table->text('about');
            $table->text('legal_reason');
            $table->string('importer');
        });

        Schema::create('tag', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tag_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('tag');
            $table->unique(['tag_id', 'locale']);
            $table->string('label');
        });

        Schema::create('pris_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('tag');
            $table->unsignedBigInteger('pris_id');
            $table->foreign('pris_id')->references('id')->on('pris');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pris_tag');
        Schema::dropIfExists('tag_translations');
        Schema::dropIfExists('tag');
        Schema::dropIfExists('pris_translations');
        Schema::dropIfExists('pris');
    }
};
