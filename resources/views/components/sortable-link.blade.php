@php
    /**
     * Renders a sort link with an icon and the current sort order.
     *
     * @param  string  $sort_by      The current sort order.
     * @param  string  $translation  The text to display for the sort link.
     *
     * Example of use:
     *
     * @include('components.sortable-link', [
     *          'sort_by' => 'keywords',
     *          'translation' => trans_choice('custom.keyword', 1)
     *         ])
     */

    // variables

    $translation ??= '';
    $sort_by ??= '';

    // end of variables

    $sort_array = [];
    $sort_icon = 'fa-sort';

    if (!request()->has('order_by') || isset($defaultOrderBy)) {
        $sort_array['order_by'] = $sort_by;
    }

    if (request()->has('order_by') || isset($defaultOrderBy)) {
        $sort_array['order_by'] = $sort_by;
        $sort_array['direction'] = $defaultDirection ?? 'desc';
    }

    $asc = isset($defaultOrderBy) && isset($sort_array['order_by']) && $sort_array['order_by'] == $defaultOrderBy && $defaultDirection == 'asc';
    if ((!request()->has('direction') && request()->get('order_by' , '') === $sort_by) || $asc) {
        $sort_icon = 'fa-sort-asc';
    }

    $desc = isset($defaultOrderBy) && isset($sort_array['order_by']) && $sort_array['order_by'] == $defaultOrderBy && 'desc';
    if ((request()->has('direction') && request()->get('order_by' , '') === $sort_by) || $desc) {
        $sort_icon = 'fa-sort-desc';
        $sort_array = [];

        request()->query->remove('order_by');
        request()->query->remove('direction');
    }

    $sort_white = $sort_icon !== 'fa-sort' ? 'text-white' : '';
    $requestParams = request()->all();
    if(isset($customRequestParam) && !empty($customRequestParam)){
        $requestParams = array_merge($requestParams, $customRequestParam);
    }
    $sort_url = url()->current(). '?' . http_build_query(array_merge($requestParams, $sort_array));
@endphp

<a href="{{ $sort_url }}" @if(isset($ajax) && $ajax) data-url="{{ $sort_url }}" @if(isset($ajaxContainer)) data-container="{{ $ajaxContainer }}" @endif @endif
   class="mb-0 text-decoration-none text-dark @if(isset($ajax) && $ajax) ajaxSort @endif">
    <i class="fa-solid {{ $sort_icon }} me-2 {{ $sort_white }}"></i>{{ $translation }}
</a>
