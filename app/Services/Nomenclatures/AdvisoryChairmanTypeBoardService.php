<?php

namespace App\Services\Nomenclatures;

use App\Models\AdvisoryChairmanType;
use App\Traits\StoreTranslatableFieldsTrait;

class AdvisoryChairmanTypeBoardService
{

    use StoreTranslatableFieldsTrait;

    public function create(string $name_bg, string $name_en)
    {
        $exists = AdvisoryChairmanType::with('translations')->whereHas('translations', fn($q) => $q->where('name', $name_bg))->first();

        if ($exists) {
            return $exists;
        }

        $new = AdvisoryChairmanType::create([
            'created_by' => auth()->id(),
        ]);

        return $this->storeTranslateOrNew(AdvisoryChairmanType::TRANSLATABLE_FIELDS, $new, ['name_bg' => $name_bg, 'name_en' => $name_en]);
    }
}
