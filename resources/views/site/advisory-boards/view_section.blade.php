@extends('layouts.site', ['fullwidth' => true])

<style>
    #siteLogo, #ok, #ms, .nav-link, .nav-item, #register-link, #login-btn, #search-btn, #back-to-admin, #profile-toggle {
        transition: 0.4s;
    }
</style>

@section('content')
    <div class="row">
        <!-- Left side menu -->
        @include('site.advisory-boards.side_menu_detail_page')

        <!-- Right side -->
        <div class="col-lg-10 py-5 right-side-content">
            <!-- Ръчно направени секции -->
            @if(isset($section))
                <div class="row mb-4 ks-row">
                    <div class="col-md-12">
                        <div class="custom-card p-3">
                            <h3 class="mb-2 fs-4">{{ $section->title }}</h3>

                            <p>{!! $section->body !!}</p>

                            @if(!empty($section->siteFiles) && $section->siteFiles->count() > 0)
                                @foreach($section->siteFiles as $file)
                                    @includeIf('site.partial.file', ['file' => $file])
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
