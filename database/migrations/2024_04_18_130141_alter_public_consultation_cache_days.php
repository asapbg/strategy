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
        Schema::table('public_consultation', function (Blueprint $table) {
            $table->integer('active_in_days')->nullable();
        });

        if(\App\Models\Publication::get()->count()){
            DB::statement(
                'update public_consultation set active_in_days = ((open_to::date) - (open_from::date)) where open_to is not null'
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_consultation', function (Blueprint $table) {
            $table->dropColumn('active_in_days');
        });
    }
};
