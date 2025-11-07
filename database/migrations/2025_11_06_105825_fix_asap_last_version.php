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
                published_at is not null
                and deleted_at is null
                and asap_last_version = 0
                and last_version = 1
                and active = 1
                and old_id is not NULL
        ");
//        DB::statement("
//            update pris set asap_last_version = 1
//            where
//                published_at is not null
//                and deleted_at is null
//                and asap_last_version = 0
//                and last_version = 1
//                and active = 1
//                and old_id is not NULL
//                and not EXISTS (
//                    select 1
//                    from pris pr
//                    where pr.published_at is not null
//                    and pr.deleted_at is null
//                    and pr.asap_last_version = 1
//                    and pr.last_version = 1
//                    and pr.active = 1
//                    and old_id is not NULL
//                    and pr.doc_num = pris.doc_num
//                    and pr.doc_date = pris.doc_date
//                    and pr.legal_act_type_id = pris.legal_act_type_id
//                    and COALESCE(pr.protocol, '') = COALESCE(pris.protocol, '')
//                )
//        ");
//        \App\Models\PrisChangePris::where('pris_id', 38325)->update(['pris_id' => 14531]);
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
