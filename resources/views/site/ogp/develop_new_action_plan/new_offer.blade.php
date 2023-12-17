@php
    $hasOffer = isset($offer);
@endphp

<form action="{{ route('ogp.develop_new_action_plans.store', ['otg_area_id' => $ogpArea->id]) }}" method="POST">
    @csrf
    @if($hasOffer)
        <input type="hidden" name="offer" value="{{ $offer->id }}">
    @endif
<div class="row mb-4">
    <div class="col-md-12">
        <div class="add-suggestion">
            <h3 class="fs-4 mb-3">{{ __('ogp.add_new_ogp_area') }}</h3>
            <div class="row">
                <div @class(["col-md-12" => !$hasOffer, 'col-md-6' => $hasOffer])>
                    <div class="input-group">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="regulatory-act" class="form-label fw-600">
                                @if($hasOffer)
                                    {{ __('ogp.new_commitment') }}
                                @else
                                    {{ trans_choice('ogp.commitments', 1) }}
                                @endif
                            </label>
                            <input type="text" name="commitment_name" class="form-control @error('commitment_name'){{ 'is-invalid' }}@enderror" value="{{ old('commitment_name') }}">
                            @error('commitment_name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @if($hasOffer)
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="commitment_id" class="form-label fw-600">{{ __('ogp.exist_commitment') }}</label>
                                <select name="commitment_id" id="commitment_id" class="form-select @error('commitment_id'){{ 'is-invalid' }}@enderror">
                                    <option value="0"></option>
                                    @foreach($offer->commitments as $v)
                                    <option value="{{ $v->id }}" @if(old('commitment_id') == $v->id) selected="selected" @endif>{{ $v->name }}</option>
                                    @endforeach
                                </select>
                                @error('commitment_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
                <div @class(["col-md-12" => !$hasOffer, 'col-md-6' => $hasOffer])>
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="arrangement_name" class="form-label fw-600">
                                @if($hasOffer)
                                    {{ __('ogp.new_arrangement') }}
                                @else
                                    {{ trans_choice('ogp.arrangements', 1) }}
                                @endif
                            </label>
                            <input type="text" name="arrangement_name" class="form-control @error('arrangement_name'){{ 'is-invalid' }}@enderror" value="{{ old('arrangement_name') }}">
                            @error('arrangement_name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @if($hasOffer)
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="arrangement_id" class="form-label fw-600">{{ __('ogp.exists_arrangement') }}</label>
                                <select name="arrangement_id" id="arrangement_id" class="form-select @error('arrangement_id'){{ 'is-invalid' }}@enderror">
                                    <option value="0"></option>
                                    @foreach($offer->commitments as $v)
                                        <optgroup label="{{ $v->name }}">
                                        @foreach($v->arrangements as $a)
                                            <option value="{{ $a->id }}" @if(old('arrangement_id') == $a->id) selected="selected" @endif>{{ $a->name }}</option>
                                        @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('arrangement_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
                @foreach(\App\Enums\OgpAreaArrangementFieldEnum::options() as $v)
                <div class="col-md-12">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="field_{{ $v }}" class="form-label fw-600">
                                {{ __('ogp.arrangement_fields.'.$v) }}
                            </label>
                            <input type="text" name="fields[{{$v}}]" id="field_{{ $v }}" class="form-control @error('fields.'.$v){{ 'is-invalid' }}@enderror" value="{{ old('fields.'.$v) }}">
                            @error('fields.'.$v)
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="col-md-12 mb-2">
                    <button class="btn btn-primary">{{ __('ogp.add_new_proposal') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
