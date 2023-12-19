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
        Schema::table('ogp_status', function (Blueprint $table) {
            $table->unsignedInteger('type')->default(1);
        });

        //\Illuminate\Support\Facades\Artisan::call('db:seed OgpStatusSeeder');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ogp_status', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
