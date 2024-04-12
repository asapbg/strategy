@if($item->pollsInPeriod->count())
    <div class="row mb-0 mt-4">
        <div class="col-md-12">
            <div class="custom-card py-4 px-3 mb-4">
                <h3 class="mb-3">{{ __('site.public_consultation.polls') }}</h3>
                    @foreach($item->pollsInPeriod as $poll)
                        @if($poll->questions->count())
                            <form class="row mb-3" action="{{ route('poll.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $poll->id }}">
                                <input type="hidden" name="pc_id" value="{{ $item->id }}">
                                <input type="hidden" name="source" value="pc">
                                @if(!$loop->first)
                                    <hr>
                                @endif
                                <p class="main-color fs-18"># {{ $poll->name }}</p>
                                @error('a')
                                <div class="text-danger mb-1">{{ $message }}</div>
                                @enderror
                                @php($multiAnswer = \App\Models\Poll::MORE_THEN_ONE_ANSWER)
                                @foreach($poll->questions as $key => $q)
                                    <div class="col-md-6 mb-4">
                                        <input type="hidden" name="q[]" value="{{ $q->id }}">
                                        <div>
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
                                    <button class="btn btn-primary">
                                        {{ __('custom.send') }}
                                    </button>
                                </div>
                            </form>
                        @endif
                    @endforeach
            </div>
        </div>
    </div>
@endif

@if($item->pollsFinished->count())
    <div class="row mb-0 mt-4">
        <div class="col-md-12">
            <div class="custom-card py-4 px-3 mb-4">
                <h3 class="mb-3">{{ __('site.public_consultation.polls') }}</h3>
                @foreach($item->pollsFinished as $poll)
                    <div class="col-12">
                        <h4># {{ $poll->name }}</h4>
                        <hr class="custom-hr">
                        <a href="{{ route('polls.export', ['id' => $poll->id, 'format' => 'pdf']) }}" class="btn btn-sm btn-primary main-color mt-2">
                            <i class="fas fa-file-pdf me-2 main-color"></i>{{ __('custom.export_as_pdf') }}
                        </a>
                        <a href="{{ route('polls.export', ['id' => $poll->id,'format' => 'excel']) }}" class="btn btn-sm btn-primary main-color mt-2">
                            <i class="fas fa-file-excel me-2 main-color"></i>{{ __('custom.export_as_excel') }}
                        </a>
                    </div>
                    @php($statistic = $poll->getStats())
                    @if($poll->questions->count())
                        <div class="row mt-4">
                            @foreach($poll->questions as $key => $q)
                                <div class="col-md-6 mb-4">
                                    <div class="comment-background p-2 rounded">
                                        <p class="fw-bold fs-18 mb-2">{{ __('custom.question_with_number', ['number' => ($key+1)]) }} {{ $q->name }} </p>
                                        <div class="mb-2">Потребители: <span>{{ isset($statistic[$q->id]) ? $statistic[$q->id]['users'] : 0 }}</span></div>
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
                                                    <div class="progress-bar main-progress-bar" role="progressbar" style="width: {{ $percents }}%" aria-valuenow="{{ $percents }}" aria-valuemin="0" aria-valuemax="100">{{ $percents }}%</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif
