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
        Schema::dropIfExists('executor_translations');
        Schema::dropIfExists('executors_translations');
        Schema::dropIfExists('executors');

        Schema::create('executors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('eik')->nullable();
            $table->date('contract_date')->nullable();
            $table->decimal('price', 10)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('executor_translations', function (Blueprint $table) {
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
        Schema::dropIfExists('executor_translations');
        Schema::dropIfExists('executors');
    }
};
