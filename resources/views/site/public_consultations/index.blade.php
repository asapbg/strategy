@extends('layouts.site', ['fullwidth' => true])

@section('content')
    @if(isset($pageTopContent) && !empty($pageTopContent->value))
        <div class="col-12 my-5">
            {!! $pageTopContent->value !!}
        </div>
    @endif
    @include('site.partial.filter', ['btn_add' => true])
    @include('site.partial.sorter')

<div class="row mb-2">
    <div class="col-12 mt-2">
        <div class="info-consul text-start">
            <p class="fw-600">
                {{ trans_choice('custom.total_pagination_result', $pk->count(), ['number' => $pk->count()]) }}
            </p>
        </div>
    </div>
</div>

<div class="row pb-5">
@foreach($pk as $consultation)
    <div class="col-md-12 mb-4">
        <div class="consul-wrapper">
            <div class="single-consultation d-flex">
                {{--                            <div class="consult-img-holder">--}}
                {{--                                <img class="img-thumbnail" src="{{ asset('\img\default_library_img.jpg') }}">--}}
                {{--                            </div>--}}
                <div class="consult-body">

                <div class="consul-item">
                    <div class="consult-item-header d-flex justify-content-between">
                        <div class="consult-item-header-link">
                            <a href="{{ route('public_consultation.view', ['id' => $consultation->id]) }}" class="text-decoration-none" title="{{ $consultation->title }}">
                                <h3 class="mb-2">{{ $consultation->title }}</h3>
                            </a>
                        </div>
                        <div class="consult-item-header-edit">
                            @can('delete', $consultation)
                                <a href="javascript:;"
                                   class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden"
                                   data-target="#modal-delete-resource"
                                   data-resource-id="{{ $consultation->id }}"
                                   data-resource-name="{{ $consultation->title }}"
                                   data-resource-delete-url="{{ route('admin.consultations.public_consultations.delete', $consultation) }}"
                                   data-toggle="tooltip"
                                   title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                </a>
                            @endcan
                            @can('update', $consultation)
                                <a href="{{ route('admin.consultations.public_consultations.edit', $consultation) }}" target="_blank">
                                    <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                        <span class="d-none">{{ __('custom.edit') }}</span>
                                    </i>
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="meta-consul mb-2">
                        <span class="text-secondary"> {{ $consultation->reg_num }}</span>
                    </div>
                    <div class="meta-consul mb-2">
                        <span class="text-secondary"><i class="far fa-calendar text-secondary"
                                title="{{ __('custom.period') }}"></i> {{ displayDate($consultation->open_from) }} -
                            {{ displayDate($consultation->open_to) }}</span>
                    </div>
                    <div class="meta-consul">
                        <span><strong>{{ __('custom.status') }}:</strong>
                            <span class="{{ $consultation->inPeriodBoolean ? 'active' : 'inactive' }}-ks">{{ $consultation->inPeriod }}</span>
                        </span>
                        <a href="{{ route('public_consultation.view', ['id' => $consultation->id]) }}" title="{{ $consultation->title }}"><i
                                class="fas fa-arrow-right read-more text-end"></i><span class="d-none">{{ $consultation->title }}</span>
                        </a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>
@includeIf('modals.delete-resource', ['resource' => $title_singular])
@endsection
