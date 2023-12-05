@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.news.update', $news->id) }}" method="post" name="form" id="form">
                        @csrf
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="title">
                                {{ __('custom.title') }} <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="title" class="form-control" required="required" value="{{ old('title') ?? $news->title }}">
                                @error('title')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="summary">
                                {{ __('custom.summary') }}
                            </label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <textarea class="summernote" name="summary" class="form-control">{{ old('summary') ?? $news->summary }}</textarea>
                                @error('summary')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="content">
                                {{ __('custom.content') }} <span class="required">*</span>
                            </label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <textarea class="summernote" name="content" class="form-control" rows="10" style="height: auto">{{ old('content') ?? $news->content }}</textarea>
                                @error('content')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.news') }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                        <br/>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
