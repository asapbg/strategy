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
        $query = Setting::where('name', 'advisory_board_new_decision_email_template')->where('section', 'advisory_boards');

        if (Schema::hasTable((new Setting())->getTable()) && $query->exists()) {
            $query->update(['section' => Setting::ADVISORY_BOARDS_SECTION]);
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
