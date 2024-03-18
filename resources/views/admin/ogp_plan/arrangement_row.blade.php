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
                        @if($evaluationEdit)
                            <a href="{{ route('admin.ogp.plan.arrangement.edit.evaluation', ['id' => $item->id, 'ogpPlanArea' => $item->ogp_plan_area_id]) }}" class="btn btn-sm btn-info mr-1 float-end" title="Редакция">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                        @if(!$disableEdit)
                                <a href="{{ route('admin.ogp.plan.arrangement.edit', ['id' => $item->id, 'ogpPlanArea' => $item->ogp_plan_area_id]) }}" class="btn btn-sm btn-info mr-1 float-end" title="Редакция">
                                    <i class="fas fa-edit"></i>
                                </a>
                        @endif
                    @endcan
                    @can('deleteArrangement', $item->ogpPlanArea->plan)
                        <a href="javascript:;"
                           class="btn btn-sm btn-danger js-toggle-delete-resource-modal hidden"
                           data-target="#arrangement_delete_modal"
                           data-resource-id="{{ $item->id }}"
                           data-resource-name="{{ $item->name }}"
                           data-resource-delete-url="{{ route('admin.ogp.plan.arrangement.delete', $item) }}"
                           data-toggle="tooltip"
                           title="{{__('custom.deletion')}}">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endcan
                </h2>
            </div>
{{--        </div>--}}
        <div id="collapse{{ $iteration }}" class="collapse @if($iteration == 1) show @endif" aria-labelledby="heading{{ $iteration }}" data-parent="#accordionExample">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-12">
                        <h4 class="custom-left-border">Описание на мярката:</h4>
                    </div>
                    @php($baseInfoFields = ['name', 'responsible_administration', 'problem', 'content', 'solving_problem', 'values_initiative', 'extra_info', 'npo_partner', 'interested_org', 'contact_names'])
                    @foreach($baseInfoFields as $field)
                        @foreach (config('available_languages') as $locale)
                            <div class="col-md-6">
                                <div class="form-group">
                                    @php($label = $field == 'content' ? 'ogp_arrangement_content_'.$locale['code'] : $field.'_'.$locale['code'])
                                    <label for="example">{{ __('validation.attributes.'.$label) }}</label>
                                    <div class="form-text"> {!! $item->{ $field.':'.$locale['code']} !!}</div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
{{--                <div class="row mb-2">--}}
{{--                    <div class="col-12">--}}
{{--                        <h4 class="custom-left-border">Контактна информация:</h4>--}}
{{--                    </div>--}}
{{--                    @php($contactInfoFields = ['contact_names', 'contact_positions', 'problem', 'contact_phone_email'])--}}
{{--                    @foreach($contactInfoFields as $field)--}}
{{--                        @foreach (config('available_languages') as $locale)--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    @php($label = $field == 'content' ? 'ogp_arrangement_content_'.$locale['code'] : $field.'_'.$locale['code'])--}}
{{--                                    <label for="example">{{ __('validation.attributes.'.$label) }}</label>--}}
{{--                                    <div class="form-text"> {!! $item->{ $field.':'.$locale['code']} !!}</div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    @endforeach--}}
{{--                </div>--}}

                @if($evaluationEdit)
                    <div class="row mb-2">
                        <div class="col-12">
                            <h4 class="custom-left-border">Оценка за изпълнението на плановете за действие - мониторинг:</h4>
                        </div>
                        @php($evalInfoFields = ['evaluation', 'evaluation_status'])
                        @foreach($evalInfoFields as $field)
                            @foreach (config('available_languages') as $locale)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @php($label = $field == 'content' ? 'ogp_arrangement_content_'.$locale['code'] : $field.'_'.$locale['code'])
                                        <label for="example">{{ __('validation.attributes.'.$label) }}</label>
                                        <div class="form-text"> {!! $item->{ $field.':'.$locale['code']} !!}</div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @endif
                <div class="row mb-2">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="custom-left-border">Начална и крайна дата за изпълнение на мярката:</h4>
                        </div>
                    </div>
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
                   @if($item->actions->count())
                       <table class="table">
                           <thead>
                                <tr>
                                    <th colspan="4">Дейности за и измерими резултати от изпълнението на мярката</th>
                                </tr>
                                <tr>
                                    <th>{{ __('validation.attributes.new_name_bg') }}</th>
                                    <th>{{ __('validation.attributes.new_name_en') }}</th>
                                    <th>{{ __('validation.attributes.new_from_date') }}</th>
                                    <th>{{ __('validation.attributes.new_to_date') }}</th>
                                </tr>
                           </thead>
                           <tbody>
                           @foreach($item->actions as $k => $action)
                               <tr id="action-{{ $action->id }}">
                                   <td>{{ $action->translate('bg')->name }}</td>
                                   <td>{{ $action->translate('en')->name }}</td>
                                   <td>{{ $action->from_date }}</td>
                                   <td>{{ $action->to_date }}</td>
                               </tr>
                           @endforeach
                           </tbody>
                       </table>
                   @endif
            </div>
        </div>
{{--    </div>--}}
</div>
{{--</div>--}}

