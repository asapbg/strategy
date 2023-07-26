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
        Schema::create('poll', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('consultation_id')->nullable();
            $table->date('begin_date');
            $table->date('end_date');
            $table->boolean('active')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('poll_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('poll_id');
            $table->unique(['poll_id', 'locale']);
            $table->foreign('poll_id')
                ->references('id')
                ->on('poll');

            $table->string('title');
            $table->string('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poll_translations');
        Schema::dropIfExists('poll');
    }
};
