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
                                        $paginate = $multiplier * App\Models\OgpArea::PAGINATE;
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
                            <div class="col-xs-12 col-md-3 mb-2 d-none">
                                <input type="text" name="username" placeholder="{{__('validation.attributes.username')}}"
                                       class="form-control"
                                       value="{{request()->get('username')}}">
                            </div>
                            <div class="col-xs-12 col-md-3 col-sm-4 mb-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-search"></i> {{__('custom.search')}}
                                </button>
                                <a href="{{route('admin.ogp.area.index')}}" class="btn btn-default">
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

                        @includeIf('partials.status', ['action' => 'App\Http\Controllers\Admin\Ogp\Areas@index'])

                        <a href="{{ route('admin.ogp.area.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{__('custom.add')}}
                        </a>

                    </div>

                    <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{__('validation.attributes.name')}}</th>
                            <th>{{__('custom.active_m')}}</th>
                            <th>{{__('custom.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $v)
                                <tr>
                                    <td>{{ $v->id }}</td>
                                    <td>{{ $v->name }}</td>
                                    <td>
                                        @includeIf('partials.toggle-boolean', ['object' => $v, 'model' => 'OgpArea'])
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.ogp.area.edit', $v->id )}}"
                                           class="btn btn-sm btn-info"
                                           data-toggle="tooltip"
                                           title="{{__('custom.edit')}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if(true)
                                        <a href="javascript:;"
                                           class="btn btn-sm btn-danger js-toggle-delete-resource-modal hidden"
                                           data-target="#modal-delete-resource"
                                           data-resource-id="{{ $v->id }}"
                                           data-resource-name="{{ "$v->name" }}"
                                           data-resource-delete-url="{{ route('admin.ogp.area.delete', $v->id) }}"
                                           data-toggle="tooltip"
                                           title="{{__('custom.deletion')}}">
                                            <i class="fa fa-trash"></i>
                                        </a>
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

            @includeIf('modals.delete-resource', ['resource' => $title_singular])
        </div>
    </section>

@endsection


