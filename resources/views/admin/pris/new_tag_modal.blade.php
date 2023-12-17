<form method="POST" id="ajax_tag" action="{{ route('admin.pris.tag.ajax.create', $item) }}">
    @csrf
    <!-- Наименование -->
    <div class="row mb-2">
        @include('admin.partial.edit_field_translate', ['field' => 'label', 'required' => true, 'translatableFields' => \App\Models\Tag::translationFieldsProperties()])
    </div>
    <div class="row mb-2">
        <div class="col-12 text-danger" id="ajax_tag_err"></div>
    </div>
</form>

