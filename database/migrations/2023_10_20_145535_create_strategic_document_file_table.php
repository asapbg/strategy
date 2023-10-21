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
        Schema::create('strategic_document_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('strategic_document_id');
            $table->unsignedBigInteger('strategic_document_type_id'); //Вид на стратегически документ
            $table->date('valid_at');
            $table->tinyInteger('visible_in_report')->default(0);
            $table->unsignedBigInteger('sys_user')->nullable();
            $table->string('path', 500);
            $table->text('file_text')->fullText('file_text_sd_ts')->language('bulgarian')->nullable();
            $table->string('filename');
            $table->string('content_type', 500);
            $table->tinyInteger('ord')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('strategic_document_file_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('strategic_document_file_id');
            $table->unique(['strategic_document_file_id', 'locale']);

            $table->string('display_name', 500);
            $table->text('file_info')->nullable();
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
        Schema::dropIfExists('strategic_document_file_translations');
        Schema::dropIfExists('strategic_document_file');
    }
};
