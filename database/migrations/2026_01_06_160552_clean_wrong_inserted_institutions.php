<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE institution_translations SET deleted_at = now() WHERE institution_id IN (SELECT id FROM institution WHERE created_at > '2025-12-12 00:00:00')");
        DB::statement("UPDATE institution SET deleted_at = now() WHERE created_at > '2025-12-12 17:13:29'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
