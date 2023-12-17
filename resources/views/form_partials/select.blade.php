@include('form_partials.label')
@if (isset($readOnly))
    @php
    $selected = array_key_exists($name, $state) ? $options->filter(function($item) use ($state, $name) {
        return $item->id == $state[$name];
    })->first() : null;
    @endphp
    <p>{{ $selected ? $selected['name'] : '' }}</p>
@else
    <select id="{{ $name }}" name="{{ $name }}" class="select2 form-control form-control-sm @error($name){{ 'is-invalid' }}@enderror">
        @foreach ($options as $option)
            <option value="{{ $option->id }}" @if(in_array($option->id, old($name, []))){{ 'selected' }}@else{{ array_key_exists($name, $state) ? ($state[$name] == $option->id ? 'selected' : '') : '' }}@endif>
                {{ $option->name }}
            </option>
        @endforeach
    </select>
    @error($name)
        <div class="text-danger input-err">{{ $message }}</div>
    @enderror
@endif
