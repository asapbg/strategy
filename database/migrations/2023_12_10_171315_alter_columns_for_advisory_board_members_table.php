<?php

use App\Enums\AdvisoryTypeEnum;
use App\Models\AdvisoryBoardMember;
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
        Schema::table((new AdvisoryBoardMember())->getTable(), function (Blueprint $table) {
            $table->dropColumn('advisory_chairman_type_id');
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
            $table->unsignedBigInteger('advisory_chairman_type_id');
        });
    }
};
