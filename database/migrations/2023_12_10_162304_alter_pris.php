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
        Schema::table('pris', function (Blueprint $table){
            $table->string('old_connections', 10000)->nullable();
            $table->unsignedBigInteger('old_id')->nullable();
            $table->string('old_doc_num', 500)->nullable();
            $table->string('old_newspaper_full')->nullable();
        });
        DB::statement('ALTER TABLE pris ALTER institution_id drop not null');
        DB::statement('ALTER TABLE pris ALTER COLUMN protocol TYPE character varying(255)');
        DB::statement('ALTER TABLE tag_translations ALTER COLUMN label TYPE character varying(2000)');

        Schema::table('users', function (Blueprint $table){
            $table->unsignedBigInteger('old_id')->nullable();
        });

        Schema::table('public_consultation', function (Blueprint $table){
            $table->unsignedBigInteger('old_id')->nullable();
        });

        Schema::table('comments', function (Blueprint $table){
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('approved')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pris', function (Blueprint $table){
            $table->dropColumn('old_connections');
            $table->dropColumn('old_id');
            $table->dropColumn('old_doc_num');
            $table->dropColumn('old_newspaper_full');
        });

        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('old_id');
        });

        Schema::table('public_consultation', function (Blueprint $table){
            $table->dropColumn('old_id');
        });

        Schema::table('comments', function (Blueprint $table){
            $table->dropColumn('active');
            $table->dropColumn('approved');
        });
    }
};
