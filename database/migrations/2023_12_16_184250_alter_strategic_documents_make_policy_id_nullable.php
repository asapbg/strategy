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
            $table->unsignedBigInteger('policy_area_id')->nullable()->change();
            $table->unsignedBigInteger('public_consultation_id')->nullable()->change();
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
            $table->unsignedBigInteger('policy_area_id')->nullable(false)->change();
            $table->unsignedBigInteger('public_consultation_id')->nullable(false)->change();
        });
    }
};
