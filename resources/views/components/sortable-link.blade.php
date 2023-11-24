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

    if (!request()->has('order_by')) {
        $sort_array['order_by'] = $sort_by;
    }

    if (request()->has('order_by')) {
        $sort_array['order_by'] = $sort_by;
        $sort_array['direction'] = 'desc';
    }

    if (!request()->has('direction') && request()->get('order_by' , '') === $sort_by) {
        $sort_icon = 'fa-sort-asc';
    }

    if (request()->has('direction') && request()->get('order_by' , '') === $sort_by) {
        $sort_icon = 'fa-sort-desc';
        $sort_array = [];

        request()->query->remove('order_by');
        request()->query->remove('direction');
    }

    $sort_white = $sort_icon !== 'fa-sort' ? 'text-white' : '';

    $sort_url = url()->current(). '?' . http_build_query(array_merge(request()->all(), $sort_array));
    dump($sort_array, $sort_url, $sort_icon, $sort_white)
@endphp

<a href="{{ $sort_url }}"
   class="mb-0 text-decoration-none text-dark">
    <i class="fa-solid {{ $sort_icon }} me-2 {{ $sort_white }}"></i>{{ $translation }}
</a>
