@if(isset($children) && sizeof($children))
    @foreach($children as $child)
        @if(!$child['selectable'])
            <p class="mb-1 @if($oldBootstrap){{ 'fw-bold' }}@else{{ 'fw-semibold' }}@endif" role="button" {{ $bootstrapDataPrefix }}-toggle="collapse" {{ $bootstrapDataPrefix }}-target="#collapse{{ $child['id'] }}" aria-expanded="false" aria-controls="collapse{{ $child['id'] }}">
                <i class="@if(!$oldBootstrap){{ 'fa-regular' }}@else{{ 'fa' }}@endif fa-circle me-2" style="font-size: 7px;"></i> {{ $child['name'] }} ({{ sizeof($child['children']) }})
            </p>
            <div class="collapse multi-collapse" id="collapse{{ $child['id'] }}">
                <div class="ps-4">
                    @include('partials.institutions_tree_row', ['children' => $child['children']])
                </div>
            </div>
        @else
            <p class="mb-1" @if(sizeof($child['children'])) role="button" {{ $bootstrapDataPrefix }}-toggle="collapse" {{ $bootstrapDataPrefix }}-target="#collapse{{ $child['id'] }}" aria-expanded="false" aria-controls="collapse{{ $child['id'] }}" @endif>
                <label>
                    @if(isset($canSelect) && $canSelect)
                        @if(isset($multipleSelect) && $multipleSelect)
                            <input class="" type="checkbox" name="institutions-item" value="{{ $child['id'] }}">
                        @else
                            <input class="" type="radio" name="institutions-item" value="{{ $child['id'] }}">
                        @endif
                    @else
                        {{ '-' }}
                    @endif
                    {{ $child['name'] }} @if(sizeof($child['children']))({{ sizeof($child['children']) }})@endif
                </label>
                @if(sizeof($child['children']))
                    <div class="collapse multi-collapse" id="collapse{{ $child['id'] }}">
                        <div class="ps-4">
                            @include('partials.institutions_tree_row', ['children' => $child['children']])
                        </div>
                    </div>
                @endif
            </p>
            @endif
            @endforeach
        @endif

