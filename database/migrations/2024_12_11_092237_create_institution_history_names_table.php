<?php

use App\Http\Controllers\CommonController;
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
        Schema::create('institution_history_names', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('institution_id');
            $table->foreign('institution_id')->references('id')->on('institution');
            $table->string('name');
            $table->date('valid_from')->default(DB::raw('CURRENT_DATE'));
            $table->date('valid_till')->nullable();
            $table->boolean('current')->default(true);
            $table->timestamps();
        });

        if (file_exists(database_path('data/institution_history_names.sql'))) {
            $institution_history_names_dump = file_get_contents(database_path('data/institution_history_names.sql'));
            DB::connection()->getPdo()->exec($institution_history_names_dump);

            CommonController::fixSequence('institution_history_names');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institution_history_names');
    }
};
