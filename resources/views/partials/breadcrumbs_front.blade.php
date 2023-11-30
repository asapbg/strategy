<section @if(!isset($pageTitle)) style="padding-top: 126px;" @endif>
    @if (isset($breadcrumbs) && !in_array(request()->route()->getName(), ['home']) )
    <div class="container-fluid">
        <div class="row breadcrumbs py-1">
            <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('custom.home') }}</a></li>
                    @foreach($breadcrumbs['links'] as $key => $link)
                        @if(isset($link['url']) && !empty($link['url']))
                            <li class="breadcrumb-item @if($loop->last) active @endif"><a href="{{ $link['url'] }}">{{ capitalize($link['name']) }}</a></li>
                        @else
                            <li class="breadcrumb-item" aria-current="page">{{ capitalize($link['name']) }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    @endif
</section>

