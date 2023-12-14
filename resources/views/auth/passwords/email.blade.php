@extends('layouts.site')

@section('pageTitle', __('auth.forgot_password'))

@section('content')
<section id="login">
    <div class="container">
        <div class="row align-items-center" style="min-height: 300px;">
            <div class="col-md-6 offset-md-3">
                <div class="custom-card p-3">
                    <div class="login-box">
                        {{--        <div class="login-logo">--}}
                        {{--            <a href="/"><b>{{env('APP_NAME')}}</b></a>--}}
                        {{--        </div>--}}
                        <div class="login-box-body">
                            @foreach(['success', 'warning', 'danger', 'info'] as $msgType)
                            @if(Session::has($msgType))
                            <div class="alert alert-{{$msgType}} mt-1" role="alert">{{Session::get($msgType)}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            @endforeach
    
                            <div class="card-body">
                                @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                                @endif
    
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
    
                                    <div class="form-group row">
                                        <label for="email"
                                            class="col-md-12 col-form-label text-md-right">{{ __('auth.email') }}</label>
    
                                        <div class="col-md-12">
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" autocomplete="off" autofocus>
    
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary w-auto mt-3">
                                            {{ __('auth.sent_reset_link') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
