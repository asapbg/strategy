<div class="row justify-content-between align-items-center">
    <div class="col-auto">
        <h4>{{ __('validation.attributes.act_of_creation') }}</h4>
    </div>

    <div class="col-auto">
        @if(!$view_mode)
            <button type="button" class="btn btn-success" onclick="ADVISORY_BOARD_ESTABLISHMENTS.submit();">
                {{ __('custom.save') . ' ' . __('validation.attributes.act_of_creation') }}
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
            <form name="ADVISORY_BOARD_ESTABLISHMENTS"
                  action="{{ route('admin.advisory-boards.regulatory-framework.establishments.store', ['item' => $item, 'rule' => $item->establishment]) }}"
                  method="post">
                @csrf

                <div class="row mb-3">
                    @foreach(config('available_languages') as $lang)
                        <div class="col-6">
                            <label for="establishment_description_{{ $lang['code'] }}">{{ __('custom.description') }}
                                ({{ Str::upper($lang['code']) }})</label>

                            @php
                                $description = $item->establishment?->translations->count() === 2 ?
                                    $item->establishment->translations->first(fn($row) => $row->locale == $lang['code'])->description :
                                    old('establishment_description_' . $lang['code'], '');
                            @endphp

                            <textarea class="form-control form-control-sm summernote"
                                      name="establishment_description_{{ $lang['code'] }}"
                                      id="establishment_description_{{ $lang['code'] }}">
                                    {{ $description }}
                                </textarea>

                            @error('establishment_description_' . $lang['code'])
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </form>
        @else
            @foreach(config('available_languages') as $lang)
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="establishment_description_{{ $lang['code'] }}">{{ __('custom.description') }}
                            ({{ Str::upper($lang['code']) }})</label>

                        @php
                            $description = $item->establishment?->translations->count() === 2 ?
                                $item->establishment->translations->first(fn($row) => $row->locale == $lang['code'])->description : '';
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