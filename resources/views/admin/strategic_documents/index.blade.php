@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('admin.partial.filter_form')

            <div class="card">
                <div class="card-body table-responsive">

                    <div class="mb-3">
                        <a href="{{ route('admin.strategic_documents.edit') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('custom.add') }} {{ trans_choice('custom.strategic_documents', 1) }}
                        </a>
                    </div>

                    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('validation.attributes.title') }}</th>
                            <th>{{ trans_choice('custom.strategic_document_types', 1) }}</th>
                            <th>{{ trans_choice('custom.strategic_document_levels', 1) }}</th>
                            <th>{{ trans_choice('custom.authority_accepting_strategics', 1) }}</th>
                            <th>{{ __('custom.published') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->documentType?->name }}</td>
                                    <td>{{ __('custom.strategic_document.dropdown.' . \App\Enums\InstitutionCategoryLevelEnum::keyByValue($item->strategic_document_level_id)) }}</td>
                                    <td>{{ $item->acceptActInstitution?->name }}</td>
                                    <td>@if($item->active) <i class="fas fa-check text-success"></i> @else <i class="fas fa-minus text-danger"></i> @endif</td>
                                    <td class="text-center" style="width: 50px; white-space: nowrap;">
                                        @can('update', $item)
                                            <a href="{{ route( $editRouteName , [$item->id]) }}"
                                               class="btn btn-sm btn-info mr-2 btn-action"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan
                                        @if($item->active)
                                            @can('update', $item)
                                                 <a href="{{ route($unPublishRouteName, ['id' => $item->id, 'stay' => false]) }}"
                                                   class="btn btn-sm btn-secondary mr-2 btn-action"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.unpublish') }}">
                                                    <i class="fa fa-eye-slash"></i>
                                                </a>
                                            @endcan
                                        @else
                                            @can('update', $item)
                                                <a href="{{ route($publishRouteName, ['id' => $item->id, 'stay' => false]) }}"
                                                   class="btn btn-sm btn-success mr-2 btn-action"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.publish') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endcan
                                        @endif
                                        @if(is_null($item->deleted_at))
                                            @can('delete', $item)
                                                <a href="javascript:;"
                                                   class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $item->id }}"
                                                   data-resource-name="{{ $item->id }} ({{ $item->title }})"
                                                   data-resource-delete-url="{{ route('admin.strategic_documents.delete', $item) }}"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.delete') }}"><i class="fas fa-trash"></i>
                                                </a>
                                            @endcan
                                        @else
                                            @can('restore', $item)
                                                    <a href="{{ route('admin.strategic_documents.restore', $item) }}"
                                                       class="btn btn-sm btn-success"
                                                       data-toggle="tooltip"
                                                       title="{{ __('custom.restore') }}"><i class="fas fa-undo"></i>
                                                    </a>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

                @includeIf('modals.delete-resource', ['resource' => $title_singular])
                <div class="card-footer mt-2">
                    @if(isset($items) && $items->count() > 0)
                        {{ $items->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </section>
    <style>


    </style>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function (){
                let centralLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value; ?>';
                let areaLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::AREA->value; ?>';
                let municipalityLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::MUNICIPAL->value; ?>';

                let fieldOfActions = $('#fieldOfActions');
                let areas = $('#areas');
                let municipalities = $('#municipalities');
                let level = $('#category');

                function categoriesControl(){
                    let levelVal = level.val();
                    // console.log(level.val(), centralLevel, levelVals.indexOf(centralLevel) != -1 || !levelVals.length);
                    if(levelVal == centralLevel || !levelVal){
                        fieldOfActions.parent().removeClass('d-none');
                    } else{
                        fieldOfActions.parent().addClass('d-none');
                        fieldOfActions.val('');
                    }
                    // console.log(level.val(), areaLevel, levelVals.indexOf(areaLevel) != -1 || !levelVals.length);
                    if(levelVal == areaLevel ||!levelVal){
                        areas.parent().removeClass('d-none');
                    } else{
                        areas.parent().addClass('d-none');
                        areas.val('');
                    }
                    // console.log(level.val(), municipalityLevel, levelVals.indexOf(municipalityLevel) != -1 || !levelVals.length);
                    if(levelVal == municipalityLevel || !levelVal){
                        municipalities.parent().removeClass('d-none');
                    } else{
                        municipalities.parent().addClass('d-none');
                        municipalities.val('');
                    }
                }

                $(document).on('change', level, function (){
                    categoriesControl();
                });
                categoriesControl();
            });
        </script>
    @endpush
@endsection


