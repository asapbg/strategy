<?php

use App\Models\AdvisoryBoardMeetingTranslation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table((new AdvisoryBoardMeetingTranslation())->getTable(), function (Blueprint $table) {
            $table->longText('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table((new AdvisoryBoardMeetingTranslation())->getTable(), function (Blueprint $table) {
            $table->string('description')->change();
        });
    }
};
