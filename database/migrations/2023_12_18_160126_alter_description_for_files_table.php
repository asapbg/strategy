<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table((new \App\Models\File())->getTable(), function (Blueprint $table) {
            $table->text('custom_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table((new \App\Models\File())->getTable(), function (Blueprint $table) {
            $table->string('custom_name')->nullable()->change();
        });
    }
};
