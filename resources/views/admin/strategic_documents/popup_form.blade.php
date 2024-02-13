<form class="row" id="new_sd_child_form">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <div class="row">
        <div class="col-12 text-danger" id="main_error"></div>
    </div>
    {{--    @csrf--}}
    <input type="hidden" name="sd" value="{{ $sd->id }}">
    @if(isset($doc) && $doc)
        <input type="hidden" name="doc" value="{{ $doc->id }}">
    @endif
    @php($defaultLang = config('app.default_lang'))
    <div class="row mb-4">
        @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\StrategicDocumentChildren::translationFieldsProperties(), 'field' => 'title', 'col' => 12, 'required' => true])
    </div>
    <div class="row mb-4">
        @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\StrategicDocumentChildren::translationFieldsProperties(), 'field' => 'description', 'col' => 12, 'required' => false])
    </div>
</form>


<script type="text/javascript">
    $(document).ready(function () {
        $('#new_sd_child_form .summernote').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol']],
                ['view', ['fullscreen']],
                ['insert', ['link']]
            ],
            dialogsInBody: true,
            lang: typeof GlobalLang != 'undefined' ? GlobalLang + '-' + GlobalLang.toUpperCase() : 'en-US',
        });
    });
</script>

