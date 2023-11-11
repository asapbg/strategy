<div class="row">
    @if(!$item->kd)
        <p class="text-danger fw-bold">За да създадете документа използвайте бутона 'Запиши'.</p>
    @endif
    <form action="{{ route('admin.consultations.public_consultations.store.kd') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $item->id }}">
        <div class="col-12">
            <table class="table table-sm sm-text table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan="3">{{ __('custom.dynamic_structures.type.CONSULT_DOCUMENTS') }}</th>
                    </tr>
                </thead>
                <tbody>
                @php($foundedGroups = [])
                @if(isset($kdRows) && sizeof($kdRows))
                    @foreach($kdRows as $i => $row)
                        @php($currentGroup = $row->dynamic_structure_groups_id ? $row->group : null)
                        @if($row->dynamic_structure_groups_id && !in_array($row->dynamic_structure_groups_id, $foundedGroups))
                            @php($foundedGroups[] = $row->dynamic_structure_groups_id)
                            <tr>
                                <td colspan="3">{{ $row->group->ord }}. {{ $row->group->label }}</td>
                            </tr>
                        @endif

                        @php($value = isset($kdValues) && sizeof($kdValues) ? $kdValues[$row->id] ?? '' : '')
                        @if($row->dynamic_structure_groups_id)
                            <tr>
                                <td>{{ ($currentGroup ? $currentGroup->ord.'.' : '').$row->ord }}</td>
                                <td>{{ $row->label }}</td>
                                <td style="min-width: 600px;">
                                    <input type="hidden" value="{{ $row->id }}" name="row_id[]">
                                    <textarea type="{{ $row->type }}" name="val[]" class="form-control form-control-sm summernote ">{{ old('val.'.$i, $value) }}</textarea>
                                    @error('val.'.$i)
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2">{{ $row->ord.'. '.$row->label }}</td>
                                <td style="min-width: 600px;">
                                    <input type="hidden" value="{{ $row->id }}" name="row_id[]">
                                    <textarea type="{{ $row->type }}" name="val[]" class="form-control form-control-sm summernote">{{ old('val.'.$i, $value) }}</textarea>
                                    @error('val.'.$i)
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-6 col-md-offset-3">
            <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
            <button id="save" type="submit" name="stay" value="1" class="btn btn-success">{{ __('custom.save_and_stay') }}</button>
            <a href="{{ route($listRouteName) }}"
               class="btn btn-primary">{{ __('custom.cancel') }}</a>
        </div>
    </form>
</div>
