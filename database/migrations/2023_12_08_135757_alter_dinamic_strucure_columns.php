<?php

use App\Enums\DynamicStructureColumnTypesEnum;
use App\Enums\DynamicStructureTypesEnum;
use App\Models\DynamicStructure;
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
        if(\App\Models\DynamicStructure::count()) {
            $locales = config('available_languages');

            foreach ([DynamicStructureTypesEnum::LEGISLATIVE_PROGRAM->value, DynamicStructureTypesEnum::OPERATIONAL_PROGRAM->value] as $type) {
                $structure = DynamicStructure::with(['columns'])
                    ->where('type', '=', $type)
                    ->where('active', 1)
                    ->first();
                if( $structure ) {
                    if($structure->columns->count()) {
                        foreach ($structure->columns as $c) {
                            //up all ord columns after #3
                            if($c->ord > 3){
                                $c->update(['ord' => ($c->ord + 1)]);
                                $c->save();
                            } else if ($c->ord == 3) {
                                //change column type for third col
                                $c->update(['type' => DynamicStructureColumnTypesEnum::BOOLEAN]);
                                //update translation for this column
                                foreach ($locales as $loc) {
                                    $c->translateOrNew($loc['code'])->label = 'Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС';
                                }
                                $c->save();
                            }
                        }

                        //insert new column with order 4
                        //Create
                        $column = DynamicStructureColumn::create([
                            'dynamic_structure_id' => $structure->id,
                            'type' => DynamicStructureColumnTypesEnum::TEXT,
                            'ord' => 4,
                            'dynamic_structure_groups_id' => null,
                        ]);
                        if( $column ) {
                            foreach ($locales as $loc) {
                                $column->translateOrNew($loc['code'])->label = '№ в Плана за действие';
                            }
                            $column->save();
                        }
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
