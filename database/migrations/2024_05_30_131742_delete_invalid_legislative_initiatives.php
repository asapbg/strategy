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
        DB::statement('update legislative_initiative set legislative_initiative.deleted_at = \'2024-05-30 00:00:00\' WHERE legislative_initiative.id IN (select li.id from legislative_initiative li left join law l on l.id = li.law_id where l.id is null)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
