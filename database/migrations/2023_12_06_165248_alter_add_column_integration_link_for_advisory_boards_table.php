<?php

use App\Models\AdvisoryBoard;
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
        Schema::table((new AdvisoryBoard())->getTable(), function (Blueprint $table) {
            $table->string('integration_link')->nullable();
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
            $table->dropColumn('integration_link');
        });
    }
};