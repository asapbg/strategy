<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('executors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('contractor_id')->nullable(); // maybe for a future nomenclature
            $table->string('contractor_name');
            $table->string('executor_name');
            $table->bigInteger('eik')->nullable();
            $table->date('contract_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->mediumText('contract_subject')->nullable();
            $table->longText('services_description')->nullable();
            $table->decimal('price', 10);
            $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('executors_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('locale', 2)->index();
            $table->unique(['executor_id', 'locale']);
            $table->foreignId('executor_id')->constrained();

            $table->string('contractor_name')->nullable();
            $table->string('executor_name')->nullable();
            $table->mediumText('contract_subject')->nullable();
            $table->longText('services_description')->nullable();
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
        Schema::dropIfExists('executors');
        Schema::dropIfExists('executors_translations');
    }
};
