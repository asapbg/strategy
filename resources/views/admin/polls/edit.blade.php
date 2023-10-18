@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @php($storeRoute = route($storeRouteName, ['id' => $item->id ?? 0]))
                    <div class="row">
                        <div class="col-sm-6">
                            <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-12 control-label" for="name">
                                            {{ __('custom.name') }} <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="name" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror" value="{{ old('name', isset($item) ? $item->name : '') }}">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-12 control-label" for="start_date">
                                            {{ __('custom.begin_date') }} <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="start_date" name="start_date" class="form-control form-control-sm datepicker @error('start_date') is-invalid @enderror" value="{{ old('start_date', isset($item) ? $item->start_date : \Carbon\Carbon::now()->format(config('app.date_format'))) }}">
                                            @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-12 control-label" for="end_date">
                                            {{ __('custom.end_date') }}
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" id="end_date" name="end_date" class="form-control form-control-sm datepicker @error('end_date') is-invalid @enderror" value="{{ old('end_date', isset($item) ? $item->end_date : '') }}">
                                            @error('end_date')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-12 control-label" for="status">
                                            {{ __('custom.status') }} <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control form-control-sm @error('status') is-invalid @enderror" name="status">
                                                @foreach(optionsStatuses() as $k => $v)
                                                    <option value="{{ $k }}"
                                                            @if(old('status', $item->id ? $item->status : 0) == $k ) selected="selected" @endif >
                                                        {{ $v }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="is_once">
                                        <input type="checkbox" id="is_once" name="is_once" class="checkbox" value="1" @if ($item->is_once) checked @endif>
                                        {{ __('validation.attributes.once') }}
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="only_registered">
                                        <input type="checkbox" id="only_registered" name="only_registered" class="checkbox" value="1" @if ($item->only_registered) checked @endif>
                                        {{ __('validation.attributes.only_registered') }}
                                    </label>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 col-md-offset-3">
                                        <button id="save" type="submit" class="btn btn-sm btn-success">{{ __('custom.save') }}</button>
                                        <button id="save" type="submit" name="stay" value="1" class="btn btn-sm btn-success">{{ __('custom.save_and_stay') }}</button>
                                        <a href="{{ route('admin.links.index') }}" class="btn btn-sm btn-primary">{{ __('custom.cancel') }}</a>
                                    </div>
                                </div>
                            </form>
                            @if(isset($item->id) && !$item->has_entry)
                                <hr>
                                <h4>{{ __('custom.add_question_title') }}</h4>
                                <form action="{{ route('admin.polls.question.create') }}" method="post" id="new-question">
                                    @csrf
                                    <input type="hidden" value="{{ $item->id }}" name="poll_id">
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="col-sm-12 control-label" for="new_question_name">
                                                {{ __('custom.label_question') }} <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input maxlength="255" type="text" id="new_question_name" name="new_question_name" class="form-control form-control-sm @error('new_question_name') is-invalid @enderror" value="{{ old('new_question_name', '') }}">
                                                @error('new_question_name')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @for( $i=1; $i <= 2; $i++ )
                                        <div class="form-group new_answer" id="new_answers-{{ $i }}">
                                            <div class="row">
                                                <label class="col-sm-12 control-label" for="new_answers">
                                                    <i class="fa fa-remove text-danger remove-answer mr-1" data-new-answer="{{ $i }}" title="{{ __('custom.remove') }}" role="button"></i>
                                                    {{ __('custom.label_answer') }} <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input maxlength="255" type="text" name="new_answers[]" class="form-control form-control-sm @error('new_answers.'.($i - 1)) is-invalid @enderror" value="{{ old('new_answers.'.($i - 1), '') }}">
                                                    @error('new_answers.'.($i - 1))
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                    <button type="button" id="add-new-answer" class="btn btn-sm btn-primary">{{ __('custom.add_answer') }}</button>
                                    <button type="submit" name="save_new" class="btn btn-sm btn-success">{{ __('custom.add') }}</button>
                                </form>
                            @endif
                        </div>
                        @if(isset($item) && $item->questions->count())
                            <div class="col-md-6">
                                <h4 class="mb-4">{{ trans_choice('custom.questions', 2) }}</h4>
                                @foreach($item->questions as $key => $q)
                                    @if(!$item->has_entry)
                                        <form action="{{ route('admin.polls.question.edit') }}" method="post" class="mb-5" class="question">
                                            @csrf
                                            <input type="hidden" value="{{ $q->id }}" name="question_id">
                                            @endif
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-sm-12 control-label" for="question_name">
                                                        <a href="{{ route('admin.polls.question.delete', ['id' => $q->id ]) }}" class="mr-1"><i class="fas fa-trash-alt text-danger" title="{{ __('site.button_delete') }}"></i></a>
                                                        {{ __('custom.question_with_number', ['number' => ($key+1)]) }} <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input maxlength="255" type="text" name="question_name" class="form-control form-control-sm" value="{{ old('question_name', $q->name) }}" required>
                                                        @error('question_name')
                                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            {{--                                    Answers--}}
                                            @if($q->answers->count())
                                                @foreach($q->answers as $kk => $a)
                                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-2 pl-0 input-group input-group-sm answer">
                                                        <input type="hidden" value="{{ $a->id }}" name="answer_id[]">
                                                        <input type="text" maxlength="255" name="answer_name[]" class="form-control" placeholder="{{ __('custom.answer') }}" value="{{ $a->name }}" required>
                                                        <span class="input-group-append">
                                                        <button type="button" class="btn btn-outline-danger btn-flat remove-answer"><i class="fas fa-trash-alt" title="{{ __('custom.delete') }}"></i></button>
                                                    </span>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if(!$item->has_entry)
                                                <button type="submit" name="edit_question" class="btn btn-sm btn-success">{{ __('custom.save') }}</button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('admin.partial.modal')
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            // let accountTypeSection = $('#account_type_section');
            // let accountTypeSelect = $('#account_type');
            // let carrerCheckboxSection = $('#career_qa');
            //
            // function controlCareerOption(){
            //     if( parseInt(accountTypeSelect.find('option:selected').data('qa')) === 1 ) {
            //         carrerCheckboxSection.show();
            //     } else{
            //         carrerCheckboxSection.find('input').prop( "checked", false );
            //         carrerCheckboxSection.hide();
            //     }
            // }

            let newQuestionForm = $('#new-question');
            newQuestionForm.on('click', '.remove-answer', function(){
                //always must have at least 2 answers
                if( newQuestionForm.find('.new_answer').length >= 3 ) {
                    newQuestionForm.find('#new_answers-' + $(this).data('new-answer')).remove();
                }
            });

            $(document).on('click', '.remove-answer', function(){
                if( $(this).closest('form').find('.answer').length <= 2 ) {
                    adminModal("<?php echo __('custom.error');?>", "<?php echo __('custom.at_least_two_answer');?>");
                } else {
                    $(this).closest('.answer').remove();
                }
            });

            newQuestionForm.on('click', '#add-new-answer', function (){
                let newAnswerIndex = newQuestionForm.find('.new_answer').length + 1;
                $('<div class="form-group new_answer" id="new_answers-'+ newAnswerIndex +'">'
                    +'<div class="row">'
                    +'<label class="col-sm-12 control-label" for="new_answers">'
                    +'<i class="fa fa-remove text-danger remove-answer mr-1" data-new-answer="'+ newAnswerIndex +'" title="{{ __('custom.remove') }}" role="button"></i>'
                    +'{{ __('custom.label_answer') }} <span class="required">*</span>'
                    +'</label>'
                    +'<div class="col-md-6 col-sm-6 col-xs-12">'
                    +'<input maxlength="255" type="text" name="new_answers[]" class="form-control form-control-sm" value="">'
                    +'</div>'
                    +'</div>'
                    +'</div>'
                ).insertAfter($('.new_answer').last());
            });

            // //show user type select when poll is set as only for registered users
            // //hide career checkbox when poll is not for registered users
            // $('#is_public').on('change', function(){
            //     controlCareerOption();
            //     if( parseInt($(this).val()) === 0 ) {
            //         accountTypeSection.show();
            //     } else{
            //         accountTypeSection.hide();
            //         carrerCheckboxSection.find('input').prop( "checked", false );
            //         carrerCheckboxSection.hide();
            //     }
            // });
            //
            // accountTypeSelect.on('change', function(){
            //     controlCareerOption();
            // });

        });
    </script>
@endpush
