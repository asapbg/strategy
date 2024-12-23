<?php

use App\Http\Controllers\CommonController;
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
        if (file_exists(public_path(DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'institution_history_names.sql'))) {
            \App\Models\InstitutionHistoryName::truncate();
            $institution_history_names_dump = file_get_contents(database_path('data/institution_history_names.sql'));
            DB::connection()->getPdo()->exec($institution_history_names_dump);

            CommonController::fixSequence('institution_history_names');
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
