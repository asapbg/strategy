
@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">
                        <a href="{{ route('admin.advisory-boards.messages.send') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ $title_singular }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>{{ __('validation.attributes.title') }}</th>
                            <th>{{__('custom.from')}}</th>
                            <th>{{__('custom.to')}}</th>
                            <th>{{__('custom.read')}}</th>
                            <th>{{__('custom.sent_date')}}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                @php($data = json_decode($item->data))
                                <tr>
                                    <td>{{ $data->subject }}</td>
                                    <td>{{ $data->from_name ?? '---' }}</td>
                                    <td>{{ $data->to_name ?? '---' }}</td>
                                    <td>{{ !is_null($item->read_at) ? displayDate($item->read_at) : __('custom.unread') }}</td>
                                    <td>{{ displayDate($item->created_at) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route( 'admin.advisory-boards.messages.view' , $item->id) }}"
                                           class="btn btn-sm btn-primary"
                                           data-toggle="tooltip"
                                           title="{{ __('custom.preview') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
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


