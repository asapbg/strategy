<?php

use App\Models\Region;
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
    public function up()
    {
        if (!Schema::hasColumn('strategic_document', 'region_id')) {
            Schema::table('strategic_document', function (Blueprint $table) {
               $table->foreignIdFor(Region::class)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('strategic_document', 'region_id')) {
            Schema::table('strategic_document', function (Blueprint $table) {
                $table->dropColumn('region_id');
            });
        }
    }
};
