<form class="row" action="{{ route('admin.consultations.public_consultations.poll.attach') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">
    <div class="col-md-6">
        <select class="form-control form-control-sm select2 @error('poll') is-invalid @enderror" name="poll">
            <option value="0">---</option>
            @if(isset($polls) && $polls->count())
                @foreach($polls as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            @endif
        </select>
        @error('poll')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <button class="btn btn-sm btn-success" type="submit">{{ __('custom.add') }}</button>
    </div>
</form>
<div class="row">
    <table class="table table-sm sm-text table-bordered table-hover mt-4">
        <thead>
        <tr>
            <th>{{ __('custom.name') }}</th>
            <th>{{ __('custom.actions') }}</th>
        </tr>
        </thead>
        <tbody>
            @if($item->polls && $item->polls->count())
                @foreach($item->polls as $poll)
                    <tr>
                        <td>
                            {{ $poll->name }}
                        </td>
                        <td>
                            @can('update', $poll)
                                <a href="{{ route('admin.polls.edit', ['id' => $poll->id]) }}"><i class="btn btn-sm btn-primary fas fa-edit"></i></a>
                            @endcan
                            @can('preview', $poll)
                                <a href="{{ route('admin.polls.preview', $poll) }}"><i class="btn btn-sm btn-success fas fa-eye"></i></a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ __('messages.records_not_found') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
