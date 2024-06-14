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
        DB::statement('CREATE INDEX files_object_code_locale_idx ON files (id_object, code_object, locale);');
        DB::statement('CREATE INDEX files_object_code_type_locale_idx ON files (id_object, code_object, doc_type, locale);');
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
