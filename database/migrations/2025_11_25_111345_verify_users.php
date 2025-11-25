<?php

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
        \App\Models\User::whereNotNull('email_verified_at')
            ->where('activity_status', \App\Models\User::STATUS_REG_IN_PROCESS)
            ->update(['activity_status' => \App\Models\User::STATUS_ACTIVE]);
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
