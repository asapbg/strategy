<div class="px-md-5 mb-5" style="min-height: 300px;">
    <form method="POST" action="{{ route('profile.store') }}">
        @csrf

        <input type="hidden" name="is_org" value="{{ $profile['is_org'] }}">

        <div class="row pris-row pb-2 mb-2">
            <label for="notification_email" class="col-md-3 pris-left-column"><i class="fa-solid fa-envelope main-color me-1"></i> {{ __('custom.notification_email') }}</label>
            <div class="col-md-9 pris-left-column">
                <input id="notification_email" type="text" class="form-control @error('notification_email') is-invalid @enderror" name="notification_email" value="{{ old('notification_email', $profile->notification_email) }}" autocomplete="email">
                @error('notification_email')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary" name="edit" value="1">
                    {{ __('custom.save') }}
                </button>
            </div>
        </div>

        @if($profile->is_org)
            <div class="row pris-row pb-2 mb-2">
                <label for="org_name" class="col-md-3 pris-left-column"><i class="fa-solid fa-building main-color me-1"></i> {{ __('validation.attributes.org_name') }}</label>
                <div class="col-md-9 pris-left-column">
                    <input id="org_name" type="text" class="form-control @error('org_name') is-invalid @enderror" name="org_name" value="{{ old('org_name', $profile->org_name) }}" autocomplete="off">
                    @error('org_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        @endif

        <div class="row pris-row pb-2 mb-2">
            <label for="first_name" class="col-md-3 pris-left-column"><i class="fa-solid fa-user main-color me-1"></i> {{ __('custom.first_name') }}</label>
            <div class="col-md-9 pris-left-column">
                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $profile->first_name) }}" autocomplete="off">
                @error('first_name')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <label for="middle_name" class="col-md-3 pris-left-column"><i class="fa-solid fa-user main-color me-1"></i> {{ __('custom.middle_name') }}</label>
            <div class="col-md-9 pris-left-column">
                <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ old('middle_name', $profile->middle_name) }}" autocomplete="off">
                @error('middle_name')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <label for="last_name" class="col-md-3 pris-left-column"><i class="fa-solid fa-user main-color me-1"></i> {{ __('custom.last_name') }}</label>
            <div class="col-md-9 pris-left-column">
                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $profile->last_name) }}" autocomplete="off">
                @error('last_name')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <label for="email" class="col-md-3 pris-left-column"><i class="fa-solid fa-envelope main-color me-1"></i> {{ __('custom.email') }}</label>
            <div class="col-md-9 pris-left-column">
                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $profile->email) }}" autocomplete="email">
                @error('email')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>
        </div>

{{--        <div class="row mb-0">--}}
{{--            <div class="col-md-10 offset-md-4">--}}
{{--                <button type="submit" value="change_email" class="btn btn-primary">--}}
{{--                    {{ __('custom.change') }}--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="row mb-0">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    {{ __('site.send_change_request') }}
                </button>
            </div>
        </div>
    </form>
</div>

@php($changeRequest = auth()->user()->changeRequests)
@if($changeRequest->count())
    <div class="px-md-5 mb-5">
        <h2 class="mb-4">{{ __('site.your_change_requests') }}</h2>
        <div class="row pris-row pb-2 mb-2">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{ __('custom.sended_at') }}</th>
                    <th >{{ __('custom.status') }}</th>
                    <th >{{ __('custom.data_for_change') }}</th>
                    <th >{{ __('custom.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($changeRequest as $row)
                    @php($data = json_decode($row->data, true))
                    @if($data)
                        <tr>
                            <td>{{ displayDateTime($row->created_at) }}</td>
                            <td>
                                {{ __('custom.user_change_request_status.'.$row->status) }}
                                @if($row->status != \App\Models\UserChangeRequest::PENDING)
                                <br><span class="main-color">({{ displayDateTime($row->updated_at) }})</span>
                                @endif
                            </td>
                            <td>
                                @foreach($data as $k => $v)
                                    @if(!$loop->first)<br>@endif{{ __('site.'.$k) }}: {{ $v }}
                                @endforeach
                            </td>
                            <td>
                                @can('withdrew', $row)
                                    <button class="btn btn-sm btn-danger withdrew-request" data-id="{{ $row->id }}">{{__('site.withdrew') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="px-md-5 mb-3">
    <h2 class="mb-4">{{ __('site.change_password') }}</h2>
    <form method="POST" action="{{ route('profile.store.password') }}">
        @csrf
        <div class="row pris-row pb-2 mb-2">
            <div class="col-12">
                <p class="main-color fw-bold">({{ __('site.password_format') }})</p>
            </div>
            <label for="password" class="col-md-3 pris-left-column"><i class="fa-solid fa-lock main-color me-1"></i> {{ __('validation.attributes.password') }}</label>
            <div class="col-md-9 pris-left-column">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="" autocomplete="off">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="row pris-row pb-2 mb-2">
            <label for="password_confirmation" class="col-md-3 pris-left-column"><i class="fa-solid fa-lock main-color me-1"></i> {{ __('validation.attributes.password_confirm') }}</label>
            <div class="col-md-9 pris-left-column">
                <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="" autocomplete="off">
                @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-0">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    {{ __('site.change_password') }}
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('.withdrew-request').on('click', function () {
                let itemId = $(this).data('id');
                new MyModal({
                    title: @json(__('site.withdrew_change_request_title')),
                    footer: '<button type="button" id="confirm-withdrew" class="btn btn-sm btn-success ms-3">' + @json(__('custom.continue')) + '</button>' +
                        '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="' + @json(__('custom.cancel')) + '">' + @json(__('custom.cancel')) + '</button>',
                    body: '<div class="alert alert-danger">' + @json(__('site.withdrew_change_request_title_confirm_text'). '?') + '</div>' +
                        '<form id="withdrew_request" method="get" action="{{ route('profile.withdrew') }}"><input type="hidden" name="change_id" value="' + itemId + '" ></form>',
                });

                $('#confirm-withdrew').on('click', function (){
                    $('#withdrew_request').submit();
                });
            });
        });
    </script>
@endpush
