<?php namespace App\Sorter\PublicConsultation;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class Title extends QuerySorter implements SorterContract{

    public function handle($value, $filter = null): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('public_consultation_translations.title', $direction);
    }
}


