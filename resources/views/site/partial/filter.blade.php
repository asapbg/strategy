@if(isset($filter) && count($filter))
{{--    <div class="card @if(isset($filterClass)){{ $filterClass }}@endif">--}}
<form method="GET" class="@if(isset($class)){{ $class }}@endif">
    @if(count($filter) > 1 || !isset($filter['paginate']))
        <div class="row filter-results mb-2">
        <h2 class="mb-4">
            {{ __('custom.search') }}
        </h2>

        @foreach($filter as $key => $field)
            @continue($key == 'paginate')
            @if(!str_contains('formGroup', $key))
                <div class="{{ $field['col'] ?? 'col-md-6' }} col-12 mb-2  @if($field['type'] == 'subjects') d-flex @endif">
                    @switch($field['type'])
                        @case('text')
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="{{ $key }}" class="form-label">{{ $field['label'] }}:</label>
                                <input type="text" id="{{ $key }}" class="form-control" autocomplete="off"
                                    value="{{ $field['value'] }}" name="{{ $key }}">
                            </div>
                        </div>
                        @break('text')
                        @case('datepicker')
                        <label for="{{ $key }}" class="form-label">{{ $field['label'] }}:</label>
                        <div class="input-group">
                            <input type="text" name="{{ $key }}" autocomplete="off"
                                value="{{ old($key, $field['value']) }}" class="form-control datepicker-btn">
                            <span class="input-group-text datepicker-addon" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                        </div>
                        @break('datepicker')
                        @case('checkbox')
                        {{--                                Still not edited for public pages--}}
                        <label>
                            <input type="checkbox" name="{{ $key }}" @if($field['checked']) checked @endif
                                value="{{ $field['value'] }}">
                            {{ $field['label'] }}
                        </label>
                        @break('checkbox')
                        @case('select')
                            @if(isset($field['multiple']) && $field['multiple'] && !empty($field['value']) && is_array($field['value']) && str_contains($field['value'][0], ','))
                                @php($field['value'] = explode(',', $field['value'][0]))
                            @endif
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="exampleFormControlInput1" class="form-label">{{ $field['label'] }}:</label>
                                    <select class="form-select select2 @if(isset($field['class'])){{$field['class'] }}@endif"
                                        name="{{ $key.(isset($field['multiple']) && $field['multiple'] ? '[]' : '') }}" id="{{ $key }}"
                                        @if(isset($field['multiple']) && $field['multiple']) multiple="multiple" @endif>
                                            {{-- select with groups--}}
                                            @if(isset($field['group']) && $field['group'])
                                            @foreach($field['options'] as $group_name => $group)
                                                @php($optionDataAttributes = '')
                                                @foreach($group as $k => $v)
                                                    @if(str_contains($k, 'data-'))
                                                        @php($optionDataAttributes .= $v.' ')
                                                    @endif
                                                @endforeach

                                                @if(isset($group['any']))
                                                    <option value="{{ $group['value'] }}" @if($group['value'] == old($key, $field['value'])) selected
                                                        @elseif(is_null(old($key, $field['value'])) && isset($field['default']) &&
                                                        $group['value'] == $field['default']) selected @endif {{ $optionDataAttributes }}>{{ $group['name'] }}</option>
                                                @else
                                                    <optgroup label="{{ $group_name }}">
                                                        @if(sizeof($group) > 0)
                                                            @foreach($group as $option)
                                                            <option value="{{ $option['value'] }}" @if((isset($field['multiple']) && $field['multiple']
                                                                && in_array($option['value'], old($key.'[]', $field['value'] ?? []))) ||
                                                                ((!isset($field['multiple']) || !$field['multiple']) && $option['value']==old($key,
                                                                $field['value']))) selected @endif>{{ $option['name'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        @else
                                            {{-- regular select --}}
                                            @foreach($field['options'] as $option)
                                                @php($optionDataAttributes = '')
                                                @foreach($option as $k => $v)
                                                    @if(str_contains($k, 'data-'))
                                                        @php($optionDataAttributes .= $v.' ')
                                                    @endif
                                                @endforeach
                                            <option {{ $optionDataAttributes }} value="{{ $option['value'] }}" @if((isset($field['multiple']) && $field['multiple'] &&
                                                in_array($option['value'], old($key.'[]', $field['value'] ?? [])) ) ||
                                                ((!isset($field['multiple']) || !$field['multiple']) && $option['value']== old($key, ($field['value'] != '' && !is_null($field['value']) ? $field['value'] : ($field['default']))))) selected @endif>{{ $option['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @break('select')
                        @case('subjects')

                            <div class="input-group input-group-sm d-flex">
                                <label for="exampleFormControlInput1" class="form-label">{{ $field['label'] }}:<button type="button" class="btn btn-sm btn-primary ms-1 pick-institution" style="height: 30px;"
                                    data-title="{{ trans_choice('custom.institutions',2) }}"
                                    data-url="{{ route('modal.institutions').'?select=1&multiple='.(isset($field['multiple']) && $field['multiple'] ? '1' : '0').'&admin=0&dom='.$key }}">
                                    <i class="fa fa-list"></i>
                                </button></label>
                                <div class="mb-3 d-flex flex-row  w-100">
                                    <select class="form-control select2 @if(isset($field['class'])){{$field['class'] }}@endif"
                                        @if(isset($field['multiple']) && $field['multiple']) multiple="multiple" @endif
                                        name="{{ $key.(isset($field['multiple']) && $field['multiple'] ? '[]' : '') }}" id="{{ $key }}">
                                        @foreach($field['options'] as $option)
                                        <option value="{{ $option['value'] }}" @if((isset($field['multiple']) && $field['multiple'] &&
                                            in_array($option['value'], old($key.'[]', $field['value'] ?? []))) ||
                                            ((!isset($field['multiple']) || !$field['multiple']) && $option['value']==old($key,
                                            $field['value']))) selected @endif>{{ $option['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @break('subjects')
                    @endswitch
                </div>
            @else
                <div class="col-12 {{ $field['class'] ?? '' }}">
                    @if(isset($field['title']))
                        <label class="form-label ms-3 me-3 custom-left-border">{{ $field['title'] }}</label>
                    @endif
                    @foreach($field['fields'] as $groupKey => $groupField)
                        <div class="{{ $groupField['col'] ?? 'col-md-6' }} col-12 mb-2  @if($groupField['type'] == 'subjects') d-flex @endif">
                            @switch($groupField['type'])
                                @case('text')
                                    <div class="input-group ">
                                        <div class="mb-3 d-flex flex-column  w-100">
                                            <label for="{{ $groupKey }}" class="form-label">{{ $groupField['label'] }}:</label>
                                            <input type="text" id="{{ $groupKey }}" class="form-control" autocomplete="off"
                                                   value="{{ $groupField['value'] }}" name="{{ $groupKey }}">
                                        </div>
                                    </div>
                                    @break('text')
                                @case('datepicker')
                                    <label for="{{ $groupKey }}" class="form-label">{{ $groupField['label'] }}:</label>
                                    <div class="input-group">
                                        <input type="text" name="{{ $groupKey }}" autocomplete="off"
                                               value="{{ old($groupKey, $groupField['value']) }}" class="form-control datepicker-btn">
                                        <span class="input-group-text datepicker-addon" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                                    </div>
                                    @break('datepicker')
                                @case('checkbox')
                                    {{--                                Still not edited for public pages--}}
                                    <label>
                                        <input type="checkbox" name="{{ $groupKey }}" @if($groupField['checked']) checked @endif
                                        value="{{ $groupField['value'] }}">
                                        {{ $groupField['label'] }}
                                    </label>
                                    @break('checkbox')
                                @case('select')
                                    @if(isset($groupField['multiple']) && $groupField['multiple'] && !empty($groupField['value']) && is_array($groupField['value']) && str_contains($groupField['value'][0], ','))
                                        @php($groupField['value'] = explode(',', $groupField['value'][0]))
                                    @endif
                                    <div class="input-group ">
                                        <div class="mb-3 d-flex flex-column  w-100">
                                            <label for="exampleFormControlInput1" class="form-label">{{ $groupField['label'] }}:</label>
                                            <select class="form-select select2 @if(isset($groupField['class'])){{$groupField['class'] }}@endif"
                                                    name="{{ $groupKey.(isset($groupField['multiple']) && $groupField['multiple'] ? '[]' : '') }}" id="{{ $groupKey }}"
                                                    @if(isset($groupField['multiple']) && $groupField['multiple']) multiple="multiple" @endif>
                                                {{-- select with groups--}}
                                                @if(isset($groupField['group']) && $groupField['group'])
                                                    @foreach($groupField['options'] as $group_name => $group)
                                                        @php($optionDataAttributes = '')
                                                        @foreach($group as $k => $v)
                                                            @if(str_contains($k, 'data-'))
                                                                @php($optionDataAttributes .= $v.' ')
                                                            @endif
                                                        @endforeach

                                                        @if(isset($group['any']))
                                                            <option value="{{ $group['value'] }}" @if($group['value'] == old($groupKey, $groupField['value'])) selected
                                                                    @elseif(is_null(old($groupKey, $groupField['value'])) && isset($groupField['default']) &&
                                                                    $group['value'] == $groupField['default']) selected @endif {{ $optionDataAttributes }}>{{ $group['name'] }}</option>
                                                        @else
                                                            <optgroup label="{{ $group_name }}">
                                                                @if(sizeof($group) > 0)
                                                                    @foreach($group as $option)
                                                                        <option value="{{ $option['value'] }}" @if((isset($groupField['multiple']) && $groupField['multiple']
                                                            && in_array($option['value'], old($groupKey.'[]', $groupField['value'] ?? []))) ||
                                                            ((!isset($groupField['multiple']) || !$groupField['multiple']) && $option['value']==old($groupKey,
                                                            $groupField['value']))) selected @endif>{{ $option['name'] }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{-- regular select --}}
                                                    @foreach($groupField['options'] as $option)
                                                        @php($optionDataAttributes = '')
                                                        @foreach($option as $k => $v)
                                                            @if(str_contains($k, 'data-'))
                                                                @php($optionDataAttributes .= $v.' ')
                                                            @endif
                                                        @endforeach
                                                        <option {{ $optionDataAttributes }} value="{{ $option['value'] }}" @if((isset($groupField['multiple']) && $groupField['multiple'] &&
                                            in_array($option['value'], old($groupKey.'[]', $groupField['value'] ?? [])) ) ||
                                            ((!isset($groupField['multiple']) || !$groupField['multiple']) && $option['value']== old($groupKey, ($groupField['value'] != '' && !is_null($groupField['value']) ? $groupField['value'] : ($groupField['default']))))) selected @endif>{{ $option['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    @break('select')
                                @case('subjects')

                                    <div class="input-group input-group-sm d-flex">
                                        <label for="exampleFormControlInput1" class="form-label">{{ $groupField['label'] }}:<button type="button" class="btn btn-sm btn-primary ms-1 pick-institution" style="height: 30px;"
                                                                                                                               data-title="{{ trans_choice('custom.institutions',2) }}"
                                                                                                                               data-url="{{ route('modal.institutions').'?select=1&multiple='.(isset($groupField['multiple']) && $groupField['multiple'] ? '1' : '0').'&admin=0&dom='.$groupKey }}">
                                                <i class="fa fa-list"></i>
                                            </button></label>
                                        <div class="mb-3 d-flex flex-row  w-100">
                                            <select class="form-control select2 @if(isset($groupField['class'])){{$groupField['class'] }}@endif"
                                                    @if(isset($groupField['multiple']) && $groupField['multiple']) multiple="multiple" @endif
                                                    name="{{ $groupKey.(isset($groupField['multiple']) && $groupField['multiple'] ? '[]' : '') }}" id="{{ $groupKey }}">
                                                @foreach($groupField['options'] as $option)
                                                    <option value="{{ $option['value'] }}" @if((isset($groupField['multiple']) && $groupField['multiple'] &&
                                        in_array($option['value'], old($groupKey.'[]', $groupField['value'] ?? []))) ||
                                        ((!isset($groupField['multiple']) || !$groupField['multiple']) && $option['value']==old($groupKey,
                                        $groupField['value']))) selected @endif>{{ $option['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @break('subjects')
                            @endswitch
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach

        {{--                <div class="col-md-3">--}}
        {{--                    <div class="input-group ">--}}
        {{--                        <div class="mb-3 d-flex flex-column  w-100">--}}
        {{--                            <label for="exampleFormControlInput1" class="form-label">{{ __('custom.filter_pagination') }}:</label>--}}
        {{--                            <select class="form-select" name="pagination">--}}
        {{--                                <option value="1">10</option>--}}
        {{--                                <option value="20">20</option>--}}
        {{--                                <option value="30">30</option>--}}
        {{--                                <option value="40">40</option>--}}
        {{--                                <option value="50">50</option>--}}
        {{--                            </select>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
    </div>
    @endif
    <div class="row mb-5 action-btn-wrapper">
        <div class="col-md-6">
            @if(count($filter) > 1 || !isset($filter['paginate']))
                @php($fRequest = $rf ?? request()->all())
                <button type="@if(isset($ajax) && $ajax){{ 'button' }}@else{{ 'submit' }}@endif"
                    class="btn rss-sub main-color @if(isset($ajax) && $ajax) ajaxSearch @endif" @if(isset($ajax) && $ajax)
                    data-params="{{ json_encode($fRequest) }}" data-url="{{ url()->current() }}" @if(isset($ajaxContainer))
                    data-container="{{ $ajaxContainer }}" @endif @endif>
                    <i class="fas fa-search main-color"></i>{{ __('custom.search') }}
                </button>
            @endif
            @if(isset($export_excel) && $export_excel)
                    <button type="submit" class="btn btn-success" name="export_excel" value="1">
                        <i class="fas fa-file-excel text-success me-2"></i>{{ __('custom.export') }}
                    </button>
            @endif
            @if(isset($export_pdf) && $export_pdf)
                <button type="submit" class="btn btn-success" name="export_pdf" value="1">
                    <i class="fas fa-file-pdf text-danger me-2"></i>{{ __('custom.export') }}
                </button>
            @endif
        </div>

        <div class="col-md-6 text-end">
            @if(!isset($subscribe) || $subscribe)
{{--                @includeIf('site.partial.subscribe-buttons', ['subscribe_params' => $requestFilter ?? [], 'hasSubscribeEmail' => $hasSubscribeEmail ?? false, 'hasSubscribeRss' => $hasSubscribeRss ?? false])--}}
                @includeIf('site.partial.subscribe-buttons', ['subscribe_params' => $requestFilter ?? [], 'hasSubscribeEmail' => $hasSubscribeEmail ?? false, 'hasSubscribeRss' => $hasSubscribeRss ?? false])
            @endif
            @if(isset($btn_add) && $btn_add)
            <a class="btn btn-success text-success"
                href="@if(isset($add_url) && $add_url){{ $add_url }}@else{{ '#' }}@endif" target="_blank"><i
                    class="fas fa-circle-plus text-success me-1"></i>{{ __('custom.adding') }}</a>
            @endif
        </div>
    </div>
</form>
{{--    </div>--}}
@endif
