<?php namespace App\Sorter\PublicConsultation;

use App\Sorter\SorterContract;
use App\Sorter\QuerySorter;

class RegNum extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('public_consultation.reg_num', $direction);
    }
}


