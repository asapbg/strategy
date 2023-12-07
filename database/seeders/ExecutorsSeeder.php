<?php

namespace Database\Seeders;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CommonController;
use App\Models\Executor;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExecutorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Start seeding executors");

        $file = DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR."executors.csv";
        if (file_exists(public_path($file))) {
            $file_handle = fopen(public_path($file), 'r');
            $key = 1;
            $count = 0;

            DB::beginTransaction();

            while (!feof($file_handle)) {
                $line_of_text = fgetcsv($file_handle, 0, ',');
                if ($key > 3 && is_array($line_of_text)) {
                    //dd($line_of_text);

                    $data['contractor_name_bg'] = $line_of_text[1];
                    $data['contractor_name_en'] = $line_of_text[1];
                    $data['executor_name_bg'] = $line_of_text[2];
                    $data['executor_name_en'] = $line_of_text[2];
                    $data['contract_subject_bg'] = $line_of_text[5];
                    $data['contract_subject_en'] = $line_of_text[5];
                    $data['services_description_bg'] = $line_of_text[6];
                    $data['services_description_en'] = $line_of_text[6];
                    $eik = trim($line_of_text[3]);
                    $data['eik'] = !empty($eik) ? $eik : null;
                    $data['contract_date'] = databaseDate(trim(explode(" ", $line_of_text[4])[0]));
                    $price = str_replace(",", "", trim(explode(" ", $line_of_text[7])[0]));
                    $data['price'] = !empty($price) ? $price : null;

                    if (empty($data['contractor_name_bg']) && empty($data['executor_name_bg'])) {
                        continue;
                    }

                    $request = new Request();
                    $controller = new AdminController($request);
                    $item = new Executor();
                    $fillable = $controller->getFillableValidated($data, $item);
                    $item->fill($fillable);
                    $item->save();

                    $controller->storeTranslateOrNew(Executor::TRANSLATABLE_FIELDS, $item, $data);
                    $count++;
                }
                $key++;
            }
            fclose($file_handle);

            DB::commit();

            $this->command->info("$count executors was added successfully");
        } else {
            $this->command->warn("File executors.csv was not found");
        }
    }
}
