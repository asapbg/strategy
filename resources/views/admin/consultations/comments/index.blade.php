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
                            <th>{{ __('custom.created_at') }}</th>
                            <th>{{ trans_choice('custom.users', 1)  }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><a target="_blank" class="text-primary" href="{{ route('admin.consultations.public_consultations.edit', $item->commented) }}">{{ $item->commented ? $item->commented->title : '---' }}</a></td>
                                    <td>
                                        <div class="limit-length">
                                            {!! $item->content !!}
                                        </div>
                                        <div class="full-length d-none">
                                            {!! $item->content !!}
                                        </div>
                                    </td>
                                    <td>{{ displayDateTime($item->created_at) }}</td>
                                    <td>
                                        @if($item->user_id && $item->author)
                                            <a target="_blank" class="text-primary" href="{{ route('admin.users.edit', $item->author) }}">{{ $item->author->fullName() }}</a>
                                        @else
                                            {{ __('custom.anonymous') }}
                                        @endif
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


