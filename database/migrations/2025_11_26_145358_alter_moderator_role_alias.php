<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\CustomRole::where('name', 'moderator-advisory')->update(['name' => 'moderator-public-consultation']);
        \App\Models\CustomRole::where('name', 'moderator-legal')->update([
            'name' => 'moderator-pris',
            'display_name' => 'Модератор „Актове на Министерския съвет"',
        ]);
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
