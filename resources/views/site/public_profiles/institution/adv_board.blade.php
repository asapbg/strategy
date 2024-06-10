@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.public_profiles.institution_menu')
        <div class="col-lg-10 right-side-content py-2">
            <div class="row mb-2">
                <h2 class="mb-4">
                    {{ __('site.institution.adv_board.title', ['name' => $item->name]) }}
                </h2>
                @if($items->count())
                    @foreach($items as $row)
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="consul-wrapper">
                                    <div class="single-consultation d-flex">
                                        <div class="consult-body">
                                            <div class="consult-item-header d-flex justify-content-between">
                                                <div class="consult-item-header-link">
                                                    <a href="{{ route('advisory-boards.view', $row) }}"
                                                       class="text-decoration-none"
                                                       title="{{ $row->name }}">
                                                        <h3>{{ $row->name }}</h3>
                                                    </a>
                                                </div>

                                                @if($row->active && auth()->user())
                                                    @can('update', $row)
                                                        <div class="consult-item-header-edit">
                                                            <a href="{{ route('admin.advisory-boards.index') . '?keywords=' . $row->id . '&status=1' }}">
                                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                                   role="button" title="{{ __('custom.delete') }}"></i>
                                                            </a>
                                                            <a href="{{ route('admin.advisory-boards.edit', ['item' => $row]) }}" target="_blank"
                                                               class="me-2">
                                                                <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                                   role="button"
                                                                   title="{{ __('custom.edit') }}">
                                                                </i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                @endif
                                            </div>
                                            @if($row->policyArea)
                                                <a href="{{ route('advisory-boards.index').'?fieldOfActions[]='.$row->policyArea->id }}"
                                                   title="{{ $row->policyArea->name }}" class="text-decoration-none mb-2">
                                                    <i class="text-primary {{ $row->policyArea->icon_class }} me-1" title="{{ $row->policyArea->name }}"></i>
                                                    {{ $row->policyArea->name }}
                                                </a>
                                            @endif
                                            <div class="meta-consul mt-2">
                            <span>{{ __('custom.status') }}:
                                @php($class = $row->active ? 'active-ks' : 'inactive-ks')
                                <span
                                    class="{{ $class }}">{{ $row->active ? __('custom.active_m') : __('custom.inactive_m') }}</span>
                            </span>
                                                <a href="{{ route('advisory-boards.view', $row) }}">
                                                    <i class="fas fa-arrow-right read-more text-end"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
