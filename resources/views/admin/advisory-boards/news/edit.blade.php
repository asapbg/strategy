@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="ct-general-tab" data-toggle="pill" href="#ct-general" role="tab" aria-controls="ct-general" aria-selected="true">
                                {{ __('custom.general_info') }}
                            </a>
                        </li>
                        @if($item->id)
                            <li class="nav-item">
                                <a class="nav-link" id="ct-files-tab" data-toggle="pill" href="#ct-files" role="tab" aria-controls="ct-files" aria-selected="false">
                                    {{ trans_choice('custom.files',2) }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                @php
                    $storeRoute = route($storeRouteName, ['item' => $item]);
                @endphp
                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">
                        <form action="{{ $storeRoute }}" method="post" name="form" id="form" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-pane fade active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                                @if($item->id)
                                    @method('PUT')
                                    <input type="hidden" name="type" value="{{ $item->type ?? 0 }}">
                                @endif
                                <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label class="control-label" for="type">
                                                    {{ __('validation.attributes.type') }}:
                                                    @if($item->id)
                                                        <span>
                                                            {{ trans_choice('custom.public_sections.types.'.\App\Enums\PublicationTypesEnum::keyByValue($item->type), 1) }}
                                                        </span>
                                                    @endif
                                                </label>
                                                <div class="d-inline">
                                                    <select id="type" name="type" onchange="changePublicationType(this)" class="form-control select2 form-control-sm @error('type'){{ 'is-invalid' }}@enderror">
                                                        @foreach(optionsPublicationTypes() as $row)
                                                            @if($row['value'] == \App\Enums\PublicationTypesEnum::TYPE_ADVISORY_BOARD->value)
                                                                <option value="{{ $row['value'] }}" selected>{{ $row['name'] }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @error('type')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12" id="adv_board_section">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label class="control-label" for="adv_board">
                                                    {{ trans_choice('custom.advisory_boards', 2) }}
                                                </label>
                                                <div class="d-inline">
                                                    @php
                                                        $oldAdvBoard = old('adv_board', $item->id ? $item->advisory_boards_id : 0) ? \App\Models\AdvisoryBoard::with(['translation'])->where('id', old('adv_board', $item->id ? $item->advisory_boards_id : 0))->first() : null
                                                    @endphp
                                                    <select id="adv_board" name="adv_board"
                                                            class="form-control form-control-sm select2-autocomplete-ajax @error('adv_board'){{ 'is-invalid' }}@enderror"
                                                            data-types2ajax="adv_board" data-urls2="{{ route('admin.select2.ajax', 'adv_board') }}"
                                                    >
                                                            @if($oldAdvBoard)
                                                                <option value="{{ $oldAdvBoard->id }}" selected >{{ $oldAdvBoard->name }}</option>
                                                            @endif
                                                        </select>
                                                        @error('adv_board')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 d-none">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="slug">
                                                {{ __('validation.attributes.slug') }}
                                            </label>
                                            <div class="col-12">
                                                <input name="slug" value="{{ old('slug', $item->id ? $item->slug : '') }}" class="form-control form-control-sm @error('slug'){{ 'is-invalid' }}@enderror">
                                                @error('slug')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12"></div>

                                    <div class="row mb-4">
                                        @include('admin.partial.edit_field_translate', ['field' => 'title', 'required' => true])
                                    </div>
                                    <div class="row mb-4">
                                        @include('admin.partial.edit_field_translate', ['field' => 'short_content', 'required' => false])
                                    </div>
                                    <div class="row mb-4">
                                        @include('admin.partial.edit_field_translate', ['field' => 'content', 'required' => true])
                                    </div>
                                    <div class="col-12 mb-md-3"></div>
                                    <div class="row mb-4">
                                        @include('admin.partial.edit_field_translate', ['field' => 'meta_title', 'required' => false])
                                    </div>
                                    <div class="row mb-4">
                                        @include('admin.partial.edit_field_translate', ['field' => 'meta_description', 'required' => false])
                                    </div>
                                    <div class="row mb-4">
                                        @include('admin.partial.edit_field_translate', ['field' => 'meta_description', 'required' => false])
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="published_at">
                                                {{ __('validation.attributes.published_at') }}
                                            </label>
                                            <div class="col-12">
                                                <input type="text" name="published_at" value="{{ old('published_at', $item->id ? displayDate($item->published_at) : '') }}" class="datepicker form-control form-control-sm @error('published_at'){{ 'is-invalid' }}@enderror">
                                                @error('published_at')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="active">
                                                {{ __('validation.attributes.status') }}
                                            </label>
                                            <div class="col-12">
                                                <select id="active" name="active"  class="form-control form-control-sm @error('active'){{ 'is-invalid' }}@enderror">
                                                    @foreach(optionsStatuses() as $val => $name)
                                                        <option value="{{ $val }}" @if(old('active', ($item->id ? $item->active : 1)) == $val) selected @endif>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('active')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12"></div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="active">
                                                {{ __('validation.attributes.main_img') }} <span class="required">*</span>
                                            </label>
                                            @if($item->id && $item->mainImg)
                                                <img src="{{ asset($item->mainImg->path) }}" class="img-thumbnail mt-2 mb-4">
                                            @endif
                                            <div class="col-12">
                                                <input type="file" name="file" class="form-control form-control-sm @error('file'){{ 'is-invalid' }}@enderror">
                                                @error('file')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @if($item->id)
                                <div class="tab-pane fade" id="ct-files" role="tabpanel" aria-labelledby="ct-files-tab">

                                    <div class="row">
                                        @foreach($languages as $lang)
                                            @php
                                                $default = $lang['default'];
                                                $code = $lang['code'];
                                                $code_upper = mb_strtoupper($code);
                                            @endphp
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="description_{{ $code }}" class="form-label">
                                                        Публично име ({{ $code_upper }})
                                                    </label>
                                                    <input value="{{ old("description_$code", '') }}" class="form-control form-control-sm @error("description_$code") is-invalid @enderror"
                                                           id="description_{{ $code }}" type="text" name="description_{{ $code }}"
                                                    >
                                                </div>
                                                <div class="mb-3">
                                                    <label for="file_{{ $code }}" class="form-label">
                                                        Изберете файл ({{ $code_upper }})
                                                    </label>
                                                    <input class="form-control form-control-sm @error("file_$code") is-invalid @enderror"
                                                           id="file_{{ $code }}" type="file" name="file_{{ $code }}"
                                                    >
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($item->files)
                                        <table class="table table-sm table-hover table-bordered mt-4">
                                            <tbody>
                                            <tr>
                                                <th>Преглед</th>
                                                <th>Име</th>
                                                <th></th>
                                            </tr>
                                            @foreach($item->files as $f)
                                                <tr>
                                                    @if(in_array($f->content_type, App\Models\File::CONTENT_TYPE_IMAGES))
                                                        @continue
                                                        <td>{!! $f->preview !!}</td>
                                                        <td>{{ $f->description }} @if($f->id == $item->file_id)({{ __('validation.attributes.main_img') }})@endif</td>
                                                        <td>
                                                            <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('admin.download.file', ['file' => $f->id]) }}">
                                                                <i class="fas fa-download me-1" role="button"
                                                                   data-toggle="tooltip" title="{{ __('custom.download') }}"></i>
                                                            </a>
                                                            <a class="btn btn-sm btn-danger" type="button" href="{{ route('admin.delete.file', ['file' => $f->id]) }}">
                                                                <i class="fas fa-trash me-1" role="button"
                                                                   data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>
                                                            </a>
                                                        </td>
                                                    @else
                                                        <td>
                                                            {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }}
                                                            - {{ __('custom.'.$f->locale) }} | {{ __('custom.version_short').' '.$f->version }}
                                                            | {{ displayDate($f->created_at) }} | {{ $f->user ? $f->user->fullName() : '' }}
                                                        </td>
                                                        <td>{{ $f->description }}</td>
                                                        <td>

                                                            <button type="button" class="btn btn-sm btn-outline-info preview-file-modal" data-file="{{ $f->id }}"
                                                                    data-url="{{ route('admin.preview.file.modal', ['id' => $f->id]) }}"
                                                            >
                                                                {{ __('custom.preview') }}
                                                            </button>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            @endif

                            <div class="form-group row">
                                <div class="col-md-6 col-md-offset-3">
                                    <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                    <button id="stay" type="submit" name="stay" class="btn btn-success" value="1">{{ __('custom.save_and_stay') }}</button>
                                    <a href="{{ route($listRouteName, ['type' => \App\Enums\PublicationTypesEnum::TYPE_ADVISORY_BOARD->value]) }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection