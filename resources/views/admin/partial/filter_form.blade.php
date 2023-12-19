@if(isset($filter) && count($filter))
    <div class="card @if(isset($filterClass)){{ $filterClass }}@endif">
        <form method="GET">
            <div class="card-body">
                <div class="row">
                    @foreach($filter as $key => $field)
                        <div class="{{ $field['col'] ?? 'col-md-6' }} col-12 mb-2  @if($field['type'] == 'subjects') d-flex @endif">
                            @switch($field['type'])
                                @case('text')
                                <input type="text" name="{{ $key }}" autocomplete="off"
                                       class="form-control form-control-sm"
                                       placeholder="{{ $field['placeholder'] }}"
                                       value="{{ old($key, $field['value']) }}" >
                                @break('text')
                                @case('datepicker')
                                <input type="text" name="{{ $key }}" autocomplete="off"
                                       data-provide="datepicker"
                                       class="form-control form-control-sm datepicker"
                                       placeholder="{{ $field['placeholder'] }}"
                                       value="{{ old($key, $field['value']) }}" >
                                @break('datepicker')
                                @case('checkbox')
                                    <label>
                                        <input type="checkbox" name="{{ $key }}" @if($field['checked']) checked @endif
                                        value="{{ $field['value'] }}" >
                                        {{ $field['label'] }}
                                    </label>

                                @break('checkbox')
                                @case('select')
                                    <select class="form-control form-control-sm select2 @if(isset($field['class'])){{$field['class'] }}@endif"
                                            @if(isset($field['placeholder'])) data-placeholder="{{ $field['placeholder'] }}" @endif name="{{ $key.(isset($field['multiple']) && $field['multiple'] ? '[]' : '') }}"
                                    @if(isset($field['multiple']) && $field['multiple']) multiple="multiple" @endif>
                                        {{-- select with groups--}}
                                        @if(isset($field['group']) && $field['group'])
                                            @foreach($field['options'] as $group_name => $group)
                                                @if(isset($group['any']))
                                                    <option value="{{ $group['value'] }}" @if($group['value'] == old($key, $field['value'])) selected @elseif(is_null(old($key, $field['value'])) && isset($field['default']) && $group['value'] == $field['default']) selected @endif>{{ $group['name'] }}</option>
                                                @else
                                                    <optgroup label="{{ $group_name }}">
                                                        @if(sizeof($group) > 0)
                                                            @foreach($group as $option)
                                                                <option value="{{ $option['value'] }}" @if((isset($field['multiple']) && $field['multiple'] && in_array($option['value'], old($key.'[]', $field['value'] ?? []))) || ((!isset($field['multiple']) || !$field['multiple']) && $option['value'] == old($key, $field['value']))) selected @endif>{{ $option['name'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        @else
                                            {{-- regular select --}}
                                            @foreach($field['options'] as $key => $option)
                                                @php
                                                    $value = $option['value'] ?? $key;
                                                    $name = $option['name'] ?? $option;
                                                @endphp
                                                <option value="{{ $value }}"
                                                        @if(
                                                              (isset($field['multiple']) && $field['multiple']
                                                              && in_array($value, old($key.'[]', $field['value'] ?? [])))
                                                              || ((!isset($field['multiple'])
                                                              || !$field['multiple']) && $value == old($key, $field['value']))
                                                        )
                                                            selected
                                                    @endif
                                                >{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                @break('select')
                                @case('subjects')
{{--                                <div class="input-group input-group-sm d-flex">--}}
                                    <select class="form-control form-control-sm select2 @if(isset($field['class'])){{$field['class'] }}@endif"
                                            @if(isset($field['multiple']) && $field['multiple']) multiple="multiple" @endif name="{{ $key.(isset($field['multiple']) && $field['multiple'] ? '[]' : '') }}" id="{{ $key }}"
                                            data-placeholder="{{ $field['placeholder'] }}">
                                        @foreach($field['options'] as $key => $option)
                                            @php
                                                $value = $option['value'] ?? $key;
                                                $name = $option['name'] ?? $option;
                                            @endphp
                                            <option value="{{ $value }}"
                                                    @if(
                                                      (isset($field['multiple']) && $field['multiple'] && in_array($value, old($key.'[]', $field['value'] ?? [])))
                                                      || ((!isset($field['multiple'])
                                                      || !$field['multiple']) && $value == old($key, $field['value']))
                                                    )
                                                        selected
                                                @endif
                                            >{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-sm btn-primary ms-1 pick-institution"
                                            data-title="{{ trans_choice('custom.institutions',2) }}"
                                            data-url="{{ route('modal.institutions').'?select=1&multiple='.(isset($field['multiple']) && $field['multiple'] ? '1' : '0').'&admin=1&dom='.$key }}">
                                        <i class="fa fa-list"></i>
                                    </button>
{{--                                </div>--}}
                                @break('subjects')
                            @endswitch
                        </div>
                    @endforeach
                    <div class="col-xs-12 col-md-3 col-sm-4 mb-2">
                        <button type="submit" name="search" value="1" class="btn btn-sm btn-success">
                            <i class="fa fa-search"></i> {{ __('custom.search') }}
                        </button>
                        @if(isset($listRouteName))
                            @if(isset($type))
                                <a href="{{ route($listRouteName, ['type' => $type]) }}" class="btn btn-sm btn-default">
                                    <i class="fas fa-eraser"></i> {{ __('custom.clear') }}
                                </a>
                            @else
                                <a href="{{ route($listRouteName) }}" class="btn btn-sm btn-default">
                                    <i class="fas fa-eraser"></i> {{ __('custom.clear') }}
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </form>
    </div>
@endif
