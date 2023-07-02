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
            RolesSeeder::class,
            PermissionsSeeder::class,
            UsersSeeder::class,
            InstitutionLevelsSeeder::class,
            ConsultationCategorySeeder::class,
            StrategicDocumentLevelsSeeder::class,
            StrategicDocumentTypesSeeder::class,
            AuthorityAcceptingStrategicSeeder::class,
            AuthorityAdvisoryBoardSeeder::class,
            AdvisoryActTypeSeeder::class,
            ActTypesSeeder::class,
            LegalActTypesSeeder::class,
        ]);
    }
}
