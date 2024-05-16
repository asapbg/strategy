<h5 class="@if(isset($readOnly) && $readOnly) mt-5 @endif">{{ __('custom.forms4.text1') }}</h5>
<table width="100%" class="table">
<thead>
    <td width="40%">{{ __('custom.forms4.text2') }}</td>
    <td>{{ __('custom.forms4.text3') }}</td>
</thead>
<tbody>
    @include('form_partials.shared.abbreviations', ['name' => 'abbreviations[]', 'buttonLabel' => 'forms.abbreviation'])
</tbody>
</table>

<h5>{{ __('csutom.forms4.text4') }}</h5>
<table width="100%" class="table">
<thead>
    <td width="40%">{{ __('custom.forms4.text5') }}</td>
    <td>{{ __('custom.forms4.text6') }}</td>
</thead>
<tbody>
    @include('form_partials.shared.array_texts', ['name' => 'figures[]', 'buttonLabel' => 'forms.figure', 'keys' => ['number', 'text']])
</tbody>
</table>

<h5>{{ __('custom.forms4.text7') }}</h5>
<table width="100%" class="table">
<thead>
    <td width="40%">{{ __('custom.forms4.text8') }}</td>
    <td>{{ __('custom.forms4.text9') }}</td>
</thead>
<tbody>
    @include('form_partials.shared.array_texts', ['name' => 'tables[]', 'buttonLabel' => 'forms.table', 'keys' => ['number', 'text']])
</tbody>
</table>

{!! __('custom.forms4.text10') !!}
