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
                <div class="col-12">
                    <div class="custom-card py-4 px-3">
                        <h3 class="mb-3">{{ $item->name }}</h3>
                        <div class="row mb-3">
                            @foreach($item->questions as $key => $q)
                                <div class="col-md-6 mb-4">
                                    <div class="comment-background p-2 rounded">
                                        <p class="fw-bold fs-18 mb-2">{{ __('custom.question_with_number', ['number' => ($key+1)]) }} {{ $q->name }} </p>
                                        <div class="mb-2">Потребители: <span>{{ $statistic[$q->id]['users'] }}</span></div>
                                        @foreach($q->answers as $key => $a)
                                            @php($percents = 0)
                                            <div class="col-12 @if(!$loop->first) mt-2 @endif">
                                                {{ $a->name }}
                                            </div>
                                            @if(sizeof($statistic) && isset($statistic[$q->id]) && isset($statistic[$q->id]['options'][$a->id]))
                                                @php($percents = ($statistic[$q->id]['options'][$a->id] * 100) / $statistic[$q->id]['users'])
                                            @endif
                                            <div class="col-md-6">
                                                <div class="progress">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percents }}%" aria-valuenow="{{ $percents }}" aria-valuemin="0" aria-valuemax="100">{{ $percents }}%</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('poll.index') }}" class="btn btn-primary w-auto">{{ __('custom.back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
