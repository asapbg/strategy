@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form method="GET">
{{--                <div class="card-header with-border">--}}
{{--                    <div class="card-tools pull-right">--}}
{{--                        <label>{{ trans_choice('custom.results', 2) }}: </label>--}}
{{--                        <select name="paginate" class="form-control d-inline w-auto">--}}
{{--                            @foreach(range(1,3) as $multiplier)--}}
{{--                                @php--}}
{{--                                    $paginate = $multiplier * App\Models\User::PAGINATE;--}}
{{--                                @endphp--}}
{{--                                <option value="{{ $paginate }}"--}}
{{--                                        @if (request()->get('paginate') == $paginate) selected="selected" @endif--}}
{{--                                >{{ $paginate }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                        <button type="button" class="btn btn-box-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">--}}
{{--                            <i class="fas fa-minus"></i>--}}
{{--                        </button>--}}
{{--                        <button type="button" class="btn btn-box-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">--}}
{{--                            <i class="fas fa-times"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <h3 class="card-title">{{ __('custom.search') }}</h3>--}}
{{--                </div>--}}
                <div class="card-body">
                    <div class="row">
                        <a href="{{ route('admin.user.notifications.mark_as_read') }}" class="btn btn-sm btn-success w-auto mb-3">Маркирай всички като прочетени</a>
                    </div>
                    <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>{{ __('custom.status') }}</th>
                            <th>{{ __('custom.subject') }}</th>
                            <th>{{ __('custom.sent_date') }}</th>
                            <th>{{ __('custom.date_of_delivery') }}</th>
                            <th>{{ trans_choice('custom.actions', 1) }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($notifications) && $notifications->count() > 0)
                            @foreach($notifications as $row)
                                <tr>
                                    <td>@if($row->unread()) {{ __('custom.unread') }} @else {{ __('custom.read') }} @endif</td>
                                    <td>{{ $row->data['subject'] ?? '' }}</td>
                                    <td>{{ displayDateTime($row->created_at) }}</td>
                                    <td>{!! ($row->unread() ? '<i class="fa fa-minus text-danger"></i>' : displayDateTime($row->read_at) )!!}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.user.notification_show', $row->id) }}"
                                           class="btn btn-sm"
                                           data-toggle="tooltip"
                                           title="{{ __('custom.view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>

                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer mt-2">
                    @if(isset($notifications) && $notifications->count() > 0)
                        {{ $notifications->appends(request()->query())->links() }}
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
@endsection


