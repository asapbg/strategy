<?php

namespace App\Models;


use App\Enums\InstitutionCategoryLevelEnum;
use App\Filters\AdvisoryBoard\ChairmanTypes;
use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
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
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function getModelName()
    {
        if($this->subscribable_id){
            return $this->itemTitle();
        } else{
            return $this->itemSectionTitle();
        }
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
            switch ($this->subscribable_type){
                case 'App\Models\Consultations\PublicConsultation':
                        foreach ($jsonFilter as $key => $value){
                            if(!empty($value)){
                                $filterData = $this->getPcFilter($key, $value);
                                if(sizeof($filterData)){
                                    $filter[$key] = $filterData;
                                }
                            }
                        }
                    break;
                case 'App\Models\Pris':
                    foreach ($jsonFilter as $key => $value){
                        if(!empty($value)){
                            $filterData = $this->getPrisFilter($key, $value);
                            if(sizeof($filterData)){
                                $filter[$key] = $filterData;
                            }

                        }
                    }
                    break;
                case 'App\Models\LegislativeInitiative':
                    foreach ($jsonFilter as $key => $value){
                        if(!empty($value)){
                            $filterData = $this->getLiFilter($key, $value);
                            if(sizeof($filterData)){
                                $filter[$key] = $filterData;
                            }

                        }
                    }
                    break;
                case 'App\Models\StrategicDocument':
                    foreach ($jsonFilter as $key => $value){
                        if(!empty($value)){
                            $filterData = $this->getSdFilter($key, $value);
                            if(sizeof($filterData)){
                                $filter[$key] = $filterData;
                            }

                        }
                    }
                    break;
                case 'App\Models\AdvisoryBoard':
                    foreach ($jsonFilter as $key => $value){
                        if(!empty($value)){
                            $filterData = $this->getAdvBoardFilter($key, $value);
                            if(sizeof($filterData)){
                                $filter[$key] = $filterData;
                            }

                        }
                    }
                    break;
                case 'App\Models\Publication':
                    foreach ($jsonFilter as $key => $value){
                        if(!empty($value)){
                            $filterData = $this->getPublicationFilter($key, $value);
                            if(sizeof($filterData)){
                                $filter[$key] = $filterData;
                            }

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
                case 'App\Models\Pris':
                    $title = Pris::find($this->subscribable_id)->mcDisplayName;
                    break;
                case 'App\Models\Consultations\LegislativeProgram':
                    $title = LegislativeProgram::find($this->subscribable_id)->name;
                    break;
                case 'App\Models\Consultations\OperationalProgram':
                    $title = OperationalProgram::find($this->subscribable_id)->name;
                    break;
                case 'App\Models\LegislativeInitiative':
                    $title = LegislativeInitiative::find($this->subscribable_id)->facebookTitle;
                    break;
                case 'App\Models\OgpPlan':
                    $title = OgpPlan::find($this->subscribable_id)->name;
                    break;
                case 'App\Models\StrategicDocument':
                    $title = StrategicDocument::find($this->subscribable_id)->title;
                    break;
                case 'App\Models\AdvisoryBoard':
                    $title = AdvisoryBoard::find($this->subscribable_id)->name;
                    break;
                case 'App\Models\Publication':
                    $title = Publication::find($this->subscribable_id)->title;
                    break;
            }
        }
        return $title;
    }

    public function itemSectionTitle(): string
    {
        $title = '';
        switch ($this->subscribable_type){
            case 'App\Models\Consultations\PublicConsultation':
                $title = trans_choice('custom.public_consultations', 2);
                break;
            case 'App\Models\Pris':
                $title = __('custom.pris');
                break;
            case 'App\Models\Consultations\LegislativeProgram':
                $title = trans_choice('custom.legislative_program', 2);
                break;
            case 'App\Models\Consultations\OperationalProgram':
                $title = trans_choice('custom.operational_programs', 2);
                break;
            case 'App\Models\LegislativeInitiative':
                $title = __('custom.legislative_initiatives');
                break;
            case 'App\Models\OgpPlan':
                $title = trans_choice('custom.ogp_national_plans', 2);
                break;
            case 'App\Models\StrategicDocument':
                $title = trans_choice('custom.strategic_documents', 2);
                break;
            case 'App\Models\AdvisoryBoard':
                $title = trans_choice('custom.advisory_boards', 2);
                break;
            case 'App\Models\Publication':
                $title = trans_choice('custom.publications', 2);
                break;
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

    public static function filterToTextById($id): string
    {
        $item = self::find($id);
        return $item->filterToTxt();
    }

    private function getSdFilter(string $key, mixed $value): array
    {
        $filterTemplate = array(
            'key' => $key,
            'value' => $value,
            'viewLabel' => '',
            'viewValue' => ''
        );
        $filter = [];
        switch ($key){
            case 'level':
                if(!empty($value)) {
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = capitalize(__('custom.level_lower_case'));
                    $labels = [];
                    foreach ($values as $v){
                        $labels[] = InstitutionCategoryLevelEnum::keyToLabel()[$v];
                    }
                    $filter['viewValue'] = implode(', ', $labels);
                }
                break;
            case 'areas':
            case 'municipalities':
            case 'fieldOfActions':
                if(!empty($value)) {
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = trans_choice('custom.field_of_actions', 1);
                    $labels = FieldOfAction::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'status':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('site.strategic_document.categories_based_on_livecycle');
                    $filter['viewValue'] = !empty($value) ? ($value == 'active' ? __('custom.effective') : ($value == 'expired' ? __('custom.expired') : __('custom.in_process_of_consultation'))) : __('custom.any');
                }
                break;
            case 'title':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.title');
                    $filter['viewValue'] = $value;
                }
                break;
        }
        return $filter;
    }
    private function getAdvBoardFilter(string $key, mixed $value): array
    {
        $filterTemplate = array(
            'key' => $key,
            'value' => $value,
            'viewLabel' => '',
            'viewValue' => ''
        );
        $filter = [];
        switch ($key){
            case 'keywords':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.name');
                    $filter['viewValue'] = $value;
                }
                break;

            case 'fieldOfActions':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = trans_choice('custom.field_of_actions', 1);
                    $labels = FieldOfAction::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'authoritys':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = __('custom.type_of_governing');
                    $labels = AuthorityAdvisoryBoard::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'actOfCreations':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = __('validation.attributes.advisory_act_type_id');
                    $labels = AdvisoryActType::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'chairmanTypes':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = trans_choice('custom.advisory_chairman_type',1);
                    $labels = ChairmanTypes::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'npo':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.presence_npo_representative');
                    $filter['viewValue'] = !empty($value) ? ((int)$value ? __('custom.yes') : __('custom.no')) : __('custom.any');
                }
                break;
            case 'personName':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.adv_board_search_person');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'status':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.presence_npo_representative');
                    $filter['viewValue'] = $value != '-1' ? ((int)$value ? __('custom.active') : __('custom.inactive')) : __('custom.any');
                }
                break;
        }
        return $filter;
    }

    private function getPrisFilter(string $key, mixed $value): array
    {
        $filterTemplate = array(
            'key' => $key,
            'value' => $value,
            'viewLabel' => '',
            'viewValue' => ''
        );
        $filter = [];
        switch ($key){
            case 'legalActTypes':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = trans_choice('custom.legal_act_type', 1);
                    $labels = LegalActType::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'fullSearch':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.files').'/'.__('custom.pris_about').'/'.__('custom.pris_legal_reason').'/'.trans_choice('custom.tags', 2);
                    $filter['viewValue'] = $value;
                }
                break;
            case 'docNum':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.document_number');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'year':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.year');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'docDate':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.date');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'institutions':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = trans_choice('custom.institutions', 1);
                    $labels = Institution::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'fromDate':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.begin_date');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'toDate':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.end_date');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'newspaperNumber':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.newspaper_number');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'newspaperYear':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.newspaper_year');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'changes':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.change_docs');
                    $filter['viewValue'] = $value;
                }
                break;
        }
        return $filter;
    }

    private function getLiFilter(string $key, mixed $value): array
    {
        $filterTemplate = array(
            'key' => $key,
            'value' => $value,
            'viewLabel' => '',
            'viewValue' => ''
        );
        $filter = [];
        switch ($key){
            case 'keywords':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.content_author');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'institution':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = trans_choice('custom.institutions', 1);
                    $labels = Institution::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'law':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = trans_choice('custom.laws', 1);
                    $labels = Law::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
        }
        return $filter;
    }

    private function getPublicationFilter(string $key, mixed $value): array
    {
        $filterTemplate = array(
            'key' => $key,
            'value' => $value,
            'viewLabel' => '',
            'viewValue' => ''
        );

        $filter = [];

        switch ($key){
            case 'categories':
                if(!empty($value)){
                    $values = $this->getArrayValues($value);
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = trans_choice('custom.categories',1);
                    $labels = PublicationCategory::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'keywords':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.title_content');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'published_from':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.published_after_f');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'published_till':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.published_before_f');
                    $filter['viewValue'] = $value;
                }
                break;
        }
        return $filter;
    }

    private function getPcFilter(string $key, mixed $value): array
    {
        $filterTemplate = array(
            'key' => $key,
            'value' => $value,
            'viewLabel' => '',
            'viewValue' => ''
        );
        $filter = [];
        switch ($key){
            case 'name':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.name');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'openFrom':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.begin_date');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'openTo':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.end_date');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'consultationNumber':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $filter['viewLabel'] = __('custom.consultation_number');
                    $filter['viewValue'] = $value;
                }
                break;
            case 'actTypes':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $filter['viewLabel'] = __('custom.act_type');
                    $labels = ActType::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'importers':
                if(!empty($value)){
                    $filter = $filterTemplate;
                    $values = $this->getArrayValues($value);
                    $labels = Institution::with(['translations'])->whereIn('id', $values)->get()->pluck('name')->toArray();
                    $filter['viewValue'] = sizeof($labels) ? implode(', ', $labels) : '';
                }
                break;
            case 'areas':
            case 'municipalities':
            case 'fieldOfActions':
                if(!empty($value)) {
                    $filter = $filterTemplate;
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
                    $filter = $filterTemplate;
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
