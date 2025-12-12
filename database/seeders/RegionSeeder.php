<?php

namespace Database\Seeders;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Import of regions nuts2 begins at " . date("H:i"));

        $json = File::get(database_path('data/ek_reg2.json'));

        if (!is_json($json)) {
            return;
        }

        $imported = 0;
        $regions = json_decode($json, true);

        foreach ($regions as $region) {
            if (!isset($region['region'])) continue;

            $exist = Region::withTrashed()->whereCode($region['region'])->first();

            if ($exist) {
                continue;
            }

            $db_region['code'] = $region['region'];
            $db_region['nuts1'] = $region['nuts1'];

            $newRegion = Region::create($db_region);

            $translations['name_bg'] = $region['name'];
            $translations['name_en'] = $region['name_en'];

            $controller = new AdminController(new Request());
            $controller->storeTranslateOrNew(Region::TRANSLATABLE_FIELDS, $newRegion, $translations);

            $imported++;
        }

        $this->command->info("$imported regions nuts2 were imported successfully at " . date("H:i"));
    }
}
