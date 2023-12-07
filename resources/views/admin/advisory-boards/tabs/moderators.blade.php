@php
    $view_mode ??= false;
@endphp

<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h3>{{ trans_choice('custom.moderators', 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-5">
                <form method="POST" name="ADVISORY_BOARD_ADD_MODERATOR"
                      action="{{ route('admin.advisory-boards.moderator.store', ['item' => $item]) }}">
                    @csrf

                    <div class="row align-items-center">
                        <div class="col-5">
                            <select name="user_id" class="select2 form-control form-control-sm">
                                <option value="">{{ __('custom.username') }}</option>

                                @if(isset($all_users) && $all_users->count() > 0)
                                    @foreach($all_users as $user)
                                        <option value="{{ $user->id }}">{{ $user->username }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-plus mr-3"></i>
                                {{ __('custom.add') . ' ' . trans_choice('custom.moderators', 1) }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('custom.name') }}</th>
                        <th>{{ __('validation.attributes.created_at') }}</th>
                        <th>{{ __('custom.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($moderators) && $moderators->count() > 0)
                        @foreach($moderators as $moderator)
                            <tr>
                                <td>{{ $moderator->id }}</td>
                                <td>{{ $moderator->user->username }}</td>
                                <td>{{ $moderator->created_at }}</td>
                                <td>
                                    @can('update', $item)
                                        <a href="javascript:;"
                                           class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                           data-target="#modal-remove-moderator"
                                           data-resource-id="{{ $moderator->id }}"
                                           data-resource-delete-url="{{ route('admin.advisory-boards.moderator.delete', ['item' => $item, 'moderator' => $moderator]) }}"
                                           data-toggle="tooltip"
                                           title="{{__('custom.delete')}}">
                                            <i class="fa fa-trash"></i>
                                        </a>
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
