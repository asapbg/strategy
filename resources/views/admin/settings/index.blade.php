@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        @if(isset($sections) && sizeof($sections))
                            @foreach($sections as $s)
                                <li class="nav-item">
                                    <a class="nav-link @if($section == $s) active @endif" id="{{ $s }}-tab" href="{{ route('admin.settings', ['section' => $s]) }}">{{ __('custom.settings.sections.'.$s) }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="card-body">
                    @if(isset($settings) && $settings->count())
                        <form action="{{ route('admin.settings.store') }}" method="post">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="section" value="{{ $section }}">

                            @foreach($settings as $row)
                                @if($row->type == \App\Models\Setting::TYPE_SYNC)
                                    @includeIf('admin.settings.sync', ['show_button' => true])
                                @else
                                    <div class="form-group">
                                        <label class="control-label" for="active">{{ __('custom.settings.'.$row->name) }} @if($row->is_required) <span class="required">*</span> @endif</label>
                                        <div>
                                            @if($row->name == \App\Models\Setting::OGP_ADV_BOARD_FORUM)
                                                <select name="{{ $row->name }}" class="form-control form-control-sm select2 @error($row->name){{ 'is-invalid' }}@enderror">
                                                    <option value="0" @if((int)$row->value == 0) selected @endif>---</option>
                                                    @php($advBoards = \App\Models\AdvisoryBoard::with(['translations'])->orderByTranslation('name')->get())
                                                    @if($advBoards->count())
                                                        @foreach($advBoards as $adv)
                                                            <option value="{{ $adv->id }}" @if((int)$row->value == $adv->id) selected @endif>{{ $adv->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            @elseif($row->name == \App\Models\Setting::FACEBOOK_IS_ACTIVE)
                                                <div class="form-check">
                                                    <input type="radio" id="{{ $row->name.'1' }}" name="{{ $row->name }}" class="form-check-input" value="1" @if(old($row->name, ($row->value ?? 0)) == 1) checked @endif>
                                                    <label class="form-check-label" for="{{ $row->name.'1' }}">
                                                        Активна
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" id="{{ $row->name.'0' }}" name="{{ $row->name }}" class="form-check-input" value="0" @if(old($row->name, ($row->value ?? 0)) == 0) checked @endif>
                                                    <label class="form-check-label" for="{{ $row->name.'0' }}">
                                                        Неактивна
                                                    </label>
                                                </div>
                                            @else
                                                @switch($row->type)
                                                    @case('summernote')
                                                        <textarea name="{{ $row->name }}" class="form-control form-control-sm summernote @error($row->name){{ 'is-invalid' }}@enderror">{{ old($row->name, ($row->value)) }}</textarea>
                                                        @break
                                                    @default
                                                        <input name="{{ $row->name }}" value="{{ old($row->name, ($row->value)) }}" class="form-control form-control-sm @error($row->name){{ 'is-invalid' }}@enderror" type="{{ $row->type }}">
                                                @endswitch
                                            @endif
                                            @error($row->name)
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @switch($row->type)
                                @case(\App\Models\Setting::TYPE_SYNC)
                                    @break

                                @default
                                    <div class="form-group row">
                                        <div class="col-md-6 col-md-offset-3">
                                            <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                        </div>
                                    </div>
                            @endswitch
                        </form>
                        @if(isset($disabledSettings) && $disabledSettings->count())
                            @foreach($disabledSettings as $ds)
                                <div class="row mb-2 @if($loop->first) mt-5 @endif">
                                    <label class="col-md-4 control-label" for="active">{{ __('custom.settings.'.$ds->name) }}: </label>
                                    <div class="col-md-8 bg-light rounded border border-secondary" id="{{ $ds->name }}">
                                        {{ empty($ds->value) ? '---' : $ds->value }}
                                    </div>
                                    @if(!empty($ds->custom_value))
                                        <div class="col-12 main-color">{{ $ds->custom_value }}</div>
                                    @endif
                                </div>
                            @endforeach
                            @if($section == \App\Models\Setting::FACEBOOK_SECTION)
                                <div class="row mb-2">
                                    <div class="text-danger" id="facebook-err"></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="text-success" id="facebook-success"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-sm btn-success" id="refresh-facebook-tokens">Обнови креденшълите</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @else
                        <p>Не са открити записи</p>
                    @endif

                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('#refresh-facebook-tokens').on('click', function (){
                if(canAjax){
                    $('#facebook-err').html('');
                    $('#facebook-success').html('');
                    canAjax = false;
                    $.ajax({
                        type: 'GET',
                        url: @json(route('admin.settings.facebook.init')),
                        success: function (res) {

                            if(typeof res.error != 'undefined'){
                                $('#facebook-err').html(res.msg);
                            } else if(typeof res.tokens == 'undefined'){
                                $('#facebook-err').html('Неуспешен опит за инициализация.');
                            } else{
                                console.log(res);
                                $.each(res.tokens, function(keyName, keyValue) {
                                    $('#' + keyName).html(keyValue);
                                    console.log(keyName + ': ' + keyValue);
                                });
                                $('#facebook-success').html(res.msg);
                            }
                            canAjax = true;
                        },
                        error: function () {
                            $('#facebook-err').html('Неуспешен опит за комуникация');
                            canAjax = true;
                        }
                    });
                }
            });
        });
    </script>
@endpush

