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
                                <option value="{{ $user->id }}">{{ implode(' ', [$user->first_name, $user->middle_name, $user->last_name]) }} ({{ $user->email }})</option>
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
                            data-target="#modal-register-advisory-moderator">
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
                        <td>{{ $moderator->user->fullName() }}</td>
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

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
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
    </script>
@endpush
