<div class="row">
    <div class="col-12">
        <table class="table table-sm sm-text table-bordered table-hover">
            <thead>
                <tr>
                    <th>{{ __('custom.name') }}</th>
                    <th>{{ __('custom.email') }}</th>
                    <th>{{ __('custom.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <form action="{{ route('admin.consultations.public_consultations.add.contact') }}" method="post">
                        @csrf
                        <input type="hidden" name="pc_id" value="{{ $item->id }}">
                        <td>
                            <input class="form-control form-control-sm @error('new_name') is-invalid @enderror" type="text" name="new_name" value="{{ old('new_name', '') }}">
                            @error('new_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input class="form-control form-control-sm @error('new_email') is-invalid @enderror" type="text" name="new_email" value="{{ old('new_email', '') }}">
                            @error('new_email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <button class="btn btn-sm btn-success" type="submit">{{ __('custom.add') }}</button>
                        </td>
                    </form>
                </tr>
            </tbody>
        </table>
        @if($item->contactPersons->count())
            <form action="{{ route('admin.consultations.public_consultations.update.contacts') }}" method="post">
                @csrf
                <input type="hidden" name="pc_id" value="{{ $item->id }}">
                @foreach($item->contactPersons as $i => $row)
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <input type="hidden" name="id[]" value="{{ $row->id }}">
                            <input type="text" name="name[]" class="form-control form-control-sm @error('name.'.$i) is-invalid @enderror" value="{{ old('name.'.$i, $row->name) }}">
                            @error('name.'.$i)
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="email[]" class="form-control form-control-sm @error('email.'.$i) is-invalid @enderror" value="{{ old('email.'.$i, $row->email) }}">
                            @error('email.'.$i)
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:;"
                               class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                               data-target="#modal-delete-resource"
                               data-resource-id="{{ $row->id }}"
                               data-resource-name="{{ $row->name }}"
                               data-resource-delete-url="{{ route('admin.consultations.public_consultations.remove.contact',$row->id) }}"
                               data-toggle="tooltip"
                               title="{{__('custom.deletion')}}">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
                <button class="btn btn-sm btn-success mt-2" type="submit">{{ __('custom.save') }}</button>
            </form>
        @endif
    </div>
</div>
@includeIf('modals.delete-resource', ['resource' => trans_choice('custom.person_contacts', 1)])
