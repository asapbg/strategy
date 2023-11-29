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
            $table->unsignedBigInteger('ekatte_area_id')->nullable();
            $table->foreign('ekatte_area_id')->references('id')->on('ekatte_area')->onDelete('set null');
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
            $table->dropForeign(['ekatte_area_id']);
            $table->dropColumn('ekatte_area_id');
        });
    }
};
