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
        Schema::create('law', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('law_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('law_id');
            $table->foreign('law_id')->references('id')->on('law');
            $table->unique(['law_id', 'locale']);
            $table->text('name');
            $table->softDeletes();
        });

        if(\App\Models\User::count()) {
            $locales = config('available_languages');
            $csvFile = fopen(base_path("database/data/laws.csv"), "r");
            $firstRow = true;
            while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
                if($firstRow) {$firstRow = false; continue;}
                if(is_array($data) && sizeof($data) == 1) {
                    $item = \App\Models\Law::create([]);
                    if( $item ) {
                        foreach ($locales as $locale) {
                            $item->translateOrNew($locale['code'])->name = $data[0];
                        }
                    }
                    $item->save();
                }
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
        Schema::dropIfExists('law');
    }
};
