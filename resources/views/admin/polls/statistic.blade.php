@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header fw-bold">
                    {{ trans_choice('custom.polls', 1).' '. $item->name }}
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(isset($pc) && $pc)
                            <div class="form-group row">
                                <div class="col-12 col-md-offset-3">
                                    <a href="{{ route('admin.consultations.public_consultations.edit', $pc).'#ct-polls' }}" class="btn btn-sm btn-primary mb-2"><i class="fas fa-arrow-left mr-2" title="{{ __('custom.back') }}"></i>Обратно към консултацията</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row mb-3">
                        @foreach($item->questions as $key => $q)
                            <div class="col-md-6 mb-4">
                                <p class="fw-bold fs-18 mb-2">{{ __('custom.question_with_number', ['number' => ($key+1)]) }} {{ $q->name }} </p>
                                <div class="mb-2">Потребители: <span>{{ $statistic[$q->id]['users'] }}</span></div>
                                @foreach($q->answers as $key => $a)
                                    @php($percents = 0)
                                    <div class="@if(!$loop->first) mt-2 @endif">
                                        {{ $a->name }}
                                    </div>
                                    @if(sizeof($statistic) && isset($statistic[$q->id]) && isset($statistic[$q->id]['options'][$a->id]))
                                        @php($percents = ($statistic[$q->id]['options'][$a->id] * 100) / $statistic[$q->id]['users'])
                                    @endif
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percents }}%" aria-valuenow="{{ $percents }}" aria-valuemin="0" aria-valuemax="100">{{ $percents }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-12">
                        @if(isset($pc) && $pc)
                            <a href="{{ route('admin.consultations.public_consultations.edit', $pc).'#ct-polls' }}" class="btn btn-sm btn-primary mb-2 w-auto"><i class="fas fa-arrow-left mr-2" title="{{ __('custom.back') }}"></i>Обратно към консултацията</a>
                        @endif
                        <a href="{{ route('admin.polls.index') }}" class="btn btn-primary w-auto">{{ __('custom.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
