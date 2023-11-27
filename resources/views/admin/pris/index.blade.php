@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">

        @include('admin.partial.filter_form')

        <div class="card">
            <div class="card-body table-responsive">

                <div class="mb-3">
                    @includeIf('partials.status', ['action' => 'App\Http\Controllers\Admin\PrisController@index'])

                    <a href="{{ route($editRouteName, 0) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ $title_singular }}
                    </a>
                </div>

                <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('custom.pris_about') }}</th>
                        <th>{{ trans_choice('custom.legal_act_types', 1) }}</th>
                        <th>{{__('custom.published_at')}}</th>
                        <th>{{ __('custom.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($items) && $items->count() > 0)
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{!! $item->about !!}</td>
                            <td>{{ $item->legal_act_type_id ? $item->actType->name : '---' }}</td>
                            <td>{{ $item->published_at ? displayDate($item->published_at) : '---' }}</td>
                            <td class="text-center">
                                @can('update', $item)
                                    <a href="{{ route( $editRouteName , [$item->id]) }}"
                                       class="btn btn-sm btn-info mr-2"
                                       data-toggle="tooltip"
                                       title="{{ __('custom.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endcan
                                @can('delete', $item)
                                    <a href="javascript:;"
                                       class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                       data-target="#modal-delete-resource"
                                       data-resource-id="{{ $item->id }}"
                                       data-resource-name="{{ $item->regNum }} ({{ $item->legal_act_type_id ? $item->actType->name : '---' }})"
                                       data-resource-delete-url="{{ route('admin.pris.delete', $item) }}"
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


