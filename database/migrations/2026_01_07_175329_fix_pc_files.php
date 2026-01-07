<?php

use App\Models\File;
use App\Models\Timeline;
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
        $file_id = 1312527;
        //File::where('id', $file_id)->delete();
        //Timeline::where('object_type', File::class)->where('object_id', $file_id)->delete();
        $pc_id = 11663;
        File::where('id_object', $pc_id)
            ->where('code_object', File::CODE_OBJ_PUBLIC_CONSULTATION)
            ->where('created_at', '>', '2026-01-06 17:53:20')
            ->update(['created_at' => '2025-10-11 10:01:13','updated_at' => '2025-10-11 10:01:13']);
        Timeline::where('object_type', File::class)
            ->where('public_consultation_id', $pc_id)
            ->where('created_at', '>', '2026-01-06 17:53:20')
            ->update(['created_at' => '2025-10-11 10:01:13','updated_at' => '2025-10-11 10:01:13']);
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
