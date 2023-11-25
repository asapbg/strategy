@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid pt-3">
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
                    <div class="mb-3">
                        <a href="{{ route('admin.advisory-boards.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ $title_singular }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ trans_choice('validation.attributes.advisory_name', 1) }}</th>
                            <th>{{ __('validation.attributes.created_at') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td class="text-center">
                                        @can('view', $item)
                                            <a href="{{ route('admin.advisory-boards.view', $item) }}"
                                               class="btn btn-sm btn-warning mr-2"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.preview') }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('update', $item)
                                            <a href="{{ route('admin.advisory-boards.edit', $item) }}"
                                               class="btn btn-sm btn-warning mr-2"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.preview') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
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
@endsection


