<?php

use App\Models\AdvisoryActType;
use App\Models\AdvisoryChairmanType;
use App\Models\AuthorityAdvisoryBoard;
use App\Models\User;
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
        $tables = [(new AuthorityAdvisoryBoard())->getTable(), (new AdvisoryActType())->getTable(), (new AdvisoryChairmanType())->getTable()];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'created_by')) {
                continue;
            }

            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on((new User())->getTable());
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
        $tables = [(new AuthorityAdvisoryBoard())->getTable(), (new AdvisoryActType())->getTable(), (new AdvisoryChairmanType())->getTable()];

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'created_by')) {
                continue;
            }

            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            });
        }
    }
};
