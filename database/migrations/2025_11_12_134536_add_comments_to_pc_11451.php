<?php

use App\Models\File;
use App\Models\Timeline;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        $file_id = 1312658;
        File::where('id', $file_id)->delete();
        Timeline::where('object_type', File::class)->where('object_id', $file_id)->delete();

        $sqlFilePath = database_path('data/comments-12.11.2025.sql');

        $sql = file_get_contents($sqlFilePath);

        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
