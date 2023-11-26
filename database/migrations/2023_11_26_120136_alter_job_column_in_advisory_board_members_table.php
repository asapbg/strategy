<?php

use App\Models\AdvisoryBoardMemberTranslation;
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
        Schema::table((new AdvisoryBoardMemberTranslation())->getTable(), function (Blueprint $table) {
            $table->string('job')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('advisory_board_members', function (Blueprint $table) {
            $table->string('job')->change();
        });
    }
};
