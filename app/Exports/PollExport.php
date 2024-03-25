<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PollExport implements FromView, ShouldAutoSize
{
    public function __construct($poll)
    {
        $this->poll = $poll;
        $this->statistic = $poll->getStats();
    }

    public function view(): View
    {
        return view('exports.poll', [
            'poll' => $this->poll,
            'statistic' => $this->statistic
        ]);
    }
}
