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
        if(\Illuminate\Support\Facades\DB::table('permissions')->get()->count()){
            \Illuminate\Support\Facades\Artisan::call('db:seed PermissionsSeeder');
        }

        if(\Illuminate\Support\Facades\DB::table('page')->get()->count()){
            \Illuminate\Support\Facades\Artisan::call('db:seed PageSeeder');
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
