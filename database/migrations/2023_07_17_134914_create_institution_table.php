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
        Schema::create('institution', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->string('eik',13)->nullable();
            $table->unsignedBigInteger('region')->nullable();
            $table->unsignedBigInteger('municipality')->nullable();
            $table->unsignedBigInteger('town')->nullable();
            $table->string('phone',1000)->nullable();
            $table->string('fax',1000)->nullable();
            $table->string('email')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->tinyInteger('adm_register')->default(1);
            $table->string('type')->nullable();

            $table->unsignedBigInteger('institution_level_id');
            $table->foreign('institution_level_id')
                ->references('id')
                ->on('institution_level');

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->integer('zip_code')->nullable();
            $table->string('nomer_register', 25)->index()->nullable();
            $table->tinyInteger('active')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('institution_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('institution_id');
            $table->unique(['institution_id', 'locale']);
            $table->foreign('institution_id')
                ->references('id')
                ->on('institution');

            $table->string('name');
            $table->string('address')->nullable();
            $table->string('add_info', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institution_translations');
        Schema::dropIfExists('institution');
    }
};
