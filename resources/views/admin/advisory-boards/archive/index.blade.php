@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid pt-3">
            <div class="card">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group form-group-sm">
                                    <label for="keywords" class="control-label">{{ trans_choice('custom.keyword', 2) }}
                                        <span
                                            class="required">*</span> </label>
                                    <input id="keywords" value="{{ request()->get('keywords') }}"
                                           class="form-control form-control-sm"
                                           type="text" name="keywords">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-sm btn-success">
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
                            <th>{{ trans_choice('validation.attributes.advisory_name', 1) }}</th>
                            <th>{{ __('custom.active_m') }}</th>
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
                                    <td>
                                        @includeIf('partials.toggle-boolean', ['object' => $item, 'model' => 'AdvisoryBoard'])
                                    </td>
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

    @includeIf('modals.delete-resource', ['resource' => $title_singular])
    @includeIf('modals.restore-resource', ['resource' => $title_singular])
@endsection
