@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">

                    <form method="GET">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="mb-3 d-flex flex-column w-100">
                                        <label for="institutions" class="form-label">{{ __('Name of contractor') }}</label>
                                        <select class="form-control form-control-sm select2" name="institutions[]" id="institutions" multiple>
                                            <option value=""></option>
                                            @foreach($institutions as $institution)
                                                <option value="{{ $institution->id }}"
                                                        @if(is_array($req_institutions) && in_array($institution->id,$req_institutions)) selected @endif
                                                >
                                                    {{ $institution->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="input-group">
                                    <div class="mb-3 d-flex flex-column w-100">
                                        <label for="executor_name" class="form-label">{{ __('Name of executor') }}</label>
                                        <input id="executor_name" class="form-control" name="executor_name" type="text"
                                               value="{{ request()->get('executor_name', '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <label for="contract_date_from" class="form-label">Начална дата</label>
                                <div class="input-group">
                                    <div class="mb-3 d-flex flex-column w-100">
                                        <input type="text" name="contract_date_from" autocomplete="off"
                                               id="contract_date_from" class="form-control datepicker"
                                               value="{{ request()->offsetGet('contract_date_from') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <label for="contract_date_till" class="form-label">Крайна дата</label>
                                <div class="input-group">
                                    <div class="mb-3 d-flex flex-column w-100">
                                        <input type="text" name="contract_date_till" autocomplete="off"
                                               id="contract_date_till" class="form-control datepicker"
                                               value="{{ request()->offsetGet('contract_date_till') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-3 col-sm-4 mb-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="input-group">
                                    <button type="submit" name="search" value="1" class="btn btn-success mr-1">
                                        <i class="fa fa-search"></i> {{ __('custom.search') }}
                                    </button>

                                    <a href="{{ url()->current() }}" class="btn btn-default">
                                        <i class="fas fa-eraser"></i> {{ __('custom.clear') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        @includeIf('partials.status', ['action' => 'App\Http\Controllers\Admin\ExecutorController@index'])
                        <a href="{{ route('admin.executors.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ l_trans('custom.new_record') }}
                        </a>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('Name of contractor') }}</th>
                            <th>{{ __('Name of executor') }}</th>
                            <th>{{ __('Contract date') }}</th>
                            <th>{{ __('custom.active_f') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($executors as $executor)
                            <tr>
                                <td>{{ $executor->id }}</td>
                                <td>{{ $executor->institution?->name ?? $translation?->contractor_name }}</td>
                                <td>{{ $executor->executor_name }}</td>
                                <td>{{ displayDate($executor->contract_date) }}</td>
                                <td>
                                    @includeIf('partials.toggle-boolean', ['object' => $executor, 'model' => 'Executor'])
                                </td>
                                <td>
                                    <a href="{{ route('admin.executors.edit', $executor->id) }}"
                                       class="btn btn-sm btn-info mb-1"
                                       data-toggle="tooltip"
                                       title="{{ __('custom.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:;"
                                       class="btn btn-sm btn-danger js-toggle-delete-resource-modal mb-1"
                                       data-target="#modal-delete-resource"
                                       data-resource-id="{{ $executor->id }}"
                                       data-resource-title="{{ $executor->title }}"
                                       data-resource-delete-url="{{ route('admin.executors.destroy', $executor->id )}}"
                                       data-toggle="tooltip"
                                       title="{{ __('custom.deletion') }}">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer mt-2">
                    @if(isset($executors) && $executors instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $executors->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </section>
    @includeIf('modals.delete-resource', ['resource' => $title_singular])
@endsection
