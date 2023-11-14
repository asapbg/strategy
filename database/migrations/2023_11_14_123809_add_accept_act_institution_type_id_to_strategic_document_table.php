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
            $table->unsignedBigInteger('accept_act_institution_type_id');
            $table->foreign('accept_act_institution_type_id')
                ->references('id')
                ->on('authority_accepting_strategic')
                ->onDelete('cascade');
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
            $table->dropForeign(['accept_act_institution_type_id']);

            $table->dropColumn('accept_act_institution_type_id');
        });
    }
};
