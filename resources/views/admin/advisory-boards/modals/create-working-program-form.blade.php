<div class="modal fade" id="modal-create-working-program" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.add') . ' ' . trans_choice('custom.function', 1) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="WORKING_PROGRAM_FORM">
                    @csrf

                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="working_year">
                                    {{ __('validation.attributes.year') }}:
                                </label>
                                <input type="text" data-provide="datepicker" class="form-control form-control-sm datepicker-year"
                                       value="{{ old('working_year', date('Y')) }}" id="working_year" name="working_year" data-date-format="yyyy">
                            </div>

                            <div class="ajax-error text-danger mt-1 error_working_year"></div>
                        </div>
                    </div>

                    <!-- Описание -->
                    <div class="row mb-2">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-6">
                                <label for="description_{{ $lang['code'] }}">
                                    {{ __('validation.attributes.description') }}
                                    ({{ Str::upper($lang['code']) }})
                                    <span class="required">*</span>
                                </label>

                                <textarea class="form-control form-control-sm summernote"
                                          name="description_{{ $lang['code'] }}"
                                          id="description_{{ $lang['code'] }}"></textarea>

                                <div class="ajax-error text-danger mt-1 error_description_{{ $lang['code'] }}"></div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="submitAjax(this, '{{ route('admin.advisory-boards.function.store', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.add') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
{{--@push('scripts')--}}
{{--    <script type="text/javascript">--}}
{{--        $(document).ready(function (){--}}
{{--            let currYear = <?php echo date('Y');?>;--}}
{{--            $('#working_year').on('change keyup input', function (){--}}
{{--                let val = $(this).val();--}}
{{--                if(val.length == 4 && parseInt(val) < parseInt(currYear)) {--}}
{{--                    $(this).val(currYear);--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
