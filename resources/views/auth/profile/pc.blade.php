<div class="px-md-5" style="min-height: 300px;">
    @if($data->count())
        @foreach ($data as $pc)
            @php
                $inPeriod = $pc->inPeriodBoolean
            @endphp
            <div class="row @if(!$loop->last) mb-3 @endif">
                <div class="col-sm-8">
                    <a href="{{ route('public_consultation.view', ['id' => $pc->id]) }}" title="{{ $pc->title }}"
                       class="ps-3 border-start border-4  @if(!$inPeriod) border-warning @else border-success @endif">
                        {{ $pc->title }})
                    </a>
                </div>
                <div class="col-sm-2">
                    <span class="{{ $pc->inPeriodBoolean ? 'active' : 'inactive' }}-ks">{{ $pc->inPeriod }}</span>
                </div>
                @if($pc->comments->count())
                    <div class="col-12">
                        <p class="mt-2 mb-1">{{ __('site.my_comments') }}</p>
                        <hr class="custom-hr mb-2">
                        @foreach($pc->comments as $c)
                            <div class="col-12">
                                <span><i class="fas fa-clock main-color me-2"></i><strong>{{ displayDateTime($c->created_at) }}</strong></span>
                                {!! $c->content !!}
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <p class="main-color">{{ __('site.not_involved_in_pc') }}</p>
    @endif
</div>
