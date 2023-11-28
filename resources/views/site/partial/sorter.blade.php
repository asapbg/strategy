@if(isset($sorter) && sizeof($sorter))
    <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
        @foreach($sorter as $sKey => $s)
            <div class="text-left @if(isset($s['class']) && !empty($s['class'])) {{ $s['class'] }} @else col-md-2 @endif">
                @include('components.sortable-link', ['sort_by' => $sKey, 'translation' => $s['label']])
            </div>
        @endforeach
    </div>
@endif
