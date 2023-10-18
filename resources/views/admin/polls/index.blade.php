@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">
                    <div class="mb-3">
                        <a href="{{ route($editRouteName, 0) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ trans_choice('custom.polls', 1) }}
                        </a>
                    </div>
                    @php($user = auth()->user())
                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>{{ __('custom.name') }}</th>
                                <th>{{ trans_choice('custom.questions', 2) }}</th>
                                <th>{{ __('custom.begin_date') }}</th>
                                <th>{{ __('custom.end_date') }}</th>
                                <th>{{ __('custom.once') }}</th>
                                <th>{{ __('custom.status') }}</th>
                                <th>{{ __('custom.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->questions->count() }}</td>
                                    <td>{{ $row->start_date }}</td>
                                    <td>{{ $row->end_date }}</td>
                                    <td>@if((int)$row->is_once)<i class="fa fa-check text-success"></i>@else<i class="fa fa-minus text-danger"></i>@endif</td>
                                    <td>@if((int)$row->status)<i class="fa fa-check text-success"></i>@else<i class="fa fa-minus text-danger"></i>@endif</td>
                                    <td>
                                        @if($user->can('update', $row))
                                            <a href="{{route($editRouteName,['id' => $row->id])}}"
                                               class="btn btn-sm btn-primary mr-2"
                                               data-toggle="tooltip"
                                               title="{{__('custom.edit')}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        @if($row->status == \App\Enums\PollStatusEnum::EXPIRED->value)
                                            <a href="{{route($previewRouteName,$row)}}"
                                               class="btn btn-sm btn-success mr-2"
                                               data-toggle="tooltip"
                                               title="{{__('custom.result')}}">
                                                <i class="fas fa-poll"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection


