<?php

namespace App\Filters\StrategicDocument;

use App\Filters\FilterContract;
use App\Filters\QueryFilter;


class Status extends QueryFilter implements FilterContract{

    public function handle($value): void
    {
        if( !empty($value) ){
            switch ($value){
                case 'active':
                    $this->query->where(function ($q){
                        $q->where('strategic_document.document_date_expiring', '>', now())
                            ->orWhereNull('strategic_document.document_date_expiring');
                    });
                    break;
                case 'expired':
                    $this->query->where('strategic_document.document_date_expiring', '<=', now());
                    break;
                case 'public_consultation':
                    $this->query->whereHas('publicConsultation', function ($q) {
                        $q->where('active', '=', '1')
                            ->where('open_to', '<=', now());
                    });
                    break;
            }
        }
    }
}

