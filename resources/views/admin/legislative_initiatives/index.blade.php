@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="mb-3 d-flex flex-column w-100">
                                        <label for="keywords"
                                               class="form-label">{{ trans_choice('custom.keyword', 2) }}</label>
                                        <input id="keywords" class="form-control" name="keywords" type="text"
                                               value="{{ request()->get('keywords', '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="mb-3 d-flex flex-column w-100">
                                        <label for="institution"
                                               class="form-label">{{ trans_choice('custom.institutions', 1) }}</label>
                                        <select id="institution" class="institution form-select select2"
                                                name="institution"
                                                multiple>
                                            <option value="" disabled>--</option>
                                            @foreach($institutions as $institution)
                                                @php $selected = request()->get('institution', '') == $institution->id ? 'selected' : '' @endphp
                                                <option
                                                    value="{{ $institution->id }}" {{ $selected }}>{{ $institution->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="mb-3 d-flex flex-column w-100">
                                        <label for="status"
                                               class="form-label">{{ trans_choice('validation.attributes.status', 1) }}</label>
                                        <select id="status" class="institution form-select select2" name="status"
                                                multiple>
                                            <option value="" disabled>--</option>
                                            @foreach(\App\Enums\LegislativeInitiativeStatusesEnum::options() as $name => $value)
                                                @php $selected = request()->get('status', '') == $value ? 'selected' : '' @endphp
                                                <option
                                                    value="{{ $value }}" {{ $selected }}>{{ __('custom.legislative_'.strtolower($name)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-3 col-sm-4 mb-2">
                                <button type="submit" name="search" value="1" class="btn btn-sm btn-success">
                                    <i class="fa fa-search"></i> {{ __('custom.search') }}
                                </button>

                                <a href="{{ url()->current() }}" class="btn btn-sm btn-default">
                                    <i class="fas fa-eraser"></i> {{ __('custom.clear') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ trans_choice('custom.regulatory_acts', 1) }}</th>
                            <th>{{ __('validation.attributes.author') }}</th>
                            <th>{{ __('validation.attributes.created_at') }}</th>
                            <th>{{ __('custom.status') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ __('custom.change_f') . ' ' . __('custom.in') . ' ' . mb_strtolower($item->law?->name) }}</td>
                                    <td>{{ $item->user->fullName() }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ __('custom.legislative_' . strtolower($item->getStatus($item->status)->name)) }}</td>
                                    <td class="text-center">
                                        @can('view', $item)
                                            <a href="{{ route('admin.legislative_initiatives.view', [$item]) }}"
                                               class="btn btn-sm btn-warning mr-2"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.preview') }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('delete', $item)
                                            @if(!$item->deleted_at)
                                                <a href="javascript:;"
                                                   class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $item->id }}"
                                                   data-resource-delete-url="{{ route('admin.legislative_initiatives.delete', $item) }}"
                                                   data-toggle="tooltip"
                                                   title="{{__('custom.delete')}}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        @endcan

                                        @can('restore', $item)
                                            @if($item->deleted_at)
                                                <a href="javascript:;"
                                                   class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                                   data-target="#modal-restore-resource"
                                                   data-resource-id="{{ $item->id }}"
                                                   data-resource-restore-url="{{ route('admin.legislative_initiatives.restore', $item) }}"
                                                   data-toggle="tooltip"
                                                   title="{{__('custom.restore')}}">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    <div class="row">
                        <nav aria-label="Page navigation example">
                            @if(isset($items) && $items->count() > 0)
                                {{ $items->appends(request()->query())->links() }}
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @includeIf('modals.delete-resource', ['resource' => $title_singular, 'have_request_param' => true])
    @includeIf('modals.restore-resource', ['resource' => $title_singular])
@endsection


