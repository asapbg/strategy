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
        $ministryOfTransport =  \App\Models\StrategicDocuments\Institution::where('eik', '=', '000695388')->first();
        \App\Models\Consultations\PublicConsultation::where('id', 11691)->update(['importer_institution_id' => $ministryOfTransport->id]);
        \App\Models\PrisChangePris::where('pris_id', 167717)->whereNull('changed_pris_id')->delete();

        DB::statement("
            update pris
               set asap_last_version = 0
             where published_at is not null
               and deleted_at is null
               and asap_last_version = 1
               and last_version = 0
               and active = 1
               and old_id is not NULL
        ");
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
