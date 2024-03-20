@php
    $q_arr = request()->except('active');
    $url_active = (isset($type))
        ? array_merge(['type' => $type, 'active' => true], $q_arr)
        : array_merge(['active' => true], $q_arr);
    $url_inactive = (isset($type))
        ? array_merge(['type' => $type, 'active' => false], $q_arr)
        : array_merge(['active' => false], $q_arr);

    if(isset($specificUrl) && !empty($specificUrl)){
        $customActiveAction = $customInactiveAction = $specificUrl;
        $indx = 0;
        foreach ($url_active as $key => $param){
            $customActiveAction .= (!$indx ? '?' : '').($indx ? '&' : '').($key.'='.(int)$param);
            $indx += 1;
        }
        $indx = 0;
        foreach ($url_inactive as $key => $param){
            $customInactiveAction .= (!$indx ? '?' : '').($indx ? '&' : '').($key.'='.(int)$param);
            $indx += 1;
        }
    }
@endphp

<div class="btn-group float-right mb-3">
    <a class="btn btn-sm {{ !request()->has('active') || request()->offsetGet('active') == 1 ? 'btn-success' : 'btn-default' }}"
       href="@if(isset($customActiveAction)){{ $customActiveAction }}@else{{ action($action, $url_active) }}@endif">
        <i class="fas fa-check-circle"></i> {{ trans_choice('custom.active', 1) }}
    </a>

    <a class="btn btn-sm {{ request()->has('active') && request()->offsetGet('active') == 0 ? 'btn-danger' : 'btn-default' }}"
       href="@if(isset($customInactiveAction)){{ $customInactiveAction }}@else{{ action($action, $url_inactive) }}@endif">
        <i class="fas fa-times-circle"></i> {{ trans_choice('custom.inactive', 2) }}
    </a>
</div>
