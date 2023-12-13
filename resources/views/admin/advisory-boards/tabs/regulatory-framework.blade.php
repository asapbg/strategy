@php
    $view_mode ??= false;
@endphp

<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.regulatory_framework', 1) }}</h3>
            </div>

            <div class="col-auto">
                @if(!$view_mode)
                    <button type="button" class="btn btn-success" onclick="ADVISORY_BOARD_REGULATORY_FRAMEWORK.submit();">
                        {{ __('custom.save') . ' ' . trans_choice('custom.regulatory_framework', 1) }}
                    </button>
                @else
                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#regulatory' }}"
                       class="btn btn-warning">{{ __('custom.editing') }}</a>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                @if(!$view_mode)
                    <form name="ADVISORY_BOARD_REGULATORY_FRAMEWORK"
                          action="{{ route('admin.advisory-boards.regulatory-framework.store', ['item' => $item, 'framework' => $item->regulatoryFramework]) }}"
                          method="post">
                        @csrf

                        @foreach(config('available_languages') as $lang)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="framework_description_{{ $lang['code'] }}">{{ __('custom.description') }}
                                        ({{ Str::upper($lang['code']) }})</label>

                                    @php
                                        $description = $item->regulatoryFramework?->translations->count() === 2 ?
                                            $item->regulatoryFramework->translations->first(fn($row) => $row->locale == $lang['code'])->description :
                                            old('framework_description_' . $lang['code'], '');
                                    @endphp

                                    <textarea class="form-control form-control-sm summernote"
                                              name="framework_description_{{ $lang['code'] }}"
                                              id="framework_description_{{ $lang['code'] }}">
                                    {{ $description }}
                                </textarea>

                                    @error('framework_description_' . $lang['code'])
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </form>
                @else
                    @foreach(config('available_languages') as $lang)
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description_{{ $lang['code'] }}">{{ __('custom.description') }}
                                    ({{ Str::upper($lang['code']) }})</label>

                                @php
                                    $description = $secretariat?->translations->count() === 2 ?
                                        $secretariat->translations->first(fn($row) => $row->locale == $lang['code'])->description : '';
                                @endphp

                                <div class="row">
                                    {!! $description !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h3>{{ trans_choice('custom.files', 2) }}</h3>
                    </div>

                    @if(!$view_mode)
                        <div class="col-auto">
                            <div class="custom-control custom-switch">
                                @php $checked = request()->get('show_deleted_regulatory_files', '0') == '1' ? 'checked' : '' @endphp
                                <input type="checkbox" class="custom-control-input"
                                       id="show-deleted-regulatory-framework-files"
                                       {{ $checked }} onchange="toggleDeletedFiles(this, 'regulatory')">
                                <label class="custom-control-label"
                                       for="show-deleted-regulatory-framework-files">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-auto">
                @if(!$view_mode)
                    <button type="button" class="btn btn-success" data-toggle="modal"
                            data-target="#modal-add-regulatory-framework-file">
                        <i class="fa fa-plus mr-3"></i>
                        {{ __('custom.add') . ' ' . __('custom.file') }}
                    </button>
                @else
                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#regulatory' }}"
                       class="btn btn-warning">{{ __('custom.editing') }}</a>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                @include('admin.partial.files_table', ['files' => $item->regulatoryFramework?->files, 'item' => $item])
            </div>
        </div>
    </div>
</div>
