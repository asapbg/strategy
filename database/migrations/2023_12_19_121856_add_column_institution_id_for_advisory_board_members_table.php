<?php

use App\Models\AdvisoryBoardMember;
use App\Models\StrategicDocuments\Institution;
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
            $table->unsignedBigInteger('institution_id')->nullable();
            $table->foreign('institution_id')->references('id')->on((new Institution())->getTable())->onDelete('cascade');
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
            $table->dropColumn('institution_id');
        });
    }
};
