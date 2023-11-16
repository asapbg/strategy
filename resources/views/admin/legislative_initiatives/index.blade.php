@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">
                        <a href="{{ route('admin.legislative_initiatives.edit') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ trans_choice('custom.legislative_initiatives_list', 1) }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ trans_choice('custom.regulatory_acts', 1) }}</th>
                            <th>{{ __('validation.attributes.author') }}</th>
                            <th>{{ __('validation.attributes.created_at') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->regulatoryAct->name }}</td>
                                    <td>{{ $item->author }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td class="text-center">
                                        @can('update', $item)
                                            <a href="{{ route( $editRouteName , [$item->id]) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('delete', $item)
                                            @if(!$item->deleted_at)
                                                <a href="javascript:;"
                                                   class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $item->id }}"
                                                   data-resource-name="{{ $item->display_name }}"
                                                   data-resource-delete-url="{{route('admin.legislative_initiatives.store',$item->id)}}"
                                                   data-toggle="tooltip"
                                                   title="{{__('custom.delete')}}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        @endcan

                                        @can('restore', $item)
                                            @if($item->deleted_at)
                                                <a href="javascript:;"
                                                   class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                   data-target="#modal-restore-resource"
                                                   data-resource-id="{{ $item->id }}"
                                                   data-resource-name="{{ $item->display_name }}"
                                                   data-resource-restore-url="{{route('admin.legislative_initiatives.store',$item->id)}}"
                                                   data-toggle="tooltip"
                                                   title="{{__('custom.restore')}}">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @includeIf('modals.restore-resource', ['resource' => $title_singular])
    @includeIf('modals.delete-resource', ['resource' => $title_singular, 'have_request_param' => true])
@endsection


