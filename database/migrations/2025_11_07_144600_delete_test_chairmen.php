<?php

use App\Models\AdvisoryChairmanType;
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
        AdvisoryChairmanType::where('id', 6)->whereTranslation('name', 'ДРУГ')->delete();
        AdvisoryChairmanType::where('id', 7)->whereTranslation('name', 'Test')->delete();
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
