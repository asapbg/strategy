<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = config('available_languages');

        $data = [
            [
                'slug' => 'advisory-board-documents',
                'system_name' => Page::ADV_BOARD_DOCUMENTS,
                'name_bg' => 'Документи',
                'name_en' => 'Documents',
                'content_bg' => '',
                'content_en' => '',
                'is_system' => 1
            ],
            [
                'slug' => 'advisory-board-info',
                'system_name' => Page::ADV_BOARD_INFO,
                'name_bg' => 'Обща информация',
                'name_en' => 'Information',
                'content_bg' => 'Съдържание в Обща информация',
                'content_en' => 'Content in Information',
                'is_system' => 1
            ],
            [
                'slug' => 'legislative-programs-info',
                'system_name' => Page::LP_INFO,
                'name_bg' => 'Информация (ЗП)',
                'name_en' => 'Information (LP)',
                'content_bg' => 'Съдържание в информация ЗП',
                'content_en' => 'Content in Information LP',
                'is_system' => 1
            ],
            [
                'slug' => 'operational-programs-info',
                'system_name' => Page::OP_INFO,
                'name_bg' => 'Информация (ОП)',
                'name_en' => 'Information (OP)',
                'content_bg' => 'Съдържание в информация OП',
                'content_en' => 'Content in Information OP',
                'is_system' => 1
            ],
        ];

        foreach ($data as $page) {
            DB::beginTransaction();
            try {
                $dbPage = Page::where('slug', '=', $page['slug'])->first();
                if(!$dbPage){
                    $item = Page::create([
                        'slug' => $page['slug'],
                        'system_name' => $page['system_name'],
                        'is_system' => $page['is_system']
                    ]);

                    if( $item ) {
                        foreach ($locales as $locale) {
                            $item->translateOrNew($locale['code'])->name = $page['name_'.$locale['code']];
                            $item->translateOrNew($locale['code'])->content = $page['content_'.$locale['code']];
                        }
                        $item->save();
                        $this->command->info("Page with slug ".$page['slug']." created successfully");
                    }
                }
                DB::commit();
            } catch (\Exception $e){
                Log::error('Seed pages error: '. $e);
                DB::rollBack();
            }
        }
    }
}
