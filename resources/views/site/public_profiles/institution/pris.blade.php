@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.public_profiles.institution_menu')
        <div class="col-lg-10 right-side-content py-5">
            <div class="row mb-2">
                <h2 class="mb-4">
                    {{ __('site.institution.pris.title', ['name' => $item->name]) }}
                </h2>
                @if($items->count())
                    @foreach($items as $row)
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="consul-wrapper">
                                    <div class="single-consultation d-flex">
                                        <div class="consult-body">
                                            <div class="consul-item">
                                                <div class="consult-item-header d-flex justify-content-between">
                                                    <div class="consult-item-header-link">
                                                        <a href="{{ $row->in_archive ? route('pris.archive.view', ['category' => \Illuminate\Support\Str::slug($row->actType->name), 'id' => $row->id]) : route('pris.view', ['category' => \Illuminate\Support\Str::slug($row->actType->name),'id' => $row->id]) }}" class="text-decoration-none" title="{{ $row->mcDisplayName }}">
                                                            <h3>{{ $row->mcDisplayName }}</h3>
                                                        </a>
                                                    </div>
                                                    <div class="consult-item-header-edit">
                                                        @can('delete', $row)
                                                            <a href="javascript:;"
                                                               class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden"
                                                               data-target="#modal-delete-resource"
                                                               data-resource-id="{{ $row->id }}"
                                                               data-resource-name="{{ $row->mcDisplayName }}"
                                                               data-resource-delete-url="{{ route('admin.pris.delete', $row) }}"
                                                               data-toggle="tooltip"
                                                               title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                                            </a>
                                                        @endcan
                                                        @can('update', $row)
                                                            <a href="{{ route('admin.pris.edit', ['item' => $row->id]) }}" target="_blank">
                                                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                                                </i>
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                                <div class="meta-consul d-flex justify-content-start">
                                                    <a href="#" title="{{ __('custom.category') }}" class="text-decoration-none mb-2 me-2">
                                                        <i class="fa-solid fa-thumbtack  main-color" title="{{ $row->actType->name }}"></i>
                                                        {{ $row->actType->name }}
                                                    </a>
                                                    @if($row->institutions->count())
                                                        @foreach($row->institutions as $i)
                                                            @if($i->id != config('app.default_institution_id'))
                                                                <a href="{{ route('pris.index').'?institutions[]='.$i->id }}" title="{{ trans_choice('custom.institutions', 1) }}" class="text-decoration-none mb-2 me-2" target="_blank">
                                                                    <i class="fas fa-university fa-link main-color" title="{{ $i->name }}"></i>
                                                                    {{ $i->name }}
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="meta-consul">
                                                    <div class="anotation text-secondary mb-2">
                                            <span class="main-color me-2">
                                                {{ __('site.pris.about') }}:
                                            </span> {!! $row->about !!}
                                                    </div>
                                                </div>
                                                <div class="meta-consul">
                                                    <span class="text-secondary d-flex flex-row align-items-baseline lh-normal"><i class="far fa-calendar text-secondary me-1"></i> {{ displayDate($row->doc_date) }} {{ __('site.year_short') }}</span>
                                                    <a href="{{ $row->in_archive ? route('pris.archive.view', ['category' => \Illuminate\Support\Str::slug($row->actType->name), 'id' => $row->id]) : route('pris.view', ['category' => \Illuminate\Support\Str::slug($row->actType->name), 'id' => $row->id]) }}"><i class="fas fa-arrow-right read-more mt-2"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        @if(isset($items) && $items->count() > 0)
                            {{ $items->onEachSide(0)->appends(request()->query())->links() }}
                        @endif
                    </div>
                @else
                    <div class="col-lg-6 mb-4 ">
                        <p class="main-color">{{ __('messages.records_not_found') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.act', 1)])
@endsection
