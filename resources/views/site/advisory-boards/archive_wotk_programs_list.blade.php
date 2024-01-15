@include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer'])

<div class="row mb-2">
    <div class="col-md-6 mt-2">
        <div class="info-consul text-start">
            <p class="fw-600">
                {{ trans_choice('custom.total_pagination_result', $items->count(), ['number' => $items->total()]) }}
            </p>
        </div>
    </div>
    @include('site.partial.paginate_filter', ['ajaxContainer' => '#listContainer'])
</div>

<div class="row">
    @if(isset($items) && $items->count() > 0)
        @php($year = null)
        @php($newYear = false)
        @foreach($items as $program)
            @php($newYear = ($loop->first || \Carbon\Carbon::parse($program->working_year)->format('Y') != $year) ? true : false)
            @php($year = ($loop->first || \Carbon\Carbon::parse($program->working_year)->format('Y') != $year) ? \Carbon\Carbon::parse($program->working_year)->format('Y') : $year)
            @if($newYear && !$loop->first)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($newYear)
                <div class="row p-1">
                    <div class="accordion" id="accordionExample">
                        <div class="card custom-card">
                            <div class="card-header" id="heading{{ $year }}">
                                <h2 class="mb-0">
                                    <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start @if(!$loop->first) collapsed @endif" type="button" data-toggle="collapse" data-target="#collapse{{ $year}}" aria-expanded="@if($loop->first){{ 'true' }}@else{{ 'false' }}@endif" aria-controls="collapse{{ $year }}">
                                        <i class="me-1 bi bi-calendar fs-18"></i>  {{ __('custom.work_program') }} - {{ $year }} {{ __('custom.year_short') }}
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse{{ $year }}" class="collapse @if($loop->first) show @endif" aria-labelledby="heading{{ $year }}" data-parent="#accordionExample">
                                <div class="card-body">
            @endif

            <p class="fw-bold mt-3">{{ __('custom.year') }}: <span class="fw-normal">{{ \Carbon\Carbon::parse($program->working_year)->format('Y') }}</span></p>
            <p>
                {!! $program->description !!}
            </p>
            <hr>
            @if($program->siteFiles->count())
                @foreach($program->siteFiles as $file)
                    @includeIf('site.partial.file', ['file' => $file, 'no_second_active_status' => true])
                @endforeach
            @endif

            @if($loop->last)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
   @endif
</div>

<div class="row">
    @if(isset($items) && $items->count() > 0)
        {{ $items->onEachSide(0)->appends(request()->query())->links() }}
    @endif
</div>
