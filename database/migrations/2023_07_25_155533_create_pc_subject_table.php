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
        Schema::create('pc_subject', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type');
            $table->string('eik');
            $table->date('contract_date');
            $table->float('price');
            $table->timestamps();
        });

        Schema::create('pc_subject_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('p_c_subject_id');
            $table->unique(['p_c_subject_id', 'locale']);
            $table->foreign('p_c_subject_id')
                ->references('id')
                ->on('pc_subject');

            $table->string('contractor');
            $table->string('executor');
            $table->string('objective');
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pc_subject_translations');
        Schema::dropIfExists('pc_subject');
    }
};
