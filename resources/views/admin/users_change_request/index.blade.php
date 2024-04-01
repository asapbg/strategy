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
                            <th>{{ __('custom.sended_at') }}</th>
                            <th>{{ trans_choice('custom.users', 1) }}</th>
                            <th >{{ __('custom.status') }}</th>
                            <th >{{ __('custom.data_for_change') }}</th>
                            <th >{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $row)
                                @php($data = json_decode($row->data, true))
                                @if($data)
                                    <tr>
                                        <td>{{ displayDateTime($row->created_at) }}</td>
                                        <td>{{ $row->user->fullName() }}</td>
                                        <td>
                                            {{ __('custom.user_change_request_status.'.$row->status) }}
                                            @if($row->status != \App\Models\UserChangeRequest::PENDING)
                                                <br><span class="main-color">({{ displayDateTime($row->updated_at) }})</span>
                                                @if($row->statusUser)
                                                    <br><a href="{{ route('admin.users.edit', $row->statusUser) }}" class="main-color" target="_blank"> {{ $row->statusUser->fullName() }}</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($data as $k => $v)
                                                @if(!$loop->first)<br>@endif{{ __('site.'.$k) }}: {{ $v }}
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('approve', $row)
                                                <button class="btn btn-sm btn-success approve-request" data-id="{{ $row->id }}">{{ __('custom.approve') }}</button>
                                            @endcan
                                            @can('reject', $row)
                                                <button class="btn btn-sm btn-danger reject-request" data-id="{{ $row->id }}">{{ __('custom.reject') }}</button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endif
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
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('.approve-request').on('click', function () {
                let itemId = $(this).data('id');
                new MyModal({
                    title: 'Одобрение на промяна на данни',
                    footer: '<button type="button" id="approve-request" class="btn btn-sm btn-success ms-3">' + @json(__('custom.continue')) + '</button>' +
                        '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="' + @json(__('custom.cancel')) + '">' + @json(__('custom.cancel')) + '</button>',
                    body: '<div class="alert alert-danger">Сигурни ли сте, че искате да одобрите заявката за промяна на данни?</div>' +
                        '<form id="approve_request" method="get" action="{{ route('admin.users.change_request.approve') }}"><input type="hidden" name="change_id" value="' + itemId + '" ></form>',
                });

                $('#approve-request').on('click', function (){
                    $('#approve_request').submit();
                });
            });

            $('.reject-request').on('click', function () {
                let itemId = $(this).data('id');
                new MyModal({
                    title: 'Отказ на промяна на данни',
                    footer: '<button type="button" id="reject-request" class="btn btn-sm btn-success ms-3">' + @json(__('custom.continue')) + '</button>' +
                        '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="' + @json(__('custom.cancel')) + '">' + @json(__('custom.cancel')) + '</button>',
                    body: '<div class="alert alert-danger">Сигурни ли сте, че искате да отхвърлите заявката за промяна на данни?</div>' +
                        '<form id="reject_request" method="get" action="{{ route('admin.users.change_request.reject') }}"><input type="hidden" name="change_id" value="' + itemId + '" ></form>',
                });

                $('#reject-request').on('click', function (){
                    $('#reject_request').submit();
                });
            });
        });
    </script>
@endpush

