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
        Schema::create('institution_field_of_action', function (Blueprint $table) {
            $table->unsignedBigInteger('institution_id');
            $table->unsignedBigInteger('field_of_action_id');
        });

        $institutions = \App\Models\StrategicDocuments\Institution::get();
        if($institutions->count()) {
            foreach ($institutions as $item) {
                $item->fieldsOfAction()->sync([1,2,3]);
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
        Schema::dropIfExists('institution_field_of_action');
    }
};
