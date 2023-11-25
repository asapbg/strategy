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
        $institutions = \App\Models\StrategicDocuments\Institution::get();
        $fieldsOfActions = \App\Models\FieldOfAction::get()->take(3)->pluck('id')->toArray();
        if($institutions->count() && sizeof($fieldsOfActions)) {
            foreach ($institutions as $item) {
                $item->fieldsOfAction()->sync($fieldsOfActions);
            }
        }
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
