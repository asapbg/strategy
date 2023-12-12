@php
    /** @var $current_tab - used to determine the fragment for the pagination */
    $current_tab ??= '';
    $archive_category ??= 0;
@endphp

<div id="accordion">
    @if(isset($items) && $items->count() > 0)
        @foreach($items as $key => $item)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title w-100">
                        <div class="row">
                            <div class="col-12">
                                <a data-toggle="collapse" href="#collapse{{$key}}"
                                   aria-expanded="true" class="font-weight-bold">
                                    @if($archive_category == 1)
                                        {{ trans_choice('custom.meetings', 1) . ' ' .  __('custom.from') . ' ' . \Carbon\Carbon::parse($item->next_meeting)->format('d.m.Y') . __('custom.year_short') }}
                                    @endif

                                    @if($archive_category == 2)
                                        {{ __('custom.function') . ' ' .  __('custom.from') . ' ' . $item->created_at->format('Y') . __('custom.year_short') }}
                                    @endif
                                </a>
                            </div>
                        </div>
                    </h4>
                </div>

                @php $show = $key === 0 ? 'show' : '' @endphp
                <div id="collapse{{ $key }}" class="collapse {{ $show }}"
                     data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            @if(!empty($item->translations[0]?->description))
                                <div class="col-6 border-right">
                                    <p>(BG)</p>
                                    {!! $item->translations[0]->description !!}
                                </div>
                            @endif

                            @if($item->translations->count() > 1 && !empty($item->translations[1]?->description))
                                <div class="col-6">
                                    <p>(EN)</p>
                                    {!! $item->translations[1]->description !!}
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(isset($item->files) && $item->files->count() > 0)
                        <div class="row">
                            <div class="col-12">
                                <hr/>
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-12">
                                    <h3>{{ trans_choice('custom.files', 2) }}</h3>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    @include('admin.partial.files_table', ['files' => $item->files, 'item' => $item->advisoryBoard, 'is_archived' => true])
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>

<div class="row">
    <nav aria-label="Page navigation example">
        @if(isset($items) && $items->count() > 0)
            {{ $items->appends(request()->query())->fragment($current_tab)->links() }}
        @endif
    </nav>
</div>
