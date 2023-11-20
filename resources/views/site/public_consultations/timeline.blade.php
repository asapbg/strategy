<div class="col-lg-2">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="hori-timeline px-1" dir="ltr">
                <h3 class="mb-3">{{ __('site.history') }}</h3>
                <div class="timeline">
                    <ul class="timeline events">
                        @if(isset($timeline) && sizeof($timeline))
                            @foreach($timeline as $t)
                                <li class="timeline-item mb-5">
                                    <h5 class="fw-bold @if(!$t['isActive']) text-muted @endif fs-18">{{ $t['label'] }}</h5>
                                    <p class="@if(!$t['isActive']) text-muted @endif">{{ $t['date'] ?? '---' }}</p>
                                    {!! $t['description'] !!}
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
