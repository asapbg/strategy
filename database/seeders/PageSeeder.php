<?php

namespace Database\Seeders;

use App\Enums\PageModulesEnum;
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
            [
                'slug' => 'impact-assessments-info',
                'system_name' => Page::IA_INFO,
                'name_bg' => 'Обща информация',
                'name_en' => 'Обща информация',
                'content_bg' => 'Съдържание в обща информация  Оценка на въздействие',
                'content_en' => 'Content in information impact assessments',
                'is_system' => 1
            ],
            [
                'slug' => 'regulatory-framework',
                'name_bg' => 'Нормативна рамка',
                'name_en' => 'Regulatory framework',
                'content_bg' => 'Съдържание в страница Нормативна рамка',
                'content_en' => 'Content in Regulatory framework page',
                'is_system' => 0,
                'module_enum' => PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value
            ],
            [
                'slug' => 'methodological-framework',
                'name_bg' => 'Методологична рамка',
                'name_en' => 'Methodological framework',
                'content_bg' => 'Съдържание в страница Методологична рамка',
                'content_en' => 'Content in Methodological framework page',
                'is_system' => 0,
                'module_enum' => PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value
            ],
            [
                'slug' => 'methodological-framework',
                'name_bg' => 'Цялостни предварителни ОВ',
                'name_en' => 'Full preliminary impact assessment',
                'content_bg' => 'Съдържание в страница Цялостни предварителни ОВ',
                'content_en' => 'Content in Full preliminary impact assessment page',
                'is_system' => 0,
                'module_enum' => PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value
            ],
            [
                'slug' => 'subsequent-impact-assessment',
                'name_bg' => 'Последващи ОВ',
                'name_en' => 'Subsequent impact assessment',
                'content_bg' => 'Съдържание в страница Последващи ОВ',
                'content_en' => 'Content in Subsequent impact assessment page',
                'is_system' => 0,
                'module_enum' => PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value
            ],
            [
                'slug' => 'good-practices-ek',
                'name_bg' => 'Добри практики – ЕК',
                'name_en' => 'Good practices – ЕК',
                'content_bg' => 'Съдържание в страница Добри практики – ЕК',
                'content_en' => 'Content in Good practices – ЕК page',
                'is_system' => 0,
                'module_enum' => PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value
            ],
            [
                'slug' => 'good-practices-oisr',
                'name_bg' => 'Добри практики – ОИСР',
                'name_en' => 'Good practices – ОИСР',
                'content_bg' => 'Съдържание в страница Добри практики – ОИСР',
                'content_en' => 'Content in Good practices – ОИСР page',
                'is_system' => 0,
                'module_enum' => PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value
            ],
            [
                'slug' => 'documents',
                'system_name' => Page::STRATEGIC_DOCUMENT_DOCUMENTS,
                'name_bg' => 'Документи',
                'name_en' => 'Documents',
                'content_bg' => '',
                'content_en' => '',
                'is_system' => 1
            ],
            [
                'slug' => 'info',
                'system_name' => Page::STRATEGIC_DOCUMENT_INFO,
                'name_bg' => 'Обща информация',
                'name_en' => 'Information',
                'content_bg' => 'Съдържание в Обща информация',
                'content_en' => 'Content in Information',
                'is_system' => 1
            ],
            [
                'slug' => 'ogp-info',
                'system_name' => Page::OGP_INFO,
                'name_bg' => 'Обща информация',
                'name_en' => 'Information',
                'content_bg' => 'Съдържание в Обща информация',
                'content_en' => 'Content in Information',
                'is_system' => 1
            ],
            [
                'slug' => 'legislative-initiative-info',
                'system_name' => Page::LEGISLATIVE_INITIATIVE_INFO,
                'name_bg' => 'Обща информация',
                'name_en' => 'Information',
                'content_bg' => 'Съдържание в Обща информация',
                'content_en' => 'Content in Information',
                'is_system' => 1
            ],
            [
                'slug' => 'accessibility-policy',
                'system_name' => Page::ACCESS_POLICY,
                'name_bg' => 'Политика за достъпност',
                'name_en' => 'Accessibility Policy',
                'content_bg' => '',
                'content_en' => '',
                'is_system' => 1,
            ],
            [
                'slug' => 'privacy-policy',
                'system_name' => Page::PRIVACY_POLICY,
                'name_bg' => 'Политика за поверителност',
                'name_en' => 'Privacy Policy',
                'content_bg' => '',
                'content_en' => '',
                'is_system' => 1,
            ],
            [
                'slug' => 'terms-of-use',
                'system_name' => Page::TERMS,
                'name_bg' => 'Условия за ползване',
                'name_en' => 'Terms of Use',
                'content_bg' => '',
                'content_en' => '',
                'is_system' => 1,
            ],
            [
                'slug' => 'cookies',
                'system_name' => Page::COOKIES,
                'name_bg' => 'Бисквитки',
                'name_en' => 'Cookies',
                'content_bg' => '',
                'content_en' => '',
                'is_system' => 1,
            ],
        ];

        foreach ($data as $page) {
            DB::beginTransaction();
            try {
                $dbPage = Page::where('slug', '=', $page['slug'])->first();
                if(!$dbPage){
                    $item = Page::create([
                        'slug' => $page['slug'],
                        'system_name' => $page['system_name'] ?? null,
                        'is_system' => $page['is_system'] ?? 0,
                        'in_footer' => $page['in_footer'] ?? 0,
                        'module_enum' => $page['module_enum'] ?? null
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
