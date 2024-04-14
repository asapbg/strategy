<?php namespace App\Sorter\PublicConsultation;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class Date extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('public_consultation.created_at', $direction);
    }
}


