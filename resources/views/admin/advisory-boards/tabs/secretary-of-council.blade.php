@php
    $view_mode ??= false;
@endphp

<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ __('custom.list_with') . ' ' . __('custom.with') . ' ' . Str::lower(trans_choice('custom.member', 2)) }}</h3>
            </div>

            <div class="col-auto">
                @if(!$view_mode)
                    <button type="button" class="btn btn-success" data-toggle="modal"
                            data-target="#modal-create-secretary-of-council">
                        <i class="fa fa-plus mr-3"></i>
                        {{ __('custom.add') . ' ' . trans_choice('custom.secretary', 1) }}
                    </button>
                @else
                    <a href="{{ route('admin.advisory-boards.edit', $item) . '#secretary-of-council' }}"
                       class="btn btn-warning">{{ __('custom.editing') }}</a>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('custom.first_name') }}</th>
                        <th>{{ __('forms.job') }}</th>
                        <th>{{ __('custom.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($secretaries_council) && $secretaries_council->count() > 0)
                        @foreach($secretaries_council as $secretary)
                            <tr>
                                <td>{{ $secretary->id }}</td>
                                <td>{{ $secretary->name }}</td>
                                <td>{{ $secretary->job }}</td>
                                <td class="text-center">
                                    @can('update', $item)
                                        <button type="button"
                                                class="btn btn-sm btn-warning"
                                                data-toggle="modal"
                                                data-target="#modal-edit-secretary-council"
                                                title="{{ __('custom.edit') }}"
                                                onclick="loadSecretaryCouncilData('{{ route('admin.advisory-boards.secretary-council.edit', ['item' => $item, 'secretary' => $secretary]) }}');"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endcan

                                    @can('delete', $item)
                                        @if(!$secretary->deleted_at)
                                            <a href="javascript:;"
                                               class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                               data-target="#modal-delete-secretary-council"
                                               data-resource-id="{{ $secretary->id }}"
                                               data-resource-delete-url="{{ route('admin.advisory-boards.secretary-council.delete', ['item' => $item, 'secretary' => $secretary]) }}"
                                               data-toggle="tooltip"
                                               title="{{__('custom.delete')}}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
                                    @endcan

                                    @can('restore', $item)
                                        @if($secretary->deleted_at)
                                            <a href="javascript:;"
                                               class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                               data-target="#modal-restore-secretary-council"
                                               data-resource-id="{{ $secretary->id }}"
                                               data-resource-restore-url="{{ route('admin.advisory-boards.secretary-council.restore', ['item' => $item, 'secretary' => $secretary]) }}"
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
            </div>
        </div>
    </div>
</div>
