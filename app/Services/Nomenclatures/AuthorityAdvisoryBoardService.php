<?php

namespace App\Services\Nomenclatures;

use App\Models\AuthorityAdvisoryBoard;
use App\Traits\StoreTranslatableFieldsTrait;

class AuthorityAdvisoryBoardService
{

    use StoreTranslatableFieldsTrait;

    public function create(string $name_bg, string $name_en)
    {
        $exists = AuthorityAdvisoryBoard::with('translations')->whereHas('translations', fn($q) => $q->where('name', $name_bg))->first();

        if ($exists) {
            return $exists;
        }

        $new = AuthorityAdvisoryBoard::create([
            'created_by' => auth()->id(),
        ]);

        return $this->storeTranslateOrNew(AuthorityAdvisoryBoard::TRANSLATABLE_FIELDS, $new, ['name_bg' => $name_bg, 'name_en' => $name_en]);
    }
}
