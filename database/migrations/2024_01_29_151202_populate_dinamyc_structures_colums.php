<?php

use App\Enums\DynamicStructureColumnTypesEnum;
use App\Enums\DynamicStructureTypesEnum;
use App\Models\DynamicStructureColumn;
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
        $locales = config('available_languages');
        $newCol = ['type' => DynamicStructureColumnTypesEnum::TEXT, 'ord' => 2, 'label' => 'Номер на програма'];

        foreach ([DynamicStructureTypesEnum::LEGISLATIVE_PROGRAM->value, DynamicStructureTypesEnum::OPERATIONAL_PROGRAM->value] as $programType){
            $structure = \App\Models\DynamicStructure::where('type', '=', $programType)->first();
            if($structure){
                //Create
                $column = DynamicStructureColumn::create([
                    'dynamic_structure_id' => $structure->id,
                    'type' => $newCol['type'],
                    'ord' => $newCol['ord'],
                    'dynamic_structure_groups_id' => null,
                ]);
                if( $column ) {
                    foreach ($locales as $loc) {
                        $column->translateOrNew($loc['code'])->label = $newCol['label'];
                    }
                    $column->save();
                }
            }
        }
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
