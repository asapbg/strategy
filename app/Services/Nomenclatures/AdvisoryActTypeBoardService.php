<?php

namespace App\Services\Nomenclatures;

use App\Models\AdvisoryActType;
use App\Traits\StoreTranslatableFieldsTrait;

class AdvisoryActTypeBoardService
{

    use StoreTranslatableFieldsTrait;

    public function create(string $name_bg, string $name_en)
    {
        $exists = AdvisoryActType::with('translations')->whereHas('translations', fn($q) => $q->where('name', $name_bg))->first();

        if ($exists) {
            return $exists;
        }

        $new = AdvisoryActType::create([
            'created_by' => auth()->id(),
        ]);

        return $this->storeTranslateOrNew(AdvisoryActType::TRANSLATABLE_FIELDS, $new, ['name_bg' => $name_bg, 'name_en' => $name_en]);
    }
}
