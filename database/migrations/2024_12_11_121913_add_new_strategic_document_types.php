<?php

use App\Models\StrategicDocumentType;
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
        $locales = config('available_languages');

        $types = [
            'Национална стратегия',
            'План за действие',
            'Национална програма',
            'Други документи',
        ];

        foreach ($types as $type) {
            $strategicType = StrategicDocumentType::whereTranslation('name', $type)->first();
            if (!$strategicType) {
                $strategicType = new StrategicDocumentType();
                $strategicType->save();
                if ($strategicType->id) {
                    foreach ($locales as $locale) {
                        $strategicType->translateOrNew($locale['code'])->name = $type;
                    }
                }
                $strategicType->save();
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
