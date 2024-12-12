<?php

use App\Http\Controllers\CommonController;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (file_exists(database_path('data/institution_history_names.sql'))) {
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
