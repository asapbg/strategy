@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row pt-5">
        <div class="col-md-12">
            <h2 class="mb-2">{{ __('site.search_result_title', ['text' => $search]) }} ({{ $totalResults->sum }}):</h2>
        </div>
    </div>

    <div class="search-results-wrapper pt-3 pb-5">
        @if(isset($totalResults) && $totalResults->sum)
            @foreach(['adv_board', 'sd', 'li', 'pris', 'publications', 'news', 'ogp_news', 'pc'] as $section)
                @php($items = ${$section.'_items'} ?? null)
                @switch($section)
                    @case('pc')
                        @php($typeTitle = trans_choice('custom.public_consultations', 2))
                        @break
                    @case('adv_board')
                        @php($typeTitle = trans_choice('custom.advisory_boards', 2))
                        @break
                    @case('sd')
                        @php($typeTitle = trans_choice('custom.strategic_documents', 2))
                        @break
                    @case('li')
                        @php($typeTitle = trans_choice('custom.legislative_initiatives', 2))
                        @break
                    @case('publications')
                        @php($typeTitle = trans_choice('custom.publications', 2))
                        @break
                    @case('news')
                        @php($typeTitle = trans_choice('custom.news', 2))
                        @break
                    @case('ogp_news')
                        @php($typeTitle = trans_choice('custom.ogp_news', 2))
                        @break
                    @case('pris')
                        @php($typeTitle = trans_choice('custom.pris', 2))
                        @break
                    @default
                        @php($typeTitle = '')
                @endswitch

                @if(sizeof($items))
                    <div class="row mb-5 @if($loop->first) mt-4 @endif">
                        <div class="col-12">
                            <h3 class="custom-left-border">{{ $typeTitle }} {{ '(' }} {{ $totalResults->{$section.'_cnt'} }}
                                @if( $totalResults->{$section.'_cnt'} > $defaultPaginate)
                                    <a href="{{ route('search.section').'?section='.$section.'&search='.$search }}" target="_blank" class="fs-18 fw-normal"> {{ __('custom.see_all') }}</a>
                                @endif
                                {{ ')' }}
                            </h3>
                            <hr class="custom-hr">
                        </div>
                        @foreach($items as $item)
                            @switch($section)
                                @case('pc')
                                    @php($url = route('public_consultation.view', $item->id))
                                    @break
                                @case('adv_board')
                                    @php($url = route('advisory-boards.view', $item->id))
                                    @break
                                @case('sd')
                                    @php($url = route('strategy-document.view', $item->id))
                                    @break
                                @case('li')
                                    @php($url = route('legislative_initiatives.view', $item->id))
                                    @break
                                @case('publications')
                                @case('news')
                                    @php($url = route('library.details', ['type' => \App\Enums\PublicationTypesEnum::TYPE_LIBRARY->value, 'id' => $item->id]))
                                    @break
                                @case('ogp_news')
                                    @php($url = route('ogp.news.details', $item->id))
                                    @break
                                @case('pris')
                                    @php($url = $item->in_archive ? route('pris.archive.view', ['category' => Str::slug($item->act_type_name), 'id' => $item->id]) : route('pris.view', ['category' => Str::slug($item->act_type_name), 'id' => $item->id]))
                                    @break
                                @default
                                    @php($url = '#')
                            @endswitch
                            @php($indx = ($loop->iteration < 10 ? '0' : '').$loop->iteration)
                            <div class="col-md-4 mb-3">
                                <div class="result-content">
                                    <div class="result-number-wrapper">
                                        <span class="result-number fs-1 main-color">{{ $indx }}</span>
                                    </div>
                                    <div class="result-heading-wrapper">
                                        <h3 class="fs-5">{{ $item->name }}</h3>
                                    </div>
                                    <div class="result-heading-wrapper">
                                        <a href="{{ $url }}" class="text-decoration-none">{{ __('site.learn_more') }} <i class="fas fa-solid fa-arrow-right main-color mx-2"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @else
            <div class="row">
                <div class="col-md-4 mb-3">
                    <p class="main-color">{{ __('site.search_no_result') }}</p>
                </div>
            </div>
        @endif
{{--        @if($totalResults->sum > 0 && $totalResults > $defaultPaginate)--}}
{{--            @php($totalPages = ceil($totalResults / $defaultPaginate))--}}
{{--            <div class="row">--}}
{{--                <nav aria-label="Page navigation example" >--}}
{{--                    <ul class="pagination m-0" style="flex-wrap: wrap;">--}}
{{--                        @if($page > 1)--}}
{{--                            <li class="page-item">--}}
{{--                                <a class="page-link" href="{{ route('search').'?search='.$search }}" aria-label="{{ __('pagination.previous') }}">--}}
{{--                                    <span aria-hidden="true">«</span>--}}
{{--                                    <span class="sr-only">{{ __('pagination.previous') }}</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endif--}}
{{--                        @for($i = 1; $i <= $totalPages; $i++)--}}
{{--                                <li class="page-item @if($i == $page) active @endif"><a class="page-link" href="{{ route('search').'?search='.$search.'&page='.$i }}">{{ $i }}</a></li>--}}
{{--                        @endfor--}}
{{--                        @if($page < $totalPages)--}}
{{--                            <li class="page-item">--}}
{{--                                <a class="page-link" href="{{ route('search').'?search='.$search.'&page='.$totalPages }}" aria-label="{{ __('pagination.next') }}">--}}
{{--                                    <span aria-hidden="true">»</span>--}}
{{--                                    <span class="sr-only">{{ __('pagination.next') }}</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endif--}}
{{--                    </ul>--}}
{{--                </nav>--}}
{{--            </div>--}}
{{--        @endif--}}
    </div>

@endsection
