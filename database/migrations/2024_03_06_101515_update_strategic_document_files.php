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
        if(\App\Models\StrategicDocumentFile::get()->count()){
            DB::statement('update strategic_document_file set strategic_document_type_id = '.\App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT);
        }
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
