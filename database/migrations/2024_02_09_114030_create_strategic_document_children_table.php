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
        Schema::create('strategic_document_children', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('strategic_document_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('strategic_document_children_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('strategic_document_children_id');
            $table->unique(['strategic_document_children_id', 'locale']);
//            $table->foreign('strategic_document_children_id')
//                ->references('id')
//                ->on('strategic_document_children');

            $table->string('title', 2000);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('strategic_document_children_translations');
        Schema::dropIfExists('strategic_document_children');
    }
};
