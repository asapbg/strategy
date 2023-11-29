<?php

use App\Models\AdvisoryBoard;
use App\Models\AuthorityAdvisoryBoard;
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
        $table = (new AdvisoryBoard())->getTable();

        if (Schema::hasColumn($table, 'report_institution_id')) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('report_institution_id');
            });
        }

        Schema::table($table, function (Blueprint $table) {
            $table->boolean('has_npo_presence')->default(false);
            $table->unsignedBigInteger('authority_id')->nullable();
            $table->foreign('authority_id')->references('id')->on((new AuthorityAdvisoryBoard())->getTable())->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table((new AdvisoryBoard())->getTable(), function (Blueprint $table) {
            $table->dropColumn('has_npo_presence');
            $table->dropColumn('authority_id');
            $table->unsignedBigInteger('report_institution_id')->nullable();
            $table->foreign('report_institution_id')->references('id')->on((new Institution())->getTable())->onDelete('cascade');
        });
    }
};
