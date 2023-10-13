<div class="row">
    @if($item->kd)
        <p class="text-danger fw-bold">За да създадете документа използвайте бутона 'Запиши'.</p>
    @endif
    <div class="col-12">
        <table class="table table-sm sm-text table-bordered table-hover">
            <thead>
                <tr>
                    <th colspan="3">{{ __('custom.dynamic_structures.type.CONSULT_DOCUMENTS') }}</th>
                </tr>-
            </thead>
            <tbody>
            @if(isset($kdRows) && $kdRows->count())
                @foreach($kdRows as $row)
                    <tr>
                        <td>{{ '#' }}</td>
                        <td>{{ $row->label }}</td>
                        <td><textarea type="{{ $row->type }}" class="form-control form-control-sm"></textarea>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
