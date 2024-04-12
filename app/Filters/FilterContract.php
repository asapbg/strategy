<?php namespace App\Filters;

interface FilterContract {
    public function handle($value, $filter = null): void;
}

