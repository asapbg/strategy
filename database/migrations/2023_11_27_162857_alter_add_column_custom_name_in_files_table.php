<?php

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
        $table = (new \App\Models\File())->getTable();

        Schema::whenTableDoesntHaveColumn($table, 'custom_name', function ($table) {
            $table->string('custom_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $table = (new \App\Models\File())->getTable();

        Schema::whenTableHasColumn($table, 'custom_name', function ($table) {
            $table->dropColumn('custom_name');
        });
    }
};
