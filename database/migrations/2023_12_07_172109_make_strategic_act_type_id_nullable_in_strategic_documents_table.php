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
        Schema::table('strategic_document', function (Blueprint $table) {
            $table->unsignedBigInteger('strategic_act_type_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('strategic_document', function (Blueprint $table) {
            $table->unsignedBigInteger('strategic_act_type_id')->nullable(false)->change();
        });
    }
};
