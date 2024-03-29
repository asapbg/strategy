<div class="px-md-5" style="min-height: 300px;">
    <form method="POST" action="{{ route('profile.store') }}">
        @csrf

        <input type="hidden" name="is_org" value="{{ $profile['is_org'] }}">
        @if($profile->is_org)
            <div class="row mb-3">
                <label for="org_name" class="col-md-2 col-form-label">{{ __('validation.attributes.org_name') }}</label>

                <div class="col-md-10">
                    <input id="org_name" type="text" class="form-control @error('org_name') is-invalid @enderror" name="org_name" value="{{ $profile->org_name }}" autocomplete="off">

                    @error('org_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        @else

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
        @endif

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

@php($pendingRequest = auth()->user()->pendingChangeRequests)
@if($pendingRequest)
    <div class="px-md-5 mb-5">
        <h2 class="mb-4">Вашите заявки за промяна</h2>
        <div class="row pris-row pb-2 mb-2">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th colspan="2">Вашите заявки за промяна</th>
                    <th >Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pendingRequest as $row)
                    @php($data = json_decode($row->data, true))
                    @if($data)
                        <tr>
                            <td>{{ displayDateTime($row->created_at) }}</td>
                            <td>
                                @foreach($data as $k => $v)
                                    @if(!$loop->first){{ ' | ' }}@endif{{ $k }}: {{ $v }}
                                @endforeach
                            </td>
                            <td></td>
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
