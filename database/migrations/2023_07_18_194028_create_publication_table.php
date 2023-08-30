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
            $table->boolean('active')->default(1);
            $table->integer('type');
            $table->string('slug', 2000);
            $table->bigInteger('file_id')->nullable();
            $table->bigInteger('publication_category_id')->nullable();
            $table->timestamp('published_at');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publication_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('publication_id');
            $table->unique(['publication_id', 'locale']);
            $table->foreign('publication_id')
                ->references('id')
                ->on('publication');

            $table->string('title', 2000);
            $table->text('short_content')->nullable();
            $table->text('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keyword')->nullable();
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
