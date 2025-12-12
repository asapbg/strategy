@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">
                        @includeIf('partials.status', ['action' => 'App\Http\Controllers\Admin\Nomenclature\RegionController@index'])

                        <a href="{{ route('admin.nomenclatures.regions.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ $title_singular }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('validation.attributes.nuts2_code') }}</th>
                            <th>{{__('validation.attributes.name')}}</th>
                            <th>{{__('custom.active_m')}}</th>
                            <th>{{__('custom.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($regions) && $regions->count() > 0)
                            @foreach($regions as $region)
                                <tr>
                                    <td>{{ $region->id }}</td>
                                    <td>{{ $region->code }}</td>
                                    <td>{{ $region->name }}</td>
                                    <td>
                                        @can('update', $region)
                                            @includeIf('partials.toggle-boolean', ['object' => $region, 'model' => 'Region'])
                                        @endcan
                                    </td>
                                    <td class="text-center">
                                        @can('update', $region)
                                            <a href="{{ route('admin.nomenclatures.regions.edit' , $region) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.edit') }}"
                                            >
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
                    @if(isset($regions) && $regions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $regions->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>

            @includeIf('modals.delete-resource', ['resource' => $title_singular])
        </div>
    </section>

@endsection
