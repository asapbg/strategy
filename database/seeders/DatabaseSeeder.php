<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
//            InstitutionSeeder::class,
            RolesSeeder::class,
            PermissionsSeeder::class,
            UsersSeeder::class,
//            InstitutionLevelsSeeder::class,
            ConsultationLevelSeeder::class,
            StrategicDocumentLevelsSeeder::class,
            StrategicDocumentTypesSeeder::class,
            AuthorityAcceptingStrategicSeeder::class,
            AuthorityAdvisoryBoardSeeder::class,
            AdvisoryActTypeSeeder::class,
            StrategicActTypeSeeder::class,
            AdvisoryChairmanTypeSeeder::class,
            ActTypesSeeder::class,
            LegalActTypesSeeder::class,
            ConsultationDocumentTypesSeeder::class,
            ConsultationTypesSeeder::class,
            PublicationCategorySeeder::class,
            RegulatoryActTypesSeeder::class,
            PolicyAreaSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
