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
        if (!Schema::hasColumns('strategic_document_children', ['strategic_act_type_id', 'strategic_act_link', 'strategic_act_number','document_date'])) {
            Schema::table('strategic_document_children', function (Blueprint $table) {
                $table->unsignedBigInteger('strategic_act_type_id')->nullable(); //Вид с който е приет документа
                $table->string('strategic_act_number',100)->nullable(); // Ako accept_act_institution_type е Министрески съвет е празно
                $table->string('strategic_act_link', 1000)->nullable(); // Ako accept_act_institution_type е Министрески и strategic_act_type не е Заповед това остава празно
                $table->date('document_date')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumns('strategic_document_children', ['strategic_act_type_id', 'strategic_act_link', 'strategic_act_number', 'document_date'])) {
            Schema::table('strategic_document_children', function (Blueprint $table) {
                $table->dropColumn(['strategic_act_type_id', 'strategic_act_link', 'document_date']);
            });
        }
    }
};
