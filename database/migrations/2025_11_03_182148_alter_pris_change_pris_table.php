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
        if (!Schema::hasColumn('pris_change_pris', 'created_at')) {
            Schema::table('pris_change_pris', function (Blueprint $table){
                $table->timestamps();
            });
        }
        Schema::table('pris_change_pris', function (Blueprint $table){
            $table->unsignedBigInteger('pris_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('pris_change_pris', 'created_at')) {
            Schema::table('pris_change_pris', function (Blueprint $table) {
                //$table->dropTimestamps();
            });
        }
    }
};
