@if(isset($sorter) && sizeof($sorter))
    <div class="row sort-row fw-600 main-color-light-bgr align-items-start rounded py-2 px-2 m-0">
        @if(isset($info) && !empty($info))
            <div class="text-start col-md-1">
                <i class="fas fa-info-circle text-primary " style="font-size: 20px" title="{{ $info }}" data-html="true" data-bs-placement="top" data-bs-toggle="tooltip"><span class="d-none">.</span></i>
            </div>
        @endif
        @foreach($sorter as $sKey => $s)
            <div class="text-start @if(isset($s['class']) && !empty($s['class'])) {{ $s['class'] }} @else col-md-2 @endif">
                @include('components.sortable-link', ['sort_by' => $sKey, 'translation' => $s['label']])
            </div>
        @endforeach
    </div>
@endif
