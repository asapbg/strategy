<?php

namespace Database\Seeders;

use App\Enums\DynamicStructureTypesEnum;
use App\Models\DynamicStructure;
use App\Models\DynamicStructureColumn;
use Illuminate\Database\Seeder;

class DynamicStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $locales = config('available_languages');
        $data = array(
            [
                'type' => DynamicStructureTypesEnum::LEGISLATIVE_PROGRAM->value,
                'columns' => [
                    ['type' => 'text', 'ord' => 1, 'label' => 'Наименование на законопроекта'],
                    ['type' => 'text', 'ord' => 2, 'label' => 'Вносител'],
                    ['type' => 'text', 'ord' => 3, 'label' => 'Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)'],
                    ['type' => 'text', 'ord' => 4, 'label' => 'Цели, основни положения и очаквани резултати'],
                    ['type' => 'text', 'ord' => 5, 'label' => 'Необходими промени в други закони'],
                    ['type' => 'text', 'ord' => 6, 'label' => 'Изготвяне на цялостна оценка на въздействието (да/не)'],
                    ['type' => 'text', 'ord' => 7, 'label' => 'Месец на публикуване за обществени консултации'],
                    ['type' => 'text', 'ord' => 8, 'label' => 'Месец на изпращане за предварително съгласуване'],
                    ['type' => 'text', 'ord' => 9, 'label' => 'Месец на внасяне в Министерския съвет'],
                ]
            ],
            [
                'type' => DynamicStructureTypesEnum::OPERATIONAL_PROGRAM->value,
                'columns' => [
                    ['type' => 'text', 'ord' => 1, 'label' => 'Наименование на нормативния акт'],
                    ['type' => 'text', 'ord' => 2, 'label' => 'Вносител'],
                    ['type' => 'text', 'ord' => 3, 'label' => 'Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)'],
                    ['type' => 'text', 'ord' => 4, 'label' => 'Основни положения и очаквани резултати'],
                    ['type' => 'text', 'ord' => 5, 'label' => 'Законово основание за приемане'],
                    ['type' => 'text', 'ord' => 6, 'label' => 'Изготвяне на цялостна оценка на въздействието (да/не)'],
                    ['type' => 'text', 'ord' => 7, 'label' => 'Месец на публикуване за обществени консултации'],
                    ['type' => 'text', 'ord' => 8, 'label' => 'Месец на изпращане за предварително съгласуване '],
                    ['type' => 'text', 'ord' => 9, 'label' => 'Месец на внасяне в Министерския съвет'],
                ]
            ]
        );

        foreach ($data as $structure) {
            $exist = DynamicStructure::where('type', '=', (int)$structure['type'])->where('active', 1)->first();
            if( !$exist ) {
                $item = DynamicStructure::create([
                    'type' => (int)$structure['type']
                ]);
                if ( $item && sizeof($structure['columns']) ) {
                    foreach ($structure['columns'] as $col) {
                        //Create
                        $column = DynamicStructureColumn::create([
                                'dynamic_structure_id' => $item->id,
                                'type' => $col['type'],
                                'ord' => $col['ord'],
                            ]);
                        if( $column ) {
                            foreach ($locales as $loc) {
                                $column->translateOrNew($loc['code'])->label = $col['label'];
                            }
                            $column->save();
                        }
                    }
                }
                $item->save();
                $this->command->info("Dynamic structure with type ".$structure['type']." created successfully");
            }
        }
    }
}