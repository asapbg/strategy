<?php

namespace App\Filters\PublicConsultation;

use App\Enums\DocTypesEnum;
use App\Filters\FilterContract;
use App\Filters\QueryFilter;
use App\Models\ActType;
use App\Models\File;
use Illuminate\Database\Eloquent\Builder;
use function Clue\StreamFilter\fun;


class MissingDocuments extends QueryFilter implements FilterContract{

    public function handle($value, $filter = null): void
    {
        if( is_array($value) && sizeof($value) ){
            foreach ($value as $v){
                if(!empty($v)){
                    if(str_contains($v, '_')){
                        $explode = explode('_', $v);
                        $table1 = 'f'.$explode[0];
                        $table2 = 'f'.$explode[1];
                        $this->query->leftjoin('files as '.$table1, function ($j) use($explode, $table1){
                            $j->on($table1.'.id_object', '=', 'public_consultation.id')
                                ->where($table1.'.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                                ->where($table1.'.locale', '=', app()->getLocale())
                                ->where($table1.'.doc_type', '=', $explode[0]);
                        })->leftjoin('files as '.$table2, function ($j) use($explode, $table2){
                            $j->on($table2.'.id_object', '=', 'public_consultation.id')
                                ->where($table2.'.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                                ->where($table2.'.locale', '=', app()->getLocale())
                                ->where($table2.'.doc_type', '=', $explode[1]);
                        })->whereNull($table1.'.id')
                            ->whereNull($table2.'.id')
                            ->whereIn('public_consultation.act_type_id', [ActType::ACT_LAW, ActType::ACT_COUNCIL_OF_MINISTERS, ActType::ACT_MINISTER, ActType::ACT_OTHER_CENTRAL_AUTHORITY, ActType::ACT_REGIONAL_GOVERNOR, ActType::ACT_MUNICIPAL, ActType::ACT_MUNICIPAL_MAYOR]);
                    } else{
                        $table = 'f'.$v;
                        $pcTypes = null;
                        if (in_array($v, [DocTypesEnum::PC_IMPACT_EVALUATION->value, DocTypesEnum::PC_IMPACT_EVALUATION_OPINION->value])){
                            $pcTypes = [ActType::ACT_LAW, ActType::ACT_COUNCIL_OF_MINISTERS];
                        }
                        $this->query->leftjoin('files as '.$table, function ($j) use($v, $table){
                            $j->on($table.'.id_object', '=', 'public_consultation.id')
                                ->where($table.'.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                                ->where($table.'.locale', '=', app()->getLocale())
                                ->where($table.'.doc_type', '=', $v);
                        })->whereNull($table.'.id')
                            ->when($pcTypes, function ($query) use($pcTypes) {
                                $query->whereIn('public_consultation.act_type_id', $pcTypes);
                            });
                    }

                }
            }
        }
    }
}

