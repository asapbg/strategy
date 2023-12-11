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
        DB::statement('ALTER TABLE users ALTER COLUMN phone TYPE character varying(255)');
        DB::statement('ALTER TABLE public_consultation ALTER consultation_level_id drop not null');
        DB::statement('ALTER TABLE public_consultation ALTER act_type_id drop not null');
        DB::statement('ALTER TABLE public_consultation_translations ALTER description drop not null');
        DB::statement('ALTER TABLE public_consultation_translations ALTER proposal_ways drop not null');
        DB::statement('ALTER TABLE public_consultation_translations ALTER COLUMN title TYPE character varying(5000)');
        DB::statement('ALTER TABLE pris ALTER COLUMN doc_num TYPE character varying(50)');

        Schema::table('pris', function (Blueprint $table) {
            $table->tinyInteger('connection_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pris', function (Blueprint $table) {
            $table->dropColumn('connection_status');
        });
    }
};
