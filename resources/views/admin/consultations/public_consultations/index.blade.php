@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">
                        <a href="{{ route($editRouteName) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ $title_singular }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>{{ __('validation.attributes.title') }}</th>
                            <th>{{ trans_choice('custom.consultation_level', 1) }}</th>
                            <th>{{ trans_choice('custom.start', 1) }}</th>
                            <th>{{ trans_choice('custom.end', 1) }}</th>
                            <th>{{ __('custom.status') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->reg_num }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>@if($item->consultation_level_id){{ __('custom.nomenclature_level.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($item->consultation_level_id)) }}@else{{ '---' }}@endif</td>
                                    <td>{{ $item->open_from }}</td>
                                    <td>{{ $item->open_to }}</td>
                                    <td>@if($item->active){{ __('custom.public_f') }}@else{{ __('custom.draft') }}@endif</td>
                                    <td class="text-center">
                                        @can('update', $item)
                                            <a href="{{ route( $editRouteName , $item) }}"
                                               class="btn btn-sm btn-info mr-2"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('publish', $item)
                                            <a href="{{ route('admin.consultations.public_consultations.publish', $item) }}"
                                               class="btn btn-sm btn-success mr-2"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.publish') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('unPublish', $item)
                                            <a href="{{ route('admin.consultations.public_consultations.unpublish', $item) }}"
                                               class="btn btn-sm btn-secondary mr-2"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.unpublish') }}">
                                                <i class="fas fa-eye-slash"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $item)
                                            <a href="javascript:;"
                                               class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                               data-target="#modal-delete-resource"
                                               data-resource-id="{{ $item->id }}"
                                               data-resource-name="{{ trans_choice('custom.public_consultations', 1) }} {{ $item->reg_num }} {{ $item->title }}"
                                               data-resource-delete-url="{{ route('admin.consultations.public_consultations.delete', $item) }}"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.delete') }}"><i class="fas fa-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="card-footer mt-2">
                    @if(isset($items) && $items->count() > 0)
                        {{ $items->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>

            @includeIf('modals.delete-resource', ['resource' => $title_singular])
        </div>
    </section>

@endsection


