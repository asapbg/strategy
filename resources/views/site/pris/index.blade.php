@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">

        @include('site.pris.side_menu')

        <div class="col-lg-10  home-results home-results-two pris-list mt-5 mb-5" >
            @include('site.partial.filter')
            @include('site.partial.sorter')

            <div class="row mb-2">
                <div class="col-12 mt-2">
                    <div class="info-consul text-start">
                        <p class="fw-600">
                            {{ trans_choice('custom.total_pagination_result', $items->count(), ['number' => $items->count()]) }}
                        </p>
                    </div>
                </div>
            </div>

            @if($items->count())
                @foreach($items as $item)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="consul-wrapper">
                                <div class="single-consultation d-flex">
                                    <div class="consult-body">
                                        <div class="consul-item">
                                            <div class="consult-item-header d-flex justify-content-between">
                                                <div class="consult-item-header-link">
                                                    <a href="{{ route('pris.view', ['id' => $item->id]) }}" class="text-decoration-none" title="{{ $item->actType->name }} {{ __('custom.number_symbol') }}{{ $item->doc_num }}">
                                                        <h3>{{ $item->actType->name }} {{ __('custom.number_symbol') }}{{ $item->doc_num }} {{ __('custom.of') }} {{ $item->institution->name }} от {{ $item->docYear }} {{ __('site.year_short') }}</h3>
                                                    </a>
                                                </div>
                                                <div class="consult-item-header-edit">
                                                    @can('delete', $item)
                                                        <a href="javascript:;"
                                                           class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden"
                                                           data-target="#modal-delete-resource"
                                                           data-resource-id="{{ $item->id }}"
                                                           data-resource-name="{{ $item->actType->name }} {{ __('custom.number_symbol') }}{{ $item->doc_num }} {{ __('custom.of') }} {{ $item->institution->name }} от {{ $item->docYear }} {{ __('site.year_short') }}"
                                                           data-resource-delete-url="{{ route('admin.pris.delete', $item) }}"
                                                           data-toggle="tooltip"
                                                           title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                                        </a>
                                                    @endcan
                                                    @can('update', $item)
                                                        <a href="{{ route('admin.pris.edit', ['item' => $item->id]) }}" target="_blank">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                                            </i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div class="meta-consul mb-2 d-inline-block">
                                                <a href="#" title="{{ __('custom.category') }}" class="text-decoration-none mb-3 me-2">
                                                    <i class="fas fa-university fa-link main-color" title="{{ $item->actType->name }}"></i>
                                                    {{ $item->actType->name }}
                                                </a>
                                                <a href="#" title="{{ trans_choice('custom.institutions', 1) }}" class="text-decoration-none mb-3">
                                                    <i class="fas fa-university fa-link main-color" title="{{ $item->institution->name }}"></i>
                                                    {{ $item->institution->name }}
                                                </a>
                                            </div>
                                            <div class="meta-consul">
                                                <div class="anotation text-secondary">
                                                    <span class="main-color me-2">{{ __('site.pris.about') }}:</span> {!! $item->about !!}
                                                </div>
                                            </div>
                                            <div class="meta-consul">
                                                <span class="text-secondary"><i class="far fa-calendar text-secondary"></i> {{ displayDate($item->doc_date) }} {{ __('site.year_short') }}</span>
                                                <a href="{{ route('pris.view', ['id' => $item->id]) }}"><i class="fas fa-arrow-right read-more"></i></a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif


            <div class="row">
                @if(isset($items) && $items->count() > 0)
                    {{ $items->appends(request()->query())->links() }}
                @endif
            </div>
        </div>
        @includeIf('modals.delete-resource', ['resource' => $title_singular])
    </div>
@endsection
