@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">

            @if(isset($customListRouteName) && !empty($customListRouteName))
                @include('admin.partial.filter_form', ['customListRoute' => route($customListRouteName, ['module' => $module])])
            @else
                @include('admin.partial.filter_form')
            @endif
            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">

                        @includeIf('partials.status', ['action' => 'App\Http\Controllers\Admin\PageController@index'])

                        <a href="{{ isset($customEditRouteName) && !empty($customEditRouteName) ? route($customEditRouteName, ['item' => 0, 'module' => $module]) : route($editRouteName) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ $title_singular }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{__('validation.attributes.name')}}</th>
                            <th>{{__('custom.footer_menu')}}</th>
                            <th>{{__('custom.is_system')}}</th>
                            <th>{{__('custom.active_m')}}</th>
                            <th>{{__('custom.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if(!empty($item->system_name) && \Lang::has('custom.pages.'.$item->system_name))
                                            <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="{{ __('custom.pages.'.$item->system_name) }}"></i>
                                        @endif
                                            {{ $item->name }}
                                        @if(!empty($item->system_name) && \Lang::has('custom.pages.'.$item->system_name))
                                            <span class="d-block text-primary fs-14"><i>({{ __('custom.pages.'.$item->system_name) }})</i></span>
                                        @endif
                                    </td>
                                    <td><i class="fas @if($item->in_footer) fa-check text-success @else fa-minus text-danger @endif" ></i></td>
                                    <td><i class="fas @if($item->is_system) fa-check text-success @else fa-minus text-danger @endif" ></i></td>
                                    <td>
                                        @if(isset($toggleBooleanModel))
                                            @includeIf('partials.toggle-boolean', ['object' => $item, 'model' => $toggleBooleanModel, 'disable_btn' => !empty($item->system_name)])
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @can('update', $item)
                                            <a href="{{ isset($customEditRouteName) && !empty($customEditRouteName) ? route($customEditRouteName, ['item' => $item->id, 'module' => $module]) : route( $editRouteName , [$item->id]) }}"
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

            @includeIf('modals.delete-resource', ['resource' => $title_singular])
        </div>
    </section>

@endsection


