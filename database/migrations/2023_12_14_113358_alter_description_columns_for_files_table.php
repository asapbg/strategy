<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\File;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table((new File())->getTable(), function (Blueprint $table) {
            $table->longText('description_bg')->nullable()->change();
            $table->longText('description_en')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table((new File())->getTable(), function (Blueprint $table) {
            $table->string('description_bg')->nullable()->change();
            $table->string('description_en')->nullable()->change();
        });
    }
};
