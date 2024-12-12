@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">

                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="ct-general-tab" data-toggle="pill" href="#ct-general" role="tab" aria-controls="ct-general" aria-selected="true">
                                Основна информация
                            </a>
                        </li>
                        @if($item->id)
                            <li class="nav-item">
                                <a class="nav-link" id="ct-history-tab" data-toggle="pill" href="#ct-history" role="tab" aria-controls="ct-links" aria-selected="false">
                                    История на промените
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ct-links-tab" data-toggle="pill" href="#ct-links" role="tab" aria-controls="ct-links" aria-selected="false">
                                    {{ __('custom.useful_links') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ct-policy-tab" data-toggle="pill" href="#ct-policy" role="tab" aria-controls="ct-policy" aria-selected="false">
                                    {{ trans_choice('custom.field_of_actions', 2) }}
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" id="ct-iisda-sync-tab" data-toggle="pill" href="#ct-iisda-sync" role="tab" aria-controls="ct-links" aria-selected="false">
                                Синхронизация с ИИСДА
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="custom-tabsContent">

                        <div class="tab-pane fade active show" id="ct-general" role="tabpanel" aria-labelledby="ct-general-tab">
                            @php($storeRoute = route($storeRouteName, ['item' => $item->id]))
                            <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <strong>SSEV (ID): </strong> {{ $item->ssev_profile_id ?? '---' }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                            <a href="{{ route('admin.strategic_documents.institutions.index') }}"
                                               class="btn btn-primary">{{ __('custom.cancel') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="ct-history" role="tabpanel" aria-labelledby="ct-history-tab">
                            @php($storeRoute = route($storeRouteName, ['item' => $item->id]))
                            @if ($item->historyNames->count() > 0)
                                <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>{{ __('validation.attributes.name') }}</th>
                                        <th>{{ __('custom.valid_from') }}</th>
                                        <th>{{ __('custom.valid_to') }}</th>
                                        <th>{{ __('custom.actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($item->historyNames as $historyName)
                                        <tr>
                                            <td>{{ $historyName->name }}</td>
                                            <td>{{ displayDate($historyName->valid_from) }}</td>
                                            <td>{{ displayDate($historyName->valid_till) }}</td>
                                            <td>
                                                @can('update', $item)
                                                    <a href="{{ route('admin.strategic_documents.institutions.history-name.edit', $historyName->id) }}"
                                                       class="btn btn-sm btn-info"
                                                       data-toggle="tooltip"
                                                       title="{{ __('custom.edit') }}"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan
{{--                                                @can('delete', $item)--}}
{{--                                                    <a href="{{ route( $editRouteName , $historyName->id) }}"--}}
{{--                                                       class="btn btn-sm btn-info"--}}
{{--                                                       data-toggle="tooltip"--}}
{{--                                                       title="{{ __('custom.edit') }}"--}}
{{--                                                    >--}}
{{--                                                        <i class="fa fa-edit"></i>--}}
{{--                                                    </a>--}}
{{--                                                @endcan--}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="col-md-2">
                                    <a href="{{ route('admin.strategic_documents.institutions.history-name.create', $item->id) }}" class="btn btn-success">
                                        {{ __('custom.add') }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="ct-iisda-sync" role="tabpanel" aria-labelledby="ct-iisda-sync-tab">
                            <div class="col-md-6">
                                <a href="javascript:;" class="btn btn-success" onclick="$(this).hide();$('#sync-started-text').show()">
                                    Стартирай синхронизация ad hoc
                                </a>
                                <p id="sync-started-text" style="display: none">Синхронизацията е стартирана</p>
                            </div>
                        </div>

                        @if($item->id)
                            <div class="tab-pane fade" id="ct-links" role="tabpanel" aria-labelledby="ct-links-tab">
                                <form class="row" action="{{ route('admin.strategic_documents.institutions.link.add') }}" method="post" name="form-link" id="form-link">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
{{--                                    <div class="row">--}}
                                        @include('admin.partial.edit_field_translate', ['field' => 'title', 'required' => true, 'col' => 4, 'translatableFields' => \App\Models\InstitutionLink::translationFieldsProperties()])
{{--                                    </div>--}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" id="link" name="link" placeholder="Връзка"
                                                       class="form-control form-control-sm @error('link'){{ 'is-invalid' }}@enderror"
                                                       value="{{ old('link', '') }}">
                                                @error('link')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button id="save" type="submit" class="btn btn-success">{{ __('custom.add') }}</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row mt-4">
                                    <hr>
                                    <p><b>{{ __('custom.useful_links') }}</b></p>
                                    @if($item->links->count())
                                        <table class="table table-responsive">
                                            <tbody>
                                                @foreach($item->links as $key => $link)
                                                    <tr>
                                                        <td>
                                                            #{{ $key  +1  }} <a class="mr-2" href="{{ $link->link }}" target="_blank">{{ $link->title }}</a>
                                                        </td>
                                                        @can('update', $item)
                                                            <td>
                                                                <a href="javascript:;"
                                                                   class="btn btn-sm btn-danger js-toggle-delete-resource-modal hidden"
                                                                   data-target="#modal-delete-resource"
                                                                   data-resource-id="{{ $link->id }}"
                                                                   data-resource-name="{{ "$link->title" }}"
                                                                   data-resource-delete-url="{{ route('admin.strategic_documents.institutions.link.remove', ['id' => $link->id]) }}"
                                                                   data-toggle="tooltip"
                                                                   title="{{ __('custom.deletion') }}">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p>Няма записи</p>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="form-group row mt-5">
                                        <div class="col-md-6 col-md-offset-3">
                                            <a href="{{ route('admin.strategic_documents.institutions.index') }}"
                                               class="btn btn-primary">{{ __('custom.cancel') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($item->id)
                            <div class="tab-pane fade" id="ct-policy" role="tabpanel" aria-labelledby="ct-policy-tab">
                                <form class="row" action="{{ route('admin.strategic_documents.institutions.policy.store') }}" method="post" name="form-policy" id="form-policy">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="fieldOfAction">{{ trans_choice('custom.field_of_actions', 1) }} <span class="required">*</span> </label>
                                                <select class="form-control form-control-sm select2 @error('fieldOfAction') is-invalid @enderror" name="fieldOfAction">
                                                    <option value="" @if(old('fieldOfAction', '') == '') selected @endif></option>
                                                    @if(isset($fieldOfActions) && $fieldOfActions->count())
                                                        @php($itemFields = $item->fieldsOfAction->count() ? $item->fieldsOfAction->pluck('id')->toArray() : [])
                                                        @foreach($fieldOfActions as $f)
                                                            @if(!sizeof($itemFields) || !in_array($f->id, $itemFields))
                                                                <option value="{{ $f->id }}" @if(old('fieldOfAction', '') == $f->id) selected @endif>{{ $f->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                </select>
                                                @error('fieldOfAction')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <button id="save" type="submit" class="btn btn-success">{{ __('custom.add') }}</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                @if($item->fieldsOfAction->count())
                                    <div class="row">
                                        @foreach($item->fieldsOfAction as $cf)
                                            <div class="col-md-3 col-10"><span class="custom-left-border">{{ $cf->name }}</span></div>
                                                @can('update', $item)
                                                    <div class="col-2">
                                                        <a href="{{ route( 'admin.strategic_documents.institutions.policy.delete' , [$item, $cf]) }}"
                                                           class="btn btn-sm btn-danger"
                                                           data-toggle="tooltip"
                                                           title="{{ __('custom.delete') }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                            <div class="col-12 mb-2"></div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="form-group row mt-5">
                                        <div class="col-md-6 col-md-offset-3">
                                            <a href="{{ route('admin.strategic_documents.institutions.index') }}"
                                               class="btn btn-primary">{{ __('custom.cancel') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        @includeIf('modals.delete-resource', ['resource' => trans_choice('custom.institution_links', 1)])
    </section>
@endsection
