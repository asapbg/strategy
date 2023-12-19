@php
    $hasOffer = isset($offer);
@endphp
<form action="{{ route('ogp.develop_new_action_plans.store_offer', ['id' => $planArea->id]) }}" method="POST">
    @csrf
    @if($hasOffer)
        <input type="hidden" name="offer" value="{{ $offer->id }}">
    @endif
<div class="row mb-4">
    <div class="col-md-12">
        <div class="add-suggestion">
            <h3 class="fs-4 mb-3">
                @if($hasOffer)
                    {{ __('ogp.edit_proposal') }}
                @else
                    {{ __('ogp.add_new_ogp_area') }}
                @endif
            </h3>
            <div class="row">

                <div class="col-md-12">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <textarea name="content" class="summernote @error('content'){{ 'is-invalid' }}@enderror">{{ old('content', $offer->content ?? '') }}</textarea>
                            @error('content')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <button class="btn btn-primary">
                        @if($hasOffer)
                            {{ __('custom.save') }}
                        @else
                            {{ __('ogp.add_new_proposal') }}
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
