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
        Schema::table('field_of_actions', function (Blueprint $table) {
            $table->unsignedBigInteger('parentid')->nullable();
        });
        if(\Illuminate\Support\Facades\DB::table('field_of_action_translations')->count()) {
            Schema::disableForeignKeyConstraints();
            \Illuminate\Support\Facades\DB::table('field_of_action_translations')->truncate();
            \Illuminate\Support\Facades\DB::table('field_of_actions')->truncate();
            Schema::enableForeignKeyConstraints();

            \Illuminate\Support\Facades\Artisan::call('db:seed FieldOfActionSeeder');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('field_of_actions', function (Blueprint $table) {
            $table->dropColumn('parentid');
        });
    }
};
