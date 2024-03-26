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
        DB::statement('ALTER TABLE pris_translations ALTER about drop not null');
        DB::statement('ALTER TABLE pris_translations ALTER legal_reason drop not null');
        DB::statement('ALTER TABLE pris_translations ALTER importer drop not null');
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
