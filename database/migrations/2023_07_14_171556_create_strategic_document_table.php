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
        Schema::create('strategic_document', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('strategic_document_level_id'); //категория ниво
            $table->unsignedBigInteger('policy_area_id'); //Област на политика
            $table->unsignedBigInteger('strategic_document_type_id'); //Вид на стратегически документ
            $table->unsignedBigInteger('strategic_act_type_id'); //Вид с който е приет документа
            $table->string('strategic_act_number',100)->nullable(); // Ako accept_act_institution_type е Министрески съвет е празно
            $table->string('strategic_act_link', 1000)->nullable(); // Ako accept_act_institution_type е Министрески и strategic_act_type не е Заповед това остава празно
            $table->unsignedBigInteger('accept_act_institution_type_id'); // Ako accept_act_institution_type е Министрески и strategic_act_type не е Заповед това остава празно
            $table->unsignedBigInteger('pris_act_id')->nullable(); // Ako accept_act_institution_type е Министрески и strategic_act_type не е Заповед, трябва да се посочи връзка с документ в pris
            $table->date('document_date')->nullable();
            $table->unsignedBigInteger('public_consultation_id');

            $table->boolean('active')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('strategic_document_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('strategic_document_id');
            $table->unique(['strategic_document_id', 'locale']);
            $table->foreign('strategic_document_id')
                ->references('id')
                ->on('strategic_document');

            $table->text('title');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('strategic_document_translations');
        Schema::dropIfExists('strategic_document');
    }
};
