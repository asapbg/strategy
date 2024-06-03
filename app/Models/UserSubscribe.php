<?php

namespace App\Models;


use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\Consultations\PublicConsultation;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserSubscribe extends ModelActivityExtend
{
    const MODULE_NAME = ('custom.user_subscribes');

    const CONDITION_PUBLISHED = 1;

    const SUBSCRIBED = 1;
    const UNSUBSCRIBED = 0;

    const CHANNEL_EMAIL = 1;
    const CHANNEL_RSS = 2;

    /**
     * The name of the Model that will be used for activity logs
     *
     * @var string
     */
    protected string $logName = 'user-subscribes';

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphTo
     */
    public function subscribable()
    {
        return $this->morphTo()->withoutGlobalScope(SoftDeletingScope::class);
    }

    public function readFilter(): array
    {
        $filter = [];
        if(!$this->subscribable_id && !empty($this->search_filters)){
            $jsonFilter = json_decode($this->search_filters);
            if(is_null($jsonFilter)){
                dd($this->id);
            }
            switch ($this->subscribable_type){
                case 'App\Models\Consultations\PublicConsultation':
                        foreach ($jsonFilter as $key => $value){
                            if(!empty($value)){
                                $filter[$key] = $this->getPcFilter($key, $value);
                            }
                        }
                    break;
            }
        }
        return $filter;
    }

    public function itemTitle(): string
    {
        $title = '';
        if($this->subscribable_id){
            switch ($this->subscribable_type){
                case 'App\Models\Consultations\PublicConsultation':
                    $title = PublicConsultation::find($this->subscribable_id)->title;
                    break;
            }
        }
        return $title;
    }

    public function filterToTxt(): string
    {
        $txt = '';
        $filter = $this->readFilter();
        if(sizeof($filter)){
            foreach ($filter as $f){
                $txt .= $f['viewLabel'].': '.$f['viewValue'].'; ';
            }
        }
        return $txt;
    }

    public static function filterToTextById($id)
    {
        $item = self::find($id);
        return $item->filterToTxt();
    }

    private function getPcFilter(string $key, mixed $value){
        $filter = array(
            'key' => $key,
            'value' => $value,
            'viewLabel' => '',
            'viewValue' => ''
        );

        switch ($key){
            case 'name':
                if(!empty($value)){
                    $filter['viewLabel'] = __('custom.name');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'openFrom':
                if(!empty($value)){
                    $filter['viewLabel'] = __('custom.begin_date');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'openTo':
                if(!empty($value)){
                    $filter['viewLabel'] = __('custom.end_date');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'consultationNumber':
                if(!empty($value)){
                    $filter['viewLabel'] = __('custom.consultation_number');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'actTypes':
                if(!empty($value)){
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = __('custom.act_type');
                    $labels = ActType::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'importers':
                if(!empty($value)){
                    $values = $this->getArrayValues($value);
                    $labels = Institution::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'areas':
            case 'municipalities':
            case 'fieldOfActions':
                if(!empty($value)) {
//                    $levelKey = $key == 'areas' ? InstitutionCategoryLevelEnum::AREA->value : ($key == 'municipalities' ? InstitutionCategoryLevelEnum::MUNICIPAL->value : InstitutionCategoryLevelEnum::CENTRAL->value);
                    $values = $this->getArrayValues($value);
//                    $filter['viewLabel'] = trans_choice('custom.field_of_actions', 1).' ('.InstitutionCategoryLevelEnum::keyToLabel()[$levelKey].' '.__('custom.level_lower_case').')';
                    $filter['viewLabel'] = trans_choice('custom.field_of_actions', 1);
                    $labels = FieldOfAction::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'level':
                if(!empty($value)) {
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = capitalize(__('custom.level_lower_case'));
                    $labels = [];
                    foreach ($values as $v){
                        $labels[] = InstitutionCategoryLevelEnum::keyToLabel()[$v];
                    }
                    $filter['viewValue'] = implode(', ', $labels);
                }
                break;
        }
        return $filter;
    }

    private function getArrayValues($vals){
        if(is_array($vals)){
            if(sizeof($vals) && str_contains($vals[0], ',')){
                $values = explode(',',$vals[0]);
            } elseif (sizeof($vals)){
                $values = $vals;
            } else{
                $values = [$vals];
            }
        } else{
            $values = [$vals];
        }
        return $values;
    }
}
