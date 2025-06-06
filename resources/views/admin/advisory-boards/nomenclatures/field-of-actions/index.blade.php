@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">
                        @includeIf('partials.status', ['action' => 'App\Http\Controllers\Admin\AdvisoryBoard\Nomenclature\AdvisoryBoardNomenclatureFieldOfActionController@index'])

                        <a href="{{ route('admin.advisory-boards.nomenclature.field-of-actions.create') }}"
                           class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ $title_singular }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{__('validation.attributes.label')}}</th>
                            <th>{{ __('custom.active_f') }}</th>
                            <th>{{__('custom.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($actions) && $actions->count() > 0)
                            @foreach($actions as $action)
                                <tr>
                                    <td>{{ $action->id }}</td>
                                    <td><i class="text-primary {{ $action->icon_class }} mr-2"></i> {{ $action->name }}</td>
                                    <td>
                                        @can('update', $action)
                                            @if(isset($toggleBooleanModel))
                                                @includeIf('partials.toggle-boolean', ['object' => $action, 'model' => $toggleBooleanModel])
                                            @endif
                                        @else
                                            <i class="fas @if($action->active){{ 'fa-check-circle text-success' }}@else{{ 'fa-minus text-danger' }}@endif"></i>
                                        @endcan
                                    </td>
                                    <td class="text-center">
                                        @can('update', $action)
                                            <a href="{{ route( 'admin.advisory-boards.nomenclature.field-of-actions.edit', $action) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan

                                        @if(!$action->deleted_at)
                                            @can('delete', $action)
                                                <a href="javascript:;"
                                                   class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $action->id }}"
                                                   data-resource-name="{{ $action->name }}"
                                                   data-resource-delete-url="{{ route('admin.advisory-boards.nomenclature.field-of-actions.delete', $action->id) }}"
                                                   data-toggle="tooltip"
                                                   title="{{__('custom.deletion')}}">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endcan
                                        @else
                                            @can('restore', $action)
                                                <a href="javascript:;"
                                                   class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                   data-target="#modal-restore-resource"
                                                   data-resource-id="{{ $action->id }}"
                                                   data-resource-name="{{ $action->name }}"
                                                   data-resource-restore-url="{{ route('admin.advisory-boards.nomenclature.field-of-actions.restore', $action->id) }}"
                                                   data-toggle="tooltip"
                                                   title="{{__('custom.restore')}}">
                                                    <i class="fas fa-trash-restore"></i>
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
                    @if(isset($actions) && $actions->count() > 0)
                        {{ $actions->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>

            @includeIf('modals.delete-resource', ['resource' => $title_singular])
            @includeIf('modals.restore-resource', ['resource' => $title_singular])
        </div>
    </section>

@endsection


