@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">
                        @includeIf('partials.status', ['action' => 'App\Http\Controllers\Admin\PublicationController@index'])

                        <a href="{{ route($editRouteName) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ $title_singular }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('validation.attributes.title') }}</th>
                            <th>{{ __('validation.attributes.type') }}</th>
                            <th>{{ __('validation.attributes.category') }}</th>
                            <th>{{__('custom.public_from')}}</th>
                            <th>{{__('custom.active_m')}}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ trans_choice('custom.public_sections.types.'.\App\Enums\PublicationTypesEnum::keyByValue($item->type), 1) }}</td>
                                    <td>@if($item->category){{ $item->category->name }}@endif</td>
                                    <td>{{ displayDate($item->published_at) }}</td>
                                    <td>
                                        @can('update', $item)
                                            @if(isset($toggleBooleanModel))
                                                @includeIf('partials.toggle-boolean', ['object' => $item, 'model' => $toggleBooleanModel])
                                            @endif
                                        @else
                                            <i class="fas @if($item->active){{ 'fa-check-circle text-success' }}@else{{ 'fa-minus text-danger' }}@endif"></i>
                                        @endcan
                                    </td>
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


