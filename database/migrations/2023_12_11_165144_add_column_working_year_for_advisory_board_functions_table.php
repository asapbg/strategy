<?php

use App\Models\AdvisoryBoardFunction;
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
        Schema::table((new AdvisoryBoardFunction())->getTable(), function (Blueprint $table) {
            $table->timestamp('working_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table((new AdvisoryBoardFunction())->getTable(), function (Blueprint $table) {
            $table->dropColumn('working_year');
        });
    }
};
