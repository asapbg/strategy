<?php

use App\Models\ConsultationLevel;
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
        $table = (new \App\Models\AdvisoryBoardMember())->getTable();
        $column = 'consultation_level_id';

        if (Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table((new \App\Models\AdvisoryBoardMember())->getTable(), function (Blueprint $table) {
            $table->unsignedBigInteger('consultation_level_id')->nullable();
            $table->foreign('consultation_level_id')->references('id')->on((new ConsultationLevel())->getTable())->onDelete('cascade');
        });
    }
};
