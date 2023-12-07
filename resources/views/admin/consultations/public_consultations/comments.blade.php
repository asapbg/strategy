@php($docTypeCommentReport = \App\Enums\DocTypesEnum::PC_COMMENTS_REPORT->value)
<form class="row mb-5" enctype="multipart/form-data" action="{{ route('admin.consultations.public_consultations.proposal_report.store') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-sm-12 control-label" for="report_date">{{ __('validation.attributes.date') }} <span class="required">*</span></label>
            <input type="text" id="report_date" name="report_date"
                   class="form-control form-control-sm datepicker @error('report_date'){{ 'is-invalid' }}@enderror"
                   value="{{ old('report_date', displayDate(\Carbon\Carbon::now())) }}">
            @error('report_date')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="col-sm-12 control-label" for="report_time">{{ __('validation.attributes.hour') }} <span class="required">*</span></label>
            <div class="input-group bootstrap-timepicker timepicker">
                <input type="text" class="form-control input-small @error('report_time'){{ 'is-invalid' }}@enderror" name="report_time" value="{{ old('report_time', \Carbon\Carbon::now()->format('H:s')) }}">
                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                @error('report_time')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
{{--            <input type="text" id="report_time" name="report_time"--}}
{{--                   class="form-control form-control-sm timepicker @error('report_time'){{ 'is-invalid' }}@enderror"--}}
{{--                   value="{{ old('report_time', displayDate(\Carbon\Carbon::now())) }}">--}}
{{--            @error('report_time')--}}
{{--            <div class="text-danger mt-1">{{ $message }}</div>--}}
{{--            @enderror--}}
        </div>
    </div>
    <div class="col-12"></div>
    @foreach(config('available_languages') as $lang)
        @php($validationRules = \App\Enums\DocTypesEnum::validationRules($docTypeCommentReport, $lang['code']))
        @php($fieldName = 'file_'.$docTypeCommentReport.'_'.$lang['code'])
        <div class="col-md-6 mb-3">
            <label for="{{ $fieldName }}" class="form-label">{{ __('validation.attributes.'.$fieldName) }} @if(in_array('required', $validationRules))<span class="required">*</span>@endif </label>
            <input class="form-control form-control-sm @error($fieldName) is-invalid @enderror" id="{{ $fieldName }}" type="file" name="{{ $fieldName }}">
            @error($fieldName)
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    @endforeach
    <div class="col-12"></div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-sm-12 control-label" for="open_from">{{ __('validation.attributes.message') }} <span class="required">*</span></label>
            <textarea class="form-control summernote @error('message') is-invalid @enderror" name="message">{{ old('message', '') }}</textarea>
            @error('message')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-12"></div>
    <div class="col-md-2">
        <button class="btn btn-sm btn-success" type="submit">{{ __('custom.publish') }}</button>
    </div>

</form>
<div class="row">
    <h3><strong>Текущо становище:</strong></h3>
    @php($hasProposalReport = false)
    @if(isset($documents) && sizeof($documents))
        @foreach(config('available_languages') as $lang)
            @php($found = false)
            @if(isset($documents[$docTypeCommentReport.'_'.$lang['code']]) && sizeof($documents[$docTypeCommentReport.'_'.$lang['code']]))
                @if(!$found)
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('validation.attributes.'.('file_'.$docTypeCommentReport.'_'.$lang['code'])) }}</label>
                @endif
                @foreach($documents[$docTypeCommentReport.'_'.$lang['code']] as $doc)
                    <div class="mb-3 @if($loop->first) mt-3 @endif">
                        <a class="mr-3" href="{{ route('admin.download.file', $doc) }}" target="_blank" title="{{ __('custom.download') }}">
                            {!! fileIcon($doc->content_type) !!} {{ $doc->{'description_'.$doc->locale} }} - {{ __('custom.'.$doc->locale) }} | {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }} | {{ $doc->user ? $doc->user->fullName() : '' }}
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-info preview-file-modal mt-2" data-file="{{ $doc->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $doc->id]) }}">{{ __('custom.preview') }}</button>
                    </div>
                @endforeach
                @if(!$found)
                    </div>
                @endif
                @php($found = true)
                @php($hasProposalReport = true)
            @endif
        @endforeach
    @endif
    @if(!$hasProposalReport)
        <p class="text-danger">Няма качено становище</p>
    @endif
    <hr>
</div>

@php($fPdf = $item->commentsDocumentPdf())
@php($fCsv = $item->commentsDocumentCsv())
<div class="row">
    <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th colspan="4" class="text-center">
                <h3 class="fw-bold">{{ trans_choice('custom.comments', 2) }}</h3>
            </th>
        </tr>
        @if($fPdf || $fCsv)
            <tr>
                <th colspan="2" style="border: 1px solid #1c3050;">Авт. генерирани коментари след края на консултацията:</th>
                <th colspan="2" style="border: 1px solid #1c3050;">
                    @if($fPdf)
                        <a class="mr-3" style="font-size: 16px" href="{{ route('download.file', $fPdf) }}" target="_blank" title="{{ __('custom.download') }}">
                            {!! fileIcon($fPdf->content_type) !!} {{ $fPdf->{'description_'.$fPdf->locale} }}
                        </a>
                    @endif
                    @if($fCsv)
                        <a class="mr-3" style="font-size: 16px" href="{{ route('download.file', $fCsv) }}" target="_blank" title="{{ __('custom.download') }}">
                            {!! fileIcon($fCsv->content_type) !!} {{ $fCsv->{'description_'.$fCsv->locale} }}
                        </a>
                    @endif
                </th>
            </tr>
        @endif
        <tr>
            <th>ID</th>
            <th>{{ __('validation.attributes.content') }}</th>
            <th>{{ __('custom.created_at') }}</th>
            <th>{{ trans_choice('custom.users', 1)  }}</th>
        </tr>
        </thead>
        <tbody>
            @if($item->comments->count() > 0)
                @foreach($item->comments as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>
                            <div class="limit-length">
                                {!! $c->content !!}
                            </div>
                            <div class="full-length d-none">
                                {!! $c->content !!}
                            </div>
                        </td>
                        <td>{{ displayDateTime($c->created_at) }}</td>
                        <td>
                            @if($c->user_id)
                                <a target="_blank" class="text-primary" href="{{ route('admin.users.edit', $c->author) }}">{{ $c->author->fullName() }}</a>
                            @else
                                {{ __('custom.anonymous') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
