@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row pt-5">
        <div class="col-md-12">
            <h2 class="mb-2">{{ __('site.search_result_title_section', ['text' => $search, 'section' => $sectionName]) }} ({{ $items->total() }}):</h2>
        </div>
    </div>

    <div class="search-results-wrapper pt-3 pb-5">
        <div class="row mb-5 mt-4">
            <div class="col-12">
                <h3 class="custom-left-border">{{ $sectionName }}
                </h3>
                <hr class="custom-hr">
            </div>
            @if($items->count())
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
                            @php($url = route('pris.view', ['category' => Str::slug($item->act_type_name), 'id' => $item->id]))
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
            @endif
        </div>
        <div class="row">
            @if(isset($items) && $items->count() > 0)
                {{ $items->onEachSide(0)->appends(request()->query())->links() }}
            @endif
        </div>
    </div>

@endsection
