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
                    <form action="{{ $storeRoute }}" method="post" name="form" id="form" enctype="multipart/form-data">
                        <div class="tab-content" id="custom-tabsContent">
                            <div class="tab-pane fade active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                                @csrf
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
                                                            @if($row['value'] == ($type ?? 0))
                                                                <option value="{{ $row['value'] }}" @if($type == $row['value'] || ($item && $item->id && $item->type == $row['value']) || (old('type', 1) == $row['value'])) selected @endif>{{ $row['name'] }}</option>
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

                                    <div class="col-12">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label class="control-label" for="publication_category_id">
                                                    {{ trans_choice('custom.categories', 1) }}:
                                                </label>
                                                @php
                                                    $category_id = old('publication_category_id') ?? $item->publication_category_id;
                                                @endphp
                                                <div class="d-inline">
                                                    <select id="publication_category_id" name="publication_category_id"
                                                            class="form-control select2 form-control-sm @error('category_id'){{ 'is-invalid' }}@enderror"
                                                    >
                                                        @foreach($publicationCategories as $category)
                                                            <option value="{{ $category->id }}" @if($category_id == $category->id) selected @endif>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('publication_category_id')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
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
                                                {{ __('validation.attributes.published_at') }} <span class="required">*</span>
                                            </label>
                                            <div class="col-12">
                                                <input type="text" name="published_at" value="{{ old('published_at', $item->id ? $item->published_at : displayDate(date('Y-m-d'))) }}" class="datepicker form-control form-control-sm @error('published_at'){{ 'is-invalid' }}@enderror">
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
                                                {{ __('validation.attributes.main_img') }}
                                            </label>
                                            @if($item->id && $item->mainImg)
                                                <img src="{{ asset('files'.DIRECTORY_SEPARATOR.str_replace('files'.DIRECTORY_SEPARATOR, '', $item->mainImg->path)) }}" class="img-thumbnail mt-2 mb-4">
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
                                    <div class="form-group row">
                                        <div class="col-md-6 col-md-offset-3">
                                            <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                            <button id="stay" type="submit" name="stay" class="btn btn-success" value="1">{{ __('custom.save_and_stay') }}</button>
                                            <a href="{{ route($listRouteName, ['type' => $type]) }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
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
                                                <th>Изображение</th>
                                                <th>Име</th>
                                                <th></th>
                                            </tr>
                                            @foreach($item->files as $f)
                                                <tr>
                                                    @if(in_array($f->content_type, App\Models\File::CONTENT_TYPE_IMAGES))
                                                        <td>{!! $f->preview !!}</td>
                                                        <td>{!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }}
                                                            - {{ __('custom.'.$f->locale) }}
                                                            | {{ displayDate($f->created_at) }} | {{ $f->user ? $f->user->fullName() : '' }} @if($f->id == $item->file_id) <i><strong>({{ __('validation.attributes.main_img') }})</strong></i> @endif</td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-primary preview-file-modal" data-file="{{ $f->id }}"
                                                                    data-url="{{ route('admin.preview.file.modal', ['id' => $f->id]) }}"
                                                            >
                                                                <i class="fas fa-eye"></i>
                                                            </button>
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
                                                            <i class="fas fa-minus text-danger"></i>
                                                        </td>
                                                        <td>
                                                            {!! fileIcon($f->content_type) !!} {{ $f->{'description_'.$f->locale} }}
                                                            - {{ __('custom.'.$f->locale) }}
                                                            | {{ displayDate($f->created_at) }} | {{ $f->user ? $f->user->fullName() : '' }}
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-primary preview-file-modal" data-file="{{ $f->id }}"
                                                                    data-url="{{ route('admin.preview.file.modal', ['id' => $f->id]) }}"
                                                            >
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('admin.download.file', ['file' => $f->id]) }}">
                                                                <i class="fas fa-download me-1" role="button"
                                                                   data-toggle="tooltip" title="{{ __('custom.download') }}"></i>
                                                            </a>
                                                            <a class="btn btn-sm btn-danger" type="button" href="{{ route('admin.delete.file', ['file' => $f->id]) }}">
                                                                <i class="fas fa-trash me-1" role="button"
                                                                   data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>
                                                            </a>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                    <div class="form-group row">
                                        <div class="col-md-6 col-md-offset-3">
                                            <button type="submit" name="save_files" class="btn btn-success" value="1">{{ __('custom.save') }}</button>
                                            <button type="submit" name="stay_in_files" class="btn btn-success" value="1">{{ __('custom.save_and_stay') }}</button>
                                            <a href="{{ route($listRouteName, ['type' => $type]) }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

{{--    @push('scripts')--}}
{{--        <script type="application/javascript">--}}
            {{--const field_of_action_categories = @json($fieldOfActionCategories);--}}
            {{--const categories_select = document.querySelector('#publication_category_id');--}}
            {{--const is_field_of_action_category_selected = @json(old('type', 1) == \App\Enums\PublicationTypesEnum::TYPE_ADVISORY_BOARD->value || !empty($item->type));--}}
            {{--const preselected_field_of_action = @json(old('publication_category_id', $item->publication_category_id));--}}

            {{--if (is_field_of_action_category_selected) {--}}
            {{--    populateCategoriesWithFieldOfActions(preselected_field_of_action);--}}
            {{--}--}}

            {{--function changePublicationType(select) {--}}
            {{--    // Консултативни съвети--}}
            {{--    if (select.value == 4) {--}}
            {{--        populateCategoriesWithFieldOfActions();--}}
            {{--    }--}}
            {{--}--}}

            {{--function populateCategoriesWithFieldOfActions(preselected = null) {--}}
            {{--    for (let category of field_of_action_categories) {--}}
            {{--        const option = document.createElement("option");--}}
            {{--        option.value = category.id;--}}
            {{--        option.text = category.name;--}}
            {{--        option.selected = preselected == option.value;--}}
            {{--        categories_select.appendChild(option);--}}
            {{--    }--}}
            {{--}--}}
{{--        </script>--}}
{{--    @endpush--}}
@endsection
