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
            $table->unsignedInteger('consultation_level_id');
            $table->unsignedInteger('act_type_id');
            $table->unsignedInteger('program_project_id');
            $table->unsignedInteger('link_category_id');
            $table->date('open_from');
            $table->date('open_to');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->boolean('active')->nullable();
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
            $table->text('shortTermReason')->nullable();
            $table->string('responsibleUnit');
            $table->string('responsiblePerson');
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
