@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('admin.partial.filter_form')
            <div class="card">
                <div class="card-body table-responsive">

{{--                    <div class="mb-3">--}}
{{--                        <a href="{{ route('admin.strategic_documents.institutions.edit') }}" class="btn btn-sm btn-success">--}}
{{--                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ trans_choice('custom.institutions', 1) }}--}}
{{--                        </a>--}}
{{--                    </div>--}}

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('validation.attributes.name') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-center">
                                        @can('update', $item)
                                            <a href="{{ route( $editRouteName , [$item->id]) }}"
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


