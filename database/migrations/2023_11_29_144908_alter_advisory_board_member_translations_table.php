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
            $table->renameColumn('name', 'member_name');
            $table->renameColumn('job', 'member_job');
            $table->string('job')->nullable()->change();
            $table->longText('member_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table((new AdvisoryBoardMemberTranslation())->getTable(), function (Blueprint $table) {
            $table->dropColumn('member_notes');
            $table->renameColumn('member_job', 'job');
            $table->renameColumn('member_name', 'name');
            $table->string('member_job')->change();
        });
    }
};
