<div class="row">
    <div class="card card-secondary p-0 my-2">
        <div class="card-header">
            {{ $loop->iteration }}. {{ trans_choice('custom.arrangement', 1) }}
            @can('update', $item)
            <a href="{{ route('admin.ogp.plan.arrangement.edit', ['id' => $item->id, 'ogpPlanArea' => $item->ogp_plan_area_id]) }}" class="btn btn-sm btn-info mr-1 float-end" title="Редакция">
                <i class="fas fa-edit"></i>
            </a>
            @endcan
        </div>
        <div class="card-body">
           @foreach(\App\Models\OgpPlanArrangement::TRANSLATABLE_FIELDS as $field)
                <div class="row mb-2">
                    @foreach (config('available_languages') as $locale)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example">{{ __('validation.attributes.'.$field.'_'.$locale['code']) }}</label>
                                <div class="form-text"> {!! $item->{ $field.':'.$locale['code']} !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
            <div class="row mb-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="example">{{ __('ogp.from_date') }}</label>
                        <div class="form-text">{{ $item->from_date }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="example">{{ __('ogp.to_date') }}</label>
                        <div class="form-text">{{ $item->to_date }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
