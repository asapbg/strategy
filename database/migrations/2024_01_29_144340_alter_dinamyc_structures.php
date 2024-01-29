<?php

use App\Enums\DynamicStructureTypesEnum;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ([DynamicStructureTypesEnum::LEGISLATIVE_PROGRAM->value, DynamicStructureTypesEnum::OPERATIONAL_PROGRAM->value] as $programType){
            $structure = \App\Models\DynamicStructure::where('type', '=', $programType)->first();
            if($structure){
                $structureColumns = \App\Models\DynamicStructureColumn::where('ord', '>', 1)
                    ->where('dynamic_structure_id', '=', $structure->id)
                    ->orderBy('ord', 'desc')
                    ->get();
                if($structureColumns->count()){
                    foreach ($structureColumns as $col){
                        $col->ord += 1;
                        $col->save();
                    }
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
