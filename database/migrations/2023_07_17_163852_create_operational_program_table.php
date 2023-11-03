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
        Schema::create('operational_program', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('public')->default(0);
            $table->json('active_columns'); //array with columns ids which belongs to this operational program at moment of creation
            $table->date('from_date');
            $table->date('to_date');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('operational_program_row', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('row_num');
            $table->unsignedInteger('operational_program_id');
            $table->unsignedInteger('dynamic_structures_column_id');
            $table->string('month', 7);
            $table->text('value')->nullable(); //column value
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
        Schema::dropIfExists('operational_program_row');
        Schema::dropIfExists('operational_program');
    }
};
