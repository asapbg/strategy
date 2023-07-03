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
        Schema::create('advisory_chairman_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::create('advisory_chairman_type_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('advisory_chairman_type_id');
            $table->unique(['advisory_chairman_type_id', 'locale']);
            $table->foreign('advisory_chairman_type_id')
                ->references('id')
                ->on('advisory_chairman_type');

            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advisory_chairman_type_translations');
        Schema::dropIfExists('advisory_chairman_type');
    }
};
