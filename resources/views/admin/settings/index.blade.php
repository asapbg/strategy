@extends('layouts.admin')

@section('content')
<form id="settings-form" action="{{ route('admin.settings.store') }}" method="POST">
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3">
                        <button type="submit" class="btn btn-success">
                            {{ __('custom.save') }}
                            {{ __('custom.settings') }}
                        </button>
                    </div>

                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>{{__('validation.attributes.name')}}</th>
                            <th>{{__('validation.attributes.value')}}</th>
                            <th>{{__('validation.attributes.updated_at')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($settings) && $settings->count() > 0)
                            @foreach($settings as $setting)
                                <tr>
                                    <td>{{ $setting->key }}</td>
                                    <td>{{ $setting->value }}</td>
                                    <td>{{ $setting->updated_at }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="card-footer mt-2">
                    @if(isset($roles) && $roles->count() > 0)
                        {{ $roles->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </section>

</form>
@endsection
