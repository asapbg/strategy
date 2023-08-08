@include('form_partials.label')
<select id="{{ $name }}" name="{{ $name }}" class="select2 form-control form-control-sm">
    @foreach ($options as $option)
        <option value="{{ $option->id }}" {{ array_key_exists($name, $state) ? ($state[$name] == $option->id ? 'selected' : '') : '' }}>
            {{ $option->name }}
        </option>
    @endforeach    
</select>