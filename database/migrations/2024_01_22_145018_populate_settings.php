<?php

use App\Models\Setting;
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
        \App\Models\Setting::whereIn('name', ['advisory_boards_text_bg', 'advisory_boards_text_en'])->delete();
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'SettingsSeeder']);
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
