<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable((new Setting())->getTable()) && Setting::where('name', Setting::TYPE_SYNC)->doesntExist()) {
            Setting::create([
                'section'       => Setting::TYPE_SYNC,
                'name'          => Setting::TYPE_SYNC,
                'type'          => Setting::TYPE_SYNC,
                'editable'      => 1,
                'is_required'   => 0,
            ]);
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
