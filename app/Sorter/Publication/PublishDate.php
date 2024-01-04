<?php namespace App\Sorter\Publication;

use App\Sorter\QuerySorter;
use App\Sorter\SorterContract;

class PublishDate extends QuerySorter implements SorterContract{

    public function handle($value): void
    {
        $direction = $value ?? 'asc';
        $this->query->orderBy('publication.published_at', $direction);
    }
}


