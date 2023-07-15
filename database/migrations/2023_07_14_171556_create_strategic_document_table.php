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
            $table->bigInteger('strategic_document_level_id');
            $table->bigInteger('policy_area_id');
            $table->bigInteger('strategic_document_type_id');
            $table->bigInteger('strategic_act_type_id');
            $table->string('document_number');
            $table->bigInteger('authority_accepting_strategic_id');
            $table->date('document_date');
            $table->string('consultation_number');
            $table->boolean('active');
            $table->timestamps();
        });

        Schema::create('strategic_document_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('strategic_document_id');
            $table->unique(['strategic_document_id', 'locale']);
            $table->foreign('strategic_document_id')
                ->references('id')
                ->on('strategic_document');

            $table->string('title');
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
        Schema::dropIfExists('strategic_document_translations');
        Schema::dropIfExists('strategic_document');
    }
};
