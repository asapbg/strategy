@php
    $q_arr = request()->except('active');
    $url_active = (isset($type))
        ? array_merge(['type' => $type, 'active' => true], $q_arr)
        : array_merge(['active' => true], $q_arr);
    $url_inactive = (isset($type))
        ? array_merge(['type' => $type, 'active' => false], $q_arr)
        : array_merge(['active' => false], $q_arr);
@endphp
<div class="btn-group float-right mb-3">
    <a class="btn btn-sm {{ !request()->has('active') || request()->offsetGet('active') == 1 ? 'btn-success' : 'btn-default' }}"
       href="{{ action($action, $url_active) }}">
        <i class="fas fa-check-circle"></i> {{ trans_choice('custom.active', 1) }}
    </a>

    <a class="btn btn-sm {{ request()->has('active') && request()->offsetGet('active') == 0 ? 'btn-danger' : 'btn-default' }}"
       href="{{ action($action, $url_inactive) }}">
        <i class="fas fa-times-circle"></i> {{ trans_choice('custom.inactive', 2) }}
    </a>
</div>
