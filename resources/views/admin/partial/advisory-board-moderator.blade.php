<div class="row mt-3 justify-content-between align-items-center">
    <div class="col-md-2">
        <div class="row align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.moderators', 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-10">
        <form method="POST" name="ADVISORY_BOARD_ADD_MODERATOR" id="submit_moderator"
              action="{{ route('admin.advisory-boards.moderator.store', ['item' => $item]) }}">
            @csrf

            <div class="row align-items-center">
                <div class="col-md-5">
                    <select name="user_id" class="select2 form-control form-control-sm" id="user_id">
                        <option value="">{{ __('custom.username') }}</option>

                        @if(isset($all_users) && $all_users->count() > 0)
                            @foreach($all_users as $user)
                                <option value="{{ $user->id }}">{{ $user->fullInformation }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-success" id="add_moderator">
                        <i class="fa fa-plus mr-3"></i>
                        {{ __('custom.add') . ' ' . trans_choice('custom.moderators', 1) }}
                    </button>
                </div>

                <div class="col-md-4">
                    <button type="button" class="btn btn-success" data-toggle="modal"
                            onclick="editAdvisoryModerator();"
                            data-toggle="modal"
                            data-target="#modal-edit-advisory-moderator">
                        <i class="fa fa-plus mr-3"></i>
                        {{ __('custom.register') . ' ' . __('custom.of') . ' ' . trans_choice('custom.users', 1) }}
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
                        <td>
                            {{ $moderator->user->fullName() }}

                            @if($moderator->user?->job)
                                {{ __('custom.with') . ' ' . Str::lower(__('validation.attributes.job')) . ' ' . $moderator->user?->job }}
                            @endif

                            @if($moderator->user?->institution)
                                {{ __('custom.from') . ' ' . Str::lower(__('validation.attributes.institution')) . ' ' . $moderator->user->institution->name }}
                            @endif

                            @if($moderator->user?->unit)
                                {{ __('custom.from') . ' ' . Str::lower(__('validation.attributes.unit')) . ' ' . $moderator->user->unit }}
                            @endif

                            @if(!empty($moderator->user?->email) || !empty($moderator->user?->phone))
                                | {{ __('custom.for') . ' ' . Str::lower(trans_choice('custom.contacts', 2)) }}:

                                @if(!empty($moderator->user?->email))
                                    <a href="mailto:{{ $moderator->user?->email }}" class="text-decoration-none">
                                        <i class="fa-solid fa-envelope ms-1"></i>
                                        {{ $moderator->user?->email }}
                                    </a>
                                @endif

                                @if(!empty($moderator->user?->phone))
                                    <a href="#" class="text-decoration-none">
                                        <i class="fa-solid fa-phone ms-1"></i>
                                        {{ $moderator->user?->phone }}
                                    </a>
                                @endif
                            @endif
                        </td>
                        <td>{{ $moderator->created_at }}</td>
                        <td>
                            @can('update', $item)
                                <a href="javascript:;"
                                   class="btn btn-sm btn-primary"
                                   onclick="editAdvisoryModerator('{{ $moderator->user?->id }}');"
                                   data-toggle="modal"
                                   data-target="#modal-edit-advisory-moderator"
                                   title="{{__('custom.edit')}}">
                                    <i class="fa fa-user-edit"></i>
                                </a>

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

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('#institution_id').select2();

            $('#submit_moderator').submit(function (e){
                if(!(parseInt($('#user_id').val()) > 0)){
                    new MyModal({
                        title: '{{ __('custom.error') }}',
                        body: '<p>{{ __('custom.select_moderator_user') }}</p>',
                        footer: '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="{{ __('custom.close') }}">{{ __('custom.close') }}</button>',
                    });
                    return false;
                }
            });
        });

        function editAdvisoryModerator(user_id = null) {
            const modal = $('#modal-edit-advisory-moderator');
            const create_route = @json(route('admin.advisory-boards.moderator.register', ['item' => $item]));
            const update_route = @json(route('admin.advisory-boards.moderator.update', ['item' => $item, 'user' => '_user']));

            if (!user_id) {
                // replace :user with user.id in form action
                modal.find('form').attr('action', create_route);

                return;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
                },
                url: @json(route('admin.ajax-get-user')) + '?user_id=' + user_id,
                type: 'GET',
                dataType: 'json',
                success: function (result) {
                    if (!result.user) {
                        return;
                    }

                    modal.find('#first_name').val(result.user.first_name);
                    modal.find('#middle_name').val(result.user.middle_name);
                    modal.find('#last_name').val(result.user.last_name);
                    modal.find('#email').val(result.user.email);
                    modal.find('#phone').val(result.user.phone);
                    modal.find('#job').val(result.user.job);
                    modal.find('#institution_id').val(result.user.institution_id).trigger('change');
                    modal.find('#unit').val(result.user.unit);

                    // replace :user with user.id in form action
                    modal.find('form').attr('action', update_route.replace('_user', result.user.id));
                },
            });
        }
    </script>
@endpush
