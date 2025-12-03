<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public array $tables = [
        'authority_advisory_board',
        'advisory_act_type',
        'advisory_chairman_type',
        'strategic_act_type',
        'act_type',
        'link_category',
        'program_project',
        'regulatory_act',
        'regulatory_act_type',
        'consultation_document_type',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasColumn($table, 'active')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->boolean('active')->default('1');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'active')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('active');
                });
            }
        }
    }
};
