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
        Schema::table('strategic_document_children', function (Blueprint $table){
            $table->unsignedBigInteger('strategic_document_level_id')->nullable(); //категория ниво
            $table->unsignedBigInteger('accept_act_institution_type_id')->nullable();
            $table->unsignedBigInteger('strategic_document_type_id')->nullable(); //Вид на стратегически документ
            $table->unsignedBigInteger('policy_area_id')->nullable(); //Област на политика
            $table->unsignedBigInteger('pris_act_id')->nullable();
            $table->unsignedBigInteger('public_consultation_id')->nullable();
            $table->timestamp('document_date_accepted')->nullable();
            $table->timestamp('document_date_expiring')->nullable();
            $table->string('link_to_monitorstat', 1000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('strategic_document_children', function (Blueprint $table){
            $table->dropColumn('strategic_document_level_id');
            $table->dropColumn('accept_act_institution_type_id');
            $table->dropColumn('strategic_document_type_id');
            $table->dropColumn('policy_area_id');
            $table->dropColumn('pris_act_id');
            $table->dropColumn('public_consultation_id');
            $table->dropColumn('document_date_accepted');
            $table->dropColumn('document_date_expiring');
            $table->dropColumn('link_to_monitorstat');
        });
    }
};
