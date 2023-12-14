<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ __('custom.list_with') . ' ' . __('custom.with') . ' ' . Str::lower(trans_choice('custom.member', 2)) }}</h3>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-create-member">
                    <i class="fa fa-plus mr-3"></i>
                    {{ __('custom.add') . ' ' . trans_choice('custom.member', 1) }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('custom.first_name') }}</th>
                        <th>{{ __('custom.type') }}</th>
                        <th>{{ __('forms.job') }}</th>
                        <th>{{ __('custom.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($item->members) && $item->members->count() > 0)
                        @foreach($item->members as $member)
                            <tr>
                                <td>{{ $member->id }}</td>
                                <td>{{ $member->member_name }}</td>
                                <td>{{ trans_choice('custom.' . Str::lower(\App\Enums\AdvisoryTypeEnum::tryFrom($member->advisory_type_id)->name), 1) }}</td>
                                <td>{{ $member->advisoryChairmanType?->name }}</td>
                                <td class="text-center">
                                    @can('update', $item)
                                        <button type="button"
                                                class="btn btn-sm btn-warning"
                                                data-toggle="modal"
                                                data-target="#modal-edit-member"
                                                title="{{ __('custom.edit') }}"
                                                onclick="loadMemberData('{{ route('admin.advisory-boards.members.edit', $member) }}');"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endcan

                                    @can('delete', $item)
                                        @if(!$member->deleted_at)
                                            <a href="javascript:;"
                                               class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                               data-target="#modal-delete-resource"
                                               data-resource-id="{{ $member->id }}"
                                               data-resource-delete-url="{{ route('admin.advisory-boards.members.delete', $member) }}"
                                               data-toggle="tooltip"
                                               title="{{__('custom.delete')}}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
                                    @endcan

                                    @can('restore', $item)
                                        @if($member->deleted_at)
                                            <a href="javascript:;"
                                               class="btn btn-sm btn-success js-toggle-restore-resource-modal"
                                               data-target="#modal-restore-resource"
                                               data-resource-id="{{ $member->id }}"
                                               data-resource-restore-url="{{ route('admin.advisory-boards.members.restore', $member) }}"
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
