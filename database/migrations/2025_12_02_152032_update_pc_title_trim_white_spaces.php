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
        // Trim 2+ white spaces in the title
        DB::statement("UPDATE public_consultation_translations SET title = regexp_replace(title, '\s+', ' ', 'g') WHERE title ~ '\s{2,}'");
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
