{{--<div class="row">--}}
<div class="card card-primary card-outline">
{{--    <div class="card card-primary p-0 my-2">--}}
{{--        <div class="card-header">--}}
            <div class="card-header" id="heading{{ $iteration }}">
                <h2 class="mb-0 d-flex flex-row justify-content-between">
                    <button class="btn btn-link btn-block text-left fw-bold @if($iteration == 1) collapsed @endif" type="button" data-toggle="collapse" data-target="#collapse{{ $iteration }}" aria-expanded="@if($iteration == 1){{ 'true' }}@else{{ 'false' }}@endif" aria-controls="collapse{{ $iteration }}">
                        {{ $iteration }}. {{ trans_choice('custom.arrangement', 1) }}
                    </button>
                    @can('update', $item)
                    <a href="{{ route('admin.ogp.plan.arrangement.edit', ['id' => $item->id, 'ogpPlanArea' => $item->ogp_plan_area_id]) }}" class="btn btn-sm btn-info mr-1 float-end" title="Редакция">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endcan
                </h2>
            </div>
{{--        </div>--}}
        <div id="collapse{{ $iteration }}" class="collapse @if($iteration == 1) show @endif" aria-labelledby="heading{{ $iteration }}" data-parent="#accordionExample">
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
                            <div class="form-text">{{ $item->from_date ? displayDate($item->from_date) : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="example">{{ __('ogp.to_date') }}</label>
                            <div class="form-text">{{ $item->to_date ? displayDate($item->to_date) : '' }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
{{--    </div>--}}
</div>
{{--</div>--}}
