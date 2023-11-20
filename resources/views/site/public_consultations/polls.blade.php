<div class="row mb-0 mt-4">
    <div class="col-md-12">
        <div class="custom-card py-4 px-3">
            <h3 class="mb-3">{{ __('site.public_consultation.polls') }}</h3>
            @if($item->pollsInPeriod->count())
                @foreach($item->pollsInPeriod as $poll)
                    @if($poll->questions->count())
                        <form class="row" action="{{ route('poll.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $poll->id }}">
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
                                    <div class="comment-background p-2 rounded">
                                        <p class="fw-bold fs-18 mb-2">{{ $q->name }}</p>
                                        @error('a_'.$q->id)
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @foreach($q->answers as $key => $a)
                                            <div class="form-check">
                                                <input class="form-check-input @error('a_'.$q->id.'.'.$key) is-invalid @enderror"
                                                       type="@if($multiAnswer){{ 'checkbox' }}@else{{ 'radio' }}@endif"
                                                       name="a_{{ $q->id }}[]" value="{{ $a->id }}" @if(in_array($a->id, old('a_'.$q->id, []))) checked @endif>
                                                <label class="form-check-label" for="a_{{ $q->id }}[]">
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
            @else
                ---
            @endif
        </div>
    </div>
</div>
