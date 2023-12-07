<form id="institutionTree" class="px-2">
    @php($bootstrapDataPrefix = $oldBootstrap ? 'data' : 'data-bs')
    @if(isset($institutions) && sizeof($institutions))
            @foreach($institutions as $institution)
            @if(!$institution['selectable'])
                <p class="mb-1 @if($oldBootstrap){{ 'fw-bold' }}@else{{ 'fw-semibold' }}@endif" role="button" {{ $bootstrapDataPrefix }}-toggle="collapse" {{ $bootstrapDataPrefix }}-target="#collapse{{ $institution['id'] }}" aria-expanded="false" aria-controls="collapse{{ $institution['id'] }}">
                    <i class="@if(!$oldBootstrap){{ 'fa-regular' }}@else{{ 'fa' }}@endif fa-circle me-2" style="font-size: 7px;"></i> {{ $institution['name'] }} ({{ sizeof($institution['children']) }})
                </p>
                @if(isset($institution['children']) && sizeof($institution['children']))
                    <div class="collapse multi-collapse" id="collapse{{ $institution['id'] }}">
                        <div class="ps-4">
                            @include('partials.institutions_tree_row', ['children' => $institution['children']])
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    @else
        <p>Не са открити записи</p>
    @endif
</form>
@if(isset($institutions) && sizeof($institutions))
    @if($canSelect)
        <button type="button" class="btn btn-sm btn-primary" id="select-institution" data-dom="{{ $selectId }}">{{ __('custom.select') }}</button>
    @endif
@endif
