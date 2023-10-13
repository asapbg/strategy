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
                <form>
                    <tr>
                        <td><input type="text" name="name" value="{{ old('name', '') }}"></td>
                        <td><input type="text" name="email" value="{{ old('email', '') }}"></td>
                        <td>
                            <button class="btn btn-sm btn-success" type="submit">{{ __('custom.save') }}</button>
                        </td>
                    </tr>
                    @if(isset($contactPersons) && $contactPersons->count())
                        @foreach($contactPersons as $row)
                            <tr>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->email }}</td>
                                <td>
                                    <a href=""
                                       class="btn btn-sm btn-info"
                                       data-toggle="tooltip"
                                       title="{{ __('custom.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href=""
                                       class="btn btn-sm btn-danger"
                                       data-toggle="tooltip"
                                       title="{{ __('custom.delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </form>
            </tbody>
        </table>
    </div>
</div>
