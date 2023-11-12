<section>
    <div class="container-fluid">
        <div class="row breadcrumbs py-1">
            <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    @if (isset($breadcrumbs))
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('custom.home') }}</a></li>
                        @foreach($breadcrumbs['links'] as $key => $link)
                            @if($key < $breadcrumbs['links_count'])
                                <li class="breadcrumb-item"><a href="{{ $link['url'] }}">{{ capitalize($link['name']) }}</a></li>
                            @else
    {{--                                <li class="breadcrumb-item"><a href="{{ $link['url'] }}">{{ capitalize($link['name']) }}</a></li>--}}
                                <li class="breadcrumb-item active" aria-current="page">{{ capitalize($link['name']) }}</li>
                            @endif
                        @endforeach
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('custom.home') }}</a></li>
                    @endif
                </ol>
            </nav>
        </div>
</section>
