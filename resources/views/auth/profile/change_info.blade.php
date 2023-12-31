<form method="POST" action="{{ route('profile.store') }}">
    @csrf

    <input type="hidden" name="is_org" value="{{ $profile['is_org'] }}">
    @if($profile->is_org)
        <div class="row mb-3">
            <label for="org_name" class="col-md-4 col-form-label text-md-end">{{ __('validation.attributes.org_name') }}</label>

            <div class="col-md-6">
                <input id="org_name" type="text" class="form-control @error('org_name') is-invalid @enderror" name="org_name" value="{{ $profile->org_name }}" required autocomplete="org_name">

                @error('org_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    @else
        <div class="row mb-3">
            <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('custom.first_name') }}</label>

            <div class="col-md-6">
                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ $profile->first_name }}" required autocomplete="first_name">

                @error('first_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="middle_name" class="col-md-4 col-form-label text-md-end">{{ __('custom.middle_name') }}</label>

            <div class="col-md-6">
                <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ $profile->middle_name }}" autocomplete="middle_name">

                @error('middle_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('custom.last_name') }}</label>

            <div class="col-md-6">
                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ $profile->last_name }}" autocomplete="last_name">

                @error('last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6 offset-md-4">
            <button type="submit" value="change_name" class="btn btn-primary">
                {{ __('custom.change') }}
            </button>
        </div>
    </div>

    <div class="row mb-3">
        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('custom.email') }}</label>

        <div class="col-md-6">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $profile->email }}" autocomplete="email">

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" value="change_email" class="btn btn-primary">
                {{ __('custom.change') }}
            </button>
        </div>
    </div>
</form>