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
        Schema::create('public_consultation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('consultation_type_id');
            $table->unsignedBigInteger('consultation_level_id');
            $table->unsignedBigInteger('act_type_id');
            $table->unsignedBigInteger('legislative_program_id')->nullable();
            $table->unsignedBigInteger('operational_program_id')->nullable();
            $table->date('open_from');
            $table->date('open_to');
            $table->unsignedBigInteger('regulatory_act_id')->nullable();
            $table->unsignedBigInteger('pris_act_id')->nullable();
            $table->unsignedBigInteger('importer_institution_id');
            $table->unsignedBigInteger('responsible_institution_id');
            $table->string('responsible_institution_address');
            $table->text('act_links')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('public_consultation_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('public_consultation_id');
            $table->unique(['locale', 'public_consultation_id']);
            $table->foreign('public_consultation_id')
                ->references('id')
                ->on('public_consultation');

            $table->string('title');
            $table->text('description');
            $table->text('short_term_reason')->nullable();
            $table->text('proposal_ways');
            $table->string('responsible_unit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_consultation_translations');
        Schema::dropIfExists('public_consultation');
    }
};
