<form method="POST" id="modal_edit_connected_document"
      action="{{ route('admin.pris.connection.update', ['pris_id' => $pris->id, 'id' => $connection->id]) }}"
>
    @csrf
    <span class="text-danger" id="update_connected_doc_error"></span>
    <div class="col-12 mb-5 ml-2">
        <div class="row">
            <div class="col-md-3">
                <select id="modal_legal_act_type_filter" name="legal_act_type_filter"  class="form-select
                    @error('legal_act_type_filter'){{ 'is-invalid' }}@enderror"
                >
                    <option value="">-- изберете {{ l_trans('custom.legal_act_types') }} --</option>
                    @if(isset($legalActTypes) && $legalActTypes->count())
                        @foreach($legalActTypes as $row)
                            <option value="{{ $row->id }}"
                                    @if($connectedDoc && $connectedDoc->legal_act_type_id == $row->id) selected @endif
                            >
                                {{ $row->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('legal_act_type_filter')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-5">
                @php($prisChangedDocIds = $pris->changedDocs->pluck('id')->toArray())
                <select id="modal_change_docs" name="modal_change_docs[]" multiple="multiple"
                        data-types2ajax="pris_doc"
                        data-urls2="{{ route('admin.select2.ajax', 'pris_doc') }}"
                        data-placeholders2="{{ __('custom.search_pris_doc_js_placeholder') }}"
                        data-depends-on="#modal_legal_act_type_filter"
                        class="form-control form-control-sm select2-autocomplete-ajax @error('change_docs'){{ 'is-invalid' }}@enderror"
                >
                    @if($connectedDoc)
                        <option value="{{ $connectedDoc->id }}">{{ $connected_doc_name }}</option>
                    @endif
                </select>
                @error('change_docs')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <select id="modal_connect_type" name="connect_type" class="form-select @error('connect_type'){{ 'is-invalid' }}@enderror">
                    <option value="">---</option>
                    @foreach(\App\Enums\PrisDocChangeTypeEnum::options() as $name => $val)
                        <option value="{{ $val }}" @if($connection->connect_type == $val) selected @endif>
                            {{ __('custom.pris.change_enum.'.$name) }}
                        </option>
                    @endforeach
                </select>
                @error('connect_type')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <input type="text" id="modal_connect_text" name="connect_text" placeholder="Въведете текст"
                       class="form-control form-control-sm @error('connect_text'){{ 'is-invalid' }}@enderror"
                       value="{{ $connection->connect_text ?? $connection->full_text }}"
                >
                @error('connect_text')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $('#modal_edit_connected_document .select2-autocomplete-ajax').each(function () {
            MyS2Ajax($(this), $(this).data('placeholders2'), $(this).data('urls2'), $(this).data('dependsOn'));
            $('#modal_edit_connected_document .select2-autocomplete-ajax').val([{{ $connectedDoc->id ?? 0 }}]).trigger('change');
        });

        let errorContainer = $('#update_connected_doc_error');
        $('.update_connected_document').on('click', function() {
            errorContainer.html('');
            $.ajax({
                url  : '{{ route('admin.pris.connection.update', ['pris_id' => $pris->id, 'id' => $connection->id]) }}',
                type : 'POST',
                data : {
                    _token: '{{ csrf_token() }}',
                    connectIds: $('#modal_change_docs').val(),
                    connect_type: $('#modal_connect_type').val(),
                    connect_text: $('#modal_connect_text').val()
                },
                success : function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        console.log(response.message);
                        errorContainer.html(response.message);
                    }
                },
                error : function() {
                    errorContainer.html(response.message);
                }
            });
        });
    });
</script>
