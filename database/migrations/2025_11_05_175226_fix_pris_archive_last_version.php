<?php

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
        DB::statement("
            update pris set asap_last_version = 1
            where
                in_archive = 1
                and legal_act_type_id = ".\App\Models\LegalActType::TYPE_LAW."
                and published_at is not null
                and deleted_at is null
                and asap_last_version = 0
                and last_version = 1
                and old_id is not null
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
