@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">
                        <a href="{{ route('admin.nomenclature.field-of-actions.create') }}"
                           class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ $title_singular }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{__('validation.attributes.label')}}</th>
                            <th>{{__('custom.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($actions) && $actions->count() > 0)
                            @foreach($actions as $action)
                                <tr>
                                    <td>{{ $action->id }}</td>
                                    <td>{{ $action->name }}</td>
                                    <td class="text-center">
                                        @can('update', $action)
                                            <a href="{{ route( 'admin.nomenclature.field_of_actions.edit', $action) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('delete', $action)
                                            <a href="javascript:;"
                                               class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                               data-target="#modal-delete-resource"
                                               data-resource-id="{{ $action->id }}"
                                               data-resource-name="{{ $action->name }}"
                                               data-resource-delete-url="{{ route('admin.nomenclatures.field_of_actions.delete', $action->id) }}"
                                               data-toggle="tooltip"
                                               title="{{__('custom.deletion')}}">
                                                <i class="fas fa-trash"></i>
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
                    @if(isset($actions) && $actions->count() > 0)
                        {{ $actions->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>

            @includeIf('modals.delete-resource', ['resource' => $title_singular])
        </div>
    </section>

@endsection


