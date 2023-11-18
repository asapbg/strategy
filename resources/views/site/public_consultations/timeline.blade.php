<div class="col-lg-2">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="hori-timeline px-1" dir="ltr">
                <h3 class="mb-3">{{ __('site.history') }}</h3>
                <div class="timeline">
                    <ul class="timeline events">
                        @foreach(\App\Enums\PublicConsultationTimelineEnum::ordered() as $eventId)
                            @php($isSet = isset($timeline) && isset($timeline[$eventId]))
                            @php($noTimelineRecord = in_array($eventId, \App\Enums\PublicConsultationTimelineEnum::noTimelineRecord()))
                            @php($isActive = false)
                            @if($noTimelineRecord)
                                @switch($eventId)
                                    @case(\App\Enums\PublicConsultationTimelineEnum::START->value)
                                        @php($isActive = $item->inPeriodBoolean)
                                        @break
                                    @case(\App\Enums\PublicConsultationTimelineEnum::END->value)
                                        @php($isActive = \Carbon\Carbon::parse($item->open_to) <= \Carbon\Carbon::now())
                                        @break
                                    @case(\App\Enums\PublicConsultationTimelineEnum::ACCEPT_ACT_MC->value)
                                        @php($isActive = $item->pris && \Carbon\Carbon::parse($item->open_to) <= \Carbon\Carbon::now())
                                        @break
                                    @case(\App\Enums\PublicConsultationTimelineEnum::FILE_CHANGE->value)
                                        @php($isActive = $item->changedFiles()->count())
                                        @break
                                    @case(\App\Enums\PublicConsultationTimelineEnum::PRESENTING_IN_NA->value)
                                        @php($isActive = false)
                                    @break
                                @endswitch
                            @endif
                            <li class="timeline-item mb-5">
                                <h5 class="fw-bold @if(!$isSet && !$isActive) text-muted @endif fs-18">{{ __('custom.timeline.'.\App\Enums\PublicConsultationTimelineEnum::keyByValue($eventId)) }}</h5>
                                <p class="@if(!$isSet && !$isActive) text-muted @endif mb-2 fw-bold ">
                                    @if($isSet)
                                        @switch($eventId)
                                            @case(\App\Enums\PublicConsultationTimelineEnum::INCLUDE_TO_PROGRAM->value)
                                                <a target="_blank" href="{{ route(($timeline[$eventId][0]->object instanceof \App\Models\Consultations\OperationalProgramRow ? 'op.view' : 'lp.view') , ['id' => $timeline[$eventId][0]->object_id]) }}">{{ displayDate($timeline[$eventId][0]->updated_at) }}</a>
                                                @break
                                            @default
                                                {{ displayDate($timeline[$eventId][0]->updated_at) }}
                                        @endswitch
                                    @elseif($noTimelineRecord)
                                        @switch($eventId)
                                            @case(\App\Enums\PublicConsultationTimelineEnum::START->value)
                                                {{ $item->open_from }}
                                                @break
                                            @case(\App\Enums\PublicConsultationTimelineEnum::END->value)
                                                {{ $item->open_to }}
                                                @break
                                            @case(\App\Enums\PublicConsultationTimelineEnum::ACCEPT_ACT_MC->value)
                                                {{ displayDate($item->pris->created_at) }}
                                                @break
                                            @case(\App\Enums\PublicConsultationTimelineEnum::FILE_CHANGE->value)
                                                @if($isActive)
                                                    @foreach($item->changedFiles() as $row)
                                                        <span class="d-inline-block @if(!$loop->last) mb-1 @endif">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary preview-file-modal" data-file="{{ $row->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $row->id]) }}" title="{{ __('custom.preview') }}">{!! fileIcon($row->content_type) !!} {{ $row->description }} {{ __('custom.version_short').' '.$row->version }}</button>
                                                        </span>
                                                    @endforeach
                                                @endif
                                                @break
                                        @endswitch
                                    @else
                                        ---
                                    @endif
                                </p>
                                <p class="@if(!$isSet && !$isActive) text-muted @endif">{{ __('custom.timeline.'.(\App\Enums\PublicConsultationTimelineEnum::keyByValue($eventId)).'.description') }}</p>
                            </li>
                        @endforeach
{{--                        <li class="timeline-item mb-5">--}}
{{--                            <h5 class="fw-bold fs-18 @if(!isset($timeline) || !isset($timeline[\App\Enums\PublicConsultationTimelineEnum::INCLUDE_TO_PROGRAM->value]))@endif">{{ __('custom.timeline.'.\App\Enums\PublicConsultationTimelineEnum::keyByValue(\App\Enums\PublicConsultationTimelineEnum::INCLUDE_TO_PROGRAM->value)) }}</h5>--}}
{{--                            <p class="mb-2 fw-bold">12.05.2023</p>--}}
{{--                            <p> Tова събитие описва запис на акт в ЗП или ОП.</p>--}}
{{--                        </li>--}}
{{--                        <li class="timeline-item mb-5">--}}
{{--                            <h5 class="fw-bold fs-18">Начало на обществената консултация</h5>--}}
{{--                            <p class="mb-2 fw-bold">{{ displayDate($item->open_from) }}</p>--}}
{{--                            <p> Визуализира се „Начало на консултацията“.</p>--}}
{{--                        </li>--}}
{{--                        <li class="timeline-item mb-5">--}}
{{--                            <h5 class="fw-bold fs-18">Промяна на файл </h5>--}}
{{--                            <p class="mb-2 fw-bold">25.05.2023</p>--}}
{{--                            <p> Промяна на файл от консултацията.</p>--}}
{{--                        </li>--}}
{{--                        <li class="timeline-item mb-5">--}}
{{--                            <h5 class="fw-bold text-muted fs-18">Приключване на консултацията</h5>--}}
{{--                            <p class="text-muted mb-2 fw-bold ">{{ displayDate($item->open_to) }}</p>--}}
{{--                            <p class="text-muted">Край на консултацията</p>--}}
{{--                        </li>--}}
{{--                        <li class="timeline-item mb-5">--}}
{{--                            <h5 class="fw-bold text-muted fs-18">Справка за получените предложения</h5>--}}
{{--                            <p class="text-muted mb-2 fw-bold">15.06.2023</p>--}}
{{--                            <p class="text-muted">Справка или съобщение.</p>--}}
{{--                        </li>--}}
{{--                        <li class="timeline-item mb-5">--}}
{{--                            <h5 class="fw-bold text-muted fs-18">Приемане на акта от Министерския съвет</h5>--}}
{{--                            <p class="text-muted mb-2 fw-bold text-muted">18.06.2023</p>--}}
{{--                            <p class="text-muted">Окончателен акт.</p>--}}
{{--                        </li>--}}
{{--                        <li class="timeline-item mb-5">--}}
{{--                            <h5 class="fw-bold text-muted fs-18"> Представяне на законопроекта</h5>--}}
{{--                            <p class="text-muted mb-2 fw-bold ">25.06.2023</p>--}}
{{--                            <p class="text-muted">Развито в обхвата на текущата поръчка.</p>--}}
{{--                        </li>--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
