@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.pris.side_menu')


        <div class="col-lg-10  home-results home-results-two pris-list mt-5 mb-5" >
            @if(isset($pageTopContent) && !empty($pageTopContent->value))
                <div class="col-12 mb-5">
                    {!! $pageTopContent->value !!}
                </div>
            @endif
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
                                                    <a href="{{ route('op.view', ['id' => $item->id]) }}" class="text-decoration-none" title="{{ trans_choice('custom.operational_program', 1) }} {{ $item->name }}">
                                                        <i class="me-1 main-color fw-bold fst-normal">
                                                            {{ trans_choice('custom.operational_program', 1) }} {{ $item->name }}
                                                        </i>
                                                        <i class="fas fa-arrow-right read-more"></i>
                                                    </a>
                                                </div>
                                                <div class="consult-item-header-edit">
{{--                                                    @can('delete', $item)--}}
                                                        <a href="{{ route('admin.consultations.operational_programs.edit', $item) }}" target="_blank">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="{{ __('custom.delete') }}"><span class="d-none">{{ __('custom.delete') }}</span></i>
                                                        </a>
{{--                                                    @endcan--}}
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
                    {{ $items->appends(request()->query())->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection
