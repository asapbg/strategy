@include('form_partials.label')
@if (isset($readOnly))
    @php
    $selected = array_key_exists($name, $state) ? $options->filter(function($item) use ($state, $name) {
        return $item->id == $state[$name];
    })->first() : null;
    @endphp
    <p>{{ $selected ? $selected['name'] : '' }}</p>
@else
    <select id="{{ $name }}" name="{{ $name }}" class="select2 form-control form-control-sm">
        @foreach ($options as $option)
            <option value="{{ $option->id }}" {{ array_key_exists($name, $state) ? ($state[$name] == $option->id ? 'selected' : '') : '' }}>
                {{ $option->name }}
            </option>
        @endforeach    
    </select>
@endif