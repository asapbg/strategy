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
            $table->text('member_job')->change();
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
            $table->string('member_job')->change();
        });
    }
};
