@php
    /** @var $current_tab - used to determine the fragment for the pagination */
    $current_tab ??= '';
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
                                    {{ __('custom.function') . ' ' .  __('custom.from') . ' ' . $item->created_at->format('Y') . __('custom.year_short') }}
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
                            <div class="col-6 border-right">
                                <p>(BG)</p>
                                {!! $item->translations[0]->description !!}
                            </div>

                            <div class="col-6">
                                <p>(EN)</p>
                                @if($item->translations->count() > 1)
                                    {!! $item->translations[1]->description !!}
                                @endif
                            </div>
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