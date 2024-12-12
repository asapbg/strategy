<?php

use App\Models\AdvisoryBoardMember;
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
    public function up(): void
    {
        Schema::table((new AdvisoryBoardMember())->getTable(), function (Blueprint $table) {
            $table->boolean('is_advisory_board_member')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table((new AdvisoryBoardMember())->getTable(), function (Blueprint $table) {
            $table->dropColumn('is_advisory_board_member');
        });
    }
};
