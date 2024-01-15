@php
    $archive ??= collect();
@endphp

<form method="GET">
    <div class="row">
        <button type="submit" class="btn btn-sm @if(request()->get('archive_category', '') == 1) btn-secondary @else btn-success @endif col-6" name="archive_category" value="1" @if(request()->get('archive_category', '') == 1) disabled @endif>
            {{ __('custom.meetings_and_decisions') }}
        </button>
        <button type="submit" class="btn btn-sm @if(request()->get('archive_category', '') == 2) btn-secondary @else btn-success @endif  col-6" name="archive_category" value="2" @if(request()->get('archive_category', '') == 2) disabled @endif>
            {{ __('custom.function') }}
        </button>
    </div>
</form>

@include('admin.partial.archive_list', ['items' => $archive, 'current_tab' => 'archive'])
