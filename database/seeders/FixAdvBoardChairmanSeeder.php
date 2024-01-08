<?php

namespace Database\Seeders;

use App\Models\AdvisoryBoard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FixAdvBoardChairmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Fix adv board chairman start " . date("H:i"));
        $json = File::get(database_path('data/fix_adv_board_positions.json'));

        if (!is_json($json)) {
            return;
        }

        $positions = json_decode($json, true);

        if(sizeof($positions)) {
            foreach ($positions as $id => $position) {
                AdvisoryBoard::where('id', $id)->update(['advisory_chairman_type_id' => $position]);
            }
        }
        $this->command->info("Fix adv board chairman end " . date("H:i"));
    }
}
