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
        <div class="row mb-4 ks-row">
            <div class="col-md-12">
                <div class="custom-card p-3">
                    <h3 class="mb-2 fs-4">{{ __('custom.work_program') }}</h3>

                    @foreach($items as $program)
                        <p class="fw-bold mt-3">Година: <span class="fw-normal">{{ \Carbon\Carbon::parse($program->working_year)->format('Y') }}</span></p>
                        <hr>
                        <p>
                            {!! $program->description !!}
                        </p>
                        @if($program->siteFiles->count())
                            @foreach($program->siteFiles as $file)
                                @includeIf('site.partial.file', ['file' => $file, 'no_second_active_status' => true])
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if(isset($items) && $items->count() > 0)
        {{ $items->onEachSide(0)->appends(request()->query())->links() }}
    @endif
</div>
