<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\File;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table((new File())->getTable(), function (Blueprint $table) {
            $table->boolean('active')->default('1');
            $table->string('resolution_council_ministers')->nullable();
            $table->string('state_newspaper')->nullable();
            $table->timestamp('effective_at')->nullable();
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
            $table->dropColumn('active');
            $table->dropColumn('resolution_council_ministers');
            $table->dropColumn('state_newspaper');
            $table->dropColumn('effective_at');
        });
    }
};
