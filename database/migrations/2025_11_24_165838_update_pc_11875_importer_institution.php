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
        $importer = \App\Models\StrategicDocuments\InstitutionTranslation::where('name', 'Общинска администрация - Търговище')->first();
        if ($importer) {
            \App\Models\Consultations\PublicConsultation::where('id', 11875)->update(['importer_institution_id' => $importer->institution_id]);
        } else {
            \App\Models\Consultations\PublicConsultation::where('id', 11875)->update(['importer_institution_id' => 501]);
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
