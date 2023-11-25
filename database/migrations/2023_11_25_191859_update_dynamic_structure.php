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
    public function up()
    {
        \App\Models\DynamicStructureColumn::whereIn('id', [12, 3])->update(['type' => \App\Enums\DynamicStructureColumnTypesEnum::TEXT]);
        \App\Models\Consultations\OperationalProgramRow::where('dynamic_structures_column_id', '=', 12)
            ->where('value', '0')->update(['value' => 'Не']);
        \App\Models\Consultations\OperationalProgramRow::where('dynamic_structures_column_id', '=', 12)
            ->where('value', '1')->update(['value' => 'Да']);

        \App\Models\Consultations\LegislativeProgramRow::where('dynamic_structures_column_id', '=', 3)
            ->where('value', '0')->update(['value' => 'Не']);
        \App\Models\Consultations\LegislativeProgramRow::where('dynamic_structures_column_id', '=', 3)
            ->where('value', '1')->update(['value' => 'Да']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
