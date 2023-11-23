<?php

use App\Models\FieldOfAction;
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
        $table = (new FieldOfAction())->getTable();

        $columns = ['name_bg', 'name_en'];

        if (Schema::hasColumns($table, $columns)) {
            Schema::dropColumns($table, $columns);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
    }
};
