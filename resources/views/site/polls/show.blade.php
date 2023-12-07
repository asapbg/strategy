@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('content')
    <div class="row">
        @include('site.legislative_initiatives.side_menu')

        <div class="col-lg-10 py-5 right-side-content">
            <h2 class="obj-title mb-4">
                {{ $item->name }}
            </h2>
            <div class="row">
                <div class="col-md-8">
                    <a href="#" class="text-decoration-none"><span class="obj-icon-info me-2"><i class="far fa-calendar me-1 dark-blue" title="{{ __('custom.published_at') }}"></i>{{ displayDate($item->created_at) }} {{ __('custom.year_short') }}.</span>
                    </a>
                    <a href="{{ route('poll.index').'?active='.$item->inPeriod }}" class="text-decoration-none">
                        <span class="obj-icon-info me-2">
                            <span>{{__('custom.status')}}:
                                @if($item->inPeriod)
                                    <span class="active-li">{{ __('custom.active_f') }}</span>
                                @else
                                    <span class="closed-li">{{ __('custom.closed_f') }}</span>
                                @endif
                            </span>
                        </span>
                    </a>
                </div>
                <div class="col-md-4 text-end">
                    @if(auth()->user() && auth()->user()->can('update', $item))
                        <a href="{{ route('admin.polls.edit', ['id' => $item->id]) }}" class="btn btn-sm btn-primary main-color">
                            <i class="fas fa-pen me-2 main-color"></i>{{ __('custom.edit') }}
                        </a>
                    @endif
{{--                    <button class="btn btn-sm btn-danger">--}}
{{--                        <i class="fas fa-regular fa-trash-can me-2 text-danger"></i>Изтриване на анкета--}}
{{--                    </button>--}}
                </div>
            </div>
            <hr class="custom-hr my-4">
            <div class="row mb-0 mt-4">
                <div class="col-md-12">
                    <div class="custom-card py-4 px-3">
                        <h3 class="mb-3">{{ $item->name }}</h3>
                        <form class="row mb-3" action="{{ route('poll.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="source" value="regular">
                            @error('a')
                            <div class="text-danger mb-1">{{ $message }}</div>
                            @enderror
                            @php($multiAnswer = \App\Models\Poll::MORE_THEN_ONE_ANSWER)
                            @foreach($item->questions as $key => $q)
                                <div class="col-md-6 mb-4">
                                    <input type="hidden" name="q[]" value="{{ $q->id }}">
                                    <div class="comment-background p-2 rounded">
                                        <p class="fw-bold fs-18 mb-2">{{ $q->name }}</p>
                                        @error('a_'.$q->id)
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @foreach($q->answers as $key => $a)
                                            <div class="form-check">
                                                <input class="form-check-input @error('a_'.$q->id.'.'.$key) is-invalid @enderror" id="a_{{ $q->id.$key }}"
                                                       type="@if($multiAnswer){{ 'checkbox' }}@else{{ 'radio' }}@endif"
                                                       name="a_{{ $q->id }}[]" value="{{ $a->id }}" @if(in_array($a->id, old('a_'.$q->id, []))) checked @endif>
                                                <label class="form-check-label" for="a_{{ $q->id.$key }}">
                                                    {{ $a->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="submit">
                                    {{ __('custom.send') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
