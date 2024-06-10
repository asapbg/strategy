@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">

        @include('site.public_profiles.user_menu')
        <div class="col-lg-10 right-side-content py-2">
            Съдържание
        </div>
    </div>
@endsection

