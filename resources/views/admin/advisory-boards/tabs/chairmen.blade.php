<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.chairmen_list', 2) }}</h3>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-create-chairman">
                    <i class="fa fa-plus mr-3"></i>
                    {{ __('custom.add') . ' ' . trans_choice('custom.chairmen', 1) }}
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
                        <th>{{ trans_choice('custom.representatives_from', 1) }}</th>
                        <th>{{ __('custom.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($members) && $members->count() > 0)
                        @foreach($members as $member)
                            <tr>
                                <td>{{ $member->id }}</td>
                                <td>{{ $member->name }}</td>
                                <td>{{ trans_choice('custom.' . Str::lower(\App\Enums\AdvisoryTypeEnum::tryFrom($member->advisory_type_id)->name), 1) }}</td>
                                <td>{{ $member->advisoryChairmanType?->name }}</td>
                                <td>{{ $member->consultationLevel?->name }}</td>
                                <td class="text-center">
                                    {{--                                    @can('view', $item)--}}
                                    {{--                                        <a href="{{ route('admin.advisory-boards.view', $item) }}"--}}
                                    {{--                                           class="btn btn-sm btn-warning mr-2"--}}
                                    {{--                                           data-toggle="tooltip"--}}
                                    {{--                                           title="{{ __('custom.preview') }}">--}}
                                    {{--                                            <i class="fa fa-eye"></i>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    @endcan--}}

                                    @can('update', $item)
                                        <button type="button"
                                                class="btn btn-warning"
                                                data-toggle="modal"
                                                data-target="#modal-edit-chairman"
                                                title="{{ __('custom.edit') }}"
                                                onclick="loadMemberData('{{ route('admin.advisory-boards.members.edit', $member) }}');"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endcan

                                    {{--                                    @can('delete', $item)--}}
                                    {{--                                        @if(!$item->deleted_at)--}}
                                    {{--                                            <a href="javascript:;"--}}
                                    {{--                                               class="btn btn-sm btn-danger js-toggle-delete-resource-modal"--}}
                                    {{--                                               data-target="#modal-delete-resource"--}}
                                    {{--                                               data-resource-id="{{ $item->id }}"--}}
                                    {{--                                               data-resource-delete-url="{{ route('admin.advisory-boards.delete', $item) }}"--}}
                                    {{--                                               data-toggle="tooltip"--}}
                                    {{--                                               title="{{__('custom.delete')}}">--}}
                                    {{--                                                <i class="fa fa-trash"></i>--}}
                                    {{--                                            </a>--}}
                                    {{--                                        @endif--}}
                                    {{--                                    @endcan--}}

                                    {{--                                    @can('restore', $item)--}}
                                    {{--                                        @if($item->deleted_at)--}}
                                    {{--                                            <a href="javascript:;"--}}
                                    {{--                                               class="btn btn-sm btn-success js-toggle-restore-resource-modal"--}}
                                    {{--                                               data-target="#modal-restore-resource"--}}
                                    {{--                                               data-resource-id="{{ $item->id }}"--}}
                                    {{--                                               data-resource-restore-url="{{ route('admin.advisory-boards.restore', $item) }}"--}}
                                    {{--                                               data-toggle="tooltip"--}}
                                    {{--                                               title="{{__('custom.restore')}}">--}}
                                    {{--                                                <i class="fa fa-plus"></i>--}}
                                    {{--                                            </a>--}}
                                    {{--                                        @endif--}}
                                    {{--                                    @endcan--}}
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
