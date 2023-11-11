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
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_object');
            $table->integer('code_object');
            $table->tinyInteger('doc_type')->nullable();
            $table->string('filename', 200)->nullable();
            $table->string('content_type', 500)->nullable();
//            $table->binary('content')->nullable();
            $table->text('file_text')->fullText('file_text_ts')->language('bulgarian')->nullable();
            $table->string('path')->nullable();
            $table->string('description_bg')->nullable();
            $table->string('description_en')->nullable();

            $table->unsignedBigInteger('sys_user')->nullable();
            $table->foreign('sys_user')
                ->references('id')
                ->on('users');

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
        Schema::dropIfExists('files');
    }
};
