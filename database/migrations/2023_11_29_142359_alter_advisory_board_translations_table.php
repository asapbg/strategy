<?php

use App\Models\AdvisoryBoardTranslation;
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
        $table = (new AdvisoryBoardTranslation())->getTable();
        $columns = [
            'advisory_specific_name',
            'advisory_act_specific_name',
            'report_institution_specific_name'
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column)) {
                Schema::table($table, function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table((new AdvisoryBoardTranslation())->getTable(), function (Blueprint $table) {
            $table->string('advisory_specific_name')->nullable();
            $table->string('advisory_act_specific_name')->nullable();
            $table->string('report_institution_specific_name')->nullable();
        });
    }
};
