@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <form method="GET">

                    <div class="card-header with-border">

                        <div class="card-tools pull-right">
                            <label>{{ trans_choice('custom.results', 2) }}: </label>
                            <select name="paginate" class="form-control d-inline w-auto">
                                @foreach(range(1,3) as $multiplier)
                                    @php
                                        $paginate = $multiplier * App\Models\User::PAGINATE;
                                    @endphp
                                    <option value="{{ $paginate }}"
                                            @if (request()->get('paginate') == $paginate) selected="selected" @endif
                                    >{{ $paginate }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-box-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <h3 class="card-title">{{ __('custom.search') }}</h3>

                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-xs-12 col-md-3 mb-2">
                                <input type="text" name="name" placeholder="{{__('validation.attributes.first_name')}}"
                                       class="form-control"
                                       value="{{request()->get('name')}}">
                            </div>

                            <div class="col-xs-12 col-md-3 mb-2">
                                <input type="text" name="email" placeholder="{{__('validation.attributes.email')}}"
                                       class="form-control"
                                       value="{{request()->get('email')}}">
                            </div>
                            <div class="col-xs-12 col-md-3 col-sm-4 mb-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-search"></i> {{__('custom.search')}}
                                </button>
                                <a href="{{route('admin.sd.users')}}" class="btn btn-default">
                                    <i class="fas fa-eraser"></i> {{__('custom.clear')}}
                                </a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">

                        @includeIf('partials.status', ['action' => 'App\Http\Controllers\Admin\StrategicDocumentsModerators@index'])
                        @can('createSd',\App\Models\User::class)
                            <a href="{{ route('admin.sd.users.create') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus-circle"></i> {{__('custom.add')}} {{ trans_choice('custom.moderators', 1) }}
                            </a>
                        @endcan
                    </div>

                    <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{__('validation.attributes.first_name')}}</th>
{{--                            <th>{{__('validation.attributes.username')}}</th>--}}
                            <th>{{__('validation.attributes.email')}}</th>
                            <th class="d-none">{{__('validation.attributes.email')}}</th>
                            <th>{{__('validation.attributes.role')}}</th>
                            <th>{{__('validation.attributes.institution')}}</th>
                            <th>{{__('custom.active_m')}}</th>
                            <th>{{__('custom.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($users) && $users->count() > 0)
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->fullname()}}</td>
{{--                                    <td>{{$user->username}}</td>--}}
                                    <td>{{$user->email}}</td>
                                    <td class="d-none">{{$user->email}}</td>
                                    <td>{!! implode('<br>',$user->roles->pluck('display_name')->toArray()) !!}</td>
                                    <td>@if($user->institution){{ $user->institution->name }}@else{{ '---' }}@endif</td>
                                    <td>
                                        @if(!$user->hasRole([\App\Models\CustomRole::SUPER_USER_ROLE]))
                                            @includeIf('partials.toggle-boolean', ['object' => $user, 'model' => 'User'])
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @can('updateSd', $user)
                                            <a href="{{route('admin.sd.users.edit',$user->id)}}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="{{__('custom.edit')}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('deleteSd', $user)
                                            <a href="javascript:;"
                                               class="btn btn-sm btn-danger js-toggle-delete-resource-modal hidden"
                                               data-target="#modal-delete-resource"
                                               data-resource-id="{{ $user->id }}"
                                               data-resource-name="{{ "$user->first_name $user->last_name" }}"
                                               data-resource-delete-url="{{route('admin.sd.users.delete',$user->id)}}"
                                               data-toggle="tooltip"
                                               title="{{__('custom.deletion')}}">
                                                <i class="fa fa-trash"></i>
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
                    @if(isset($users) && $users->count() > 0)
                        {{ $users->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>

            @includeIf('modals.delete-resource', ['resource' => $title_singular])
        </div>
    </section>

@endsection
{{--@push('scripts')--}}
{{--    <script type="text/javascript">--}}
{{--        $(document).ready(function (){--}}
{{--            $('.roles').on('change', function (){--}}
{{--                let selectedRoles = $('.roles:checked').map(function () {--}}
{{--                    return $(this).data('institution')--}}
{{--                }).get();--}}
{{--                if(jQuery.inArray(1, selectedRoles) !== -1) {--}}
{{--                    $('#institution_select').removeClass('d-none');--}}
{{--                } else {--}}
{{--                    $('#institution_select').addClass('d-none');--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}


