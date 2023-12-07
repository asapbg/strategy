@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">
                        <a href="{{ route('admin.strategic_documents.edit') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ trans_choice('custom.strategic_documents', 1) }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('validation.attributes.title') }}</th>
                            <th>{{ trans_choice('custom.strategic_document_types', 1) }}</th>
                            <th>{{ trans_choice('custom.strategic_document_levels', 1) }}</th>
                            <th>{{ trans_choice('custom.authority_accepting_strategics', 1) }}</th>
                            <th>{{ __('custom.published') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->documentType->name }}</td>
                                    <td>{{ $item->documentLevel?->name }}</td>
                                    <td>{{ $item->acceptActInstitution?->name }}</td>
                                    <td>@if($item->active) <i class="fas fa-check text-success"></i> @else <i class="fas fa-minus text-danger"></i> @endif</td>
                                    <td class="text-center" style="width: 50px; white-space: nowrap;">
                                        @can('update', $item)
                                            <a href="{{ route( $editRouteName , [$item->id]) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan
                                        @if($item->active)
                                            @can('update', $item)
                                                 <a href="{{ route($unPublishRouteName, ['id' => $item->id, 'stay' => false]) }}"
                                                   class="btn btn-sm btn-danger mr-2"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.unpublish') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endcan
                                        @else
                                            @can('update', $item)
                                                <a href="{{ route($publishRouteName, ['id' => $item->id, 'stay' => false]) }}"
                                                   class="btn btn-sm btn-warning mr-2"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.publish') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endcan
                                        @endif
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


