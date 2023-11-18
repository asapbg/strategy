@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('admin.partial.filter_form')
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ trans_choice('custom.public_consultations', 1) }}</th>
                            <th>{{ __('validation.attributes.content') }}</th>
{{--                            <th>{{ __('custom.actions') }}</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->commented ? $item->commented->title : '---' }}</td>
                                    <td>{!! $item->content !!}</td>
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


