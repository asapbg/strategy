@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">
                        <a href="{{ route('admin.consultations.legislative_programs.edit') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ trans_choice('custom.legislative_programs', 1) }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('custom.period') }}</th>
                            <th>{{ __('custom.actual_f') }}</th>
                            <th>{{ __('custom.public_f') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->period }}</td>
                                    <td>@if($item->actual) <i class="fa fa-check text-success"></i> @else <i class="fa fa-minus text-danger"></i> @endif</td>
                                    <td>@if($item->public) <i class="fa fa-check text-success"></i> @else <i class="fa fa-minus text-danger"></i> @endif</td>
                                    <td class="text-start">
                                        @can('view', $item)
                                            <a href="{{ route('admin.consultations.legislative_programs.view', $item) }}"
                                               class="btn btn-sm btn-warning mr-2"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.preview') }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('publish', $item)
                                            <a href="{{ route('admin.consultations.legislative_programs.publish', $item) }}"
                                               class="btn btn-sm btn-success mr-2"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.publish') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('unPublish', $item)
                                            <a href="{{ route('admin.consultations.legislative_programs.unpublish', $item) }}"
                                               class="btn btn-sm btn-danger mr-2"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.unpublish') }}">
                                                <i class="fas fa-eye-slash"></i>
                                            </a>
                                        @endcan
                                        @can('update', $item)
                                            <a href="{{ route( $editRouteName , $item) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.edit') }}">
                                                <i class="fa fa-edit"></i>
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
        </div>
    </section>

@endsection


