@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.pris.side_menu')


        <div class="col-lg-10 right-side-content py-5" >
            @if(isset($pageTopContent) && !empty(trim($pageTopContent)))
                <div class="col-12 mb-5">
                    {!! $pageTopContent !!}
                </div>
            @endif
            <div class="row mb-2">
                <div class="col-md-6 mt-2">
                    <div class="info-consul text-start">
                        <p class="fw-600">
                            {{ trans_choice('custom.total_pagination_result', $items->count(), ['number' => $items->total()]) }}
                        </p>
                    </div>
                </div>
                {{--                @include('site.partial.paginate_filter', ['ajaxContainer' => '#listContainer'])--}}
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
                                                    <a href="{{ route('op.view', ['id' => $item->id]) }}" class="text-decoration-none" title="{{ $item->name }}">
                                                        <i class="me-1 main-color fw-bold fst-normal">
                                                            {{ $item->name }}
                                                        </i>
                                                        <i class="fas fa-arrow-right read-more"></i>
                                                    </a>
                                                    <div class="meta-consul">
                                                        <span>
                                                            <span class="{{ $item->isActual ? 'active' : 'inactive' }}-ks">{{ $item->isActual ? __('custom.active_f') : __('custom.archive') }}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="consult-item-header-edit">
                                                    @can('delete', $item)
                                                        <a href="javascript:;"
                                                           class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden"
                                                           data-target="#modal-delete-resource"
                                                           data-resource-id="{{ $item->id }}"
                                                           data-resource-name="{{ $item->name }}"
                                                           data-resource-delete-url="{{ route('admin.consultations.operational_programs.delete', $item) }}"
                                                           data-toggle="tooltip"
                                                           title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                                        </a>
                                                    @endcan
                                                    @can('update', $item)
                                                        <a href="{{ route('admin.consultations.operational_programs.edit', $item) }}" target="_blank">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                                                <span class="d-none">{{ __('custom.edit') }}</span>
                                                            </i>
                                                        </a>
                                                    @endcan
                                                </div>
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
                    {{ $items->onEachSide(0)->appends(request()->query())->links() }}
                @endif
            </div>
        </div>
        @includeIf('modals.delete-resource', ['resource' => $title_singular])
    </div>
@endsection
