<div class="px-md-5" style="min-height: 300px;">
    @if($data->count())
        @foreach ($data as $li)
            @php
                $status_class = 'active-li';
                $color_class = 'success';
                switch ($li->getStatus($li->status)->name) {
                    case 'STATUS_CLOSED':
                        $status_class = 'closed-li';
                        $color_class = 'danger';
                        break;

                    case 'STATUS_SEND':
                        $status_class = 'send-li';
                        $color_class = 'primary';
                        break;
                }
            @endphp
            <div class="row @if(!$loop->last) mb-4 @endif">
                <div class="col-md-8">
                    <a href="{{ route('legislative_initiatives.view', $li) }}" title="{{ __('custom.change_f') }} {{ __('custom.in') }} {{ $li->law?->name }}"
                       class="ps-3 border-start border-4  border-{{ $color_class }}">
                        {{ __('custom.change_f') }} {{ __('custom.in') }} {{ $li->law?->name }}
                    </a>
                    <div class="mt-2">
                        @if($li->endAfterDays)
{{--                            <span class="item-separator mb-2">|</span>--}}
                            <span class="mb-2 text-secondary">
                                {{ __('custom.end_after') }}: {{ $li->endAfterDays.' '.trans_choice('custom.days', $li->endAfterDays) }}
                            </span>
{{--                            <span class="ms-2 mb-2">--}}
{{--                                <strong> {{ __('custom.end_after') }}:</strong>--}}
{{--                                <span class="voted-li">--}}
{{--                                {{ $li->endAfterDays.' '.trans_choice('custom.days', $li->endAfterDays) }}--}}
{{--                                </span>--}}
{{--                            </span>--}}
                        @else
                            @if(!empty($li->active_support) && $li->daysLeft)
                                <span class="mb-2 text-secondary">
                                    <i class="far fa-hourglass text-secondary" title="{{ __('custom.time_left') }}"></i> {{ $li->daysLeft }} {{ trans_choice('custom.days', ($li->daysLeft > 1 ? 2 : 1)) }}
                                </span>
                            @endif
                        @endif
                        <span class="item-separator mb-2">|</span>
                        <span class="ms-2 mb-2 text-secondary">
                            <i class="far fa-thumbs-up text-secondary"></i> {{ $li->countLikes() }}
                        </span>
                        <span class="ms-2 mb-2 text-secondary">
                            <i class="far fa-thumbs-up text-secondary"></i> {{ $li->countDislikes() }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <span class="{{ $status_class }}">{{ __('custom.legislative_' . \Illuminate\Support\Str::lower($li->getStatus($li->status)->name)) }}</span>
                </div>
            </div>
        @endforeach
    @else
        <p class="main-color">{{ __('site.not_involved_in_li') }}</p>
    @endif
</div>
