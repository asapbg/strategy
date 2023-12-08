<div class="modal fade" id="modal-register-advisory-moderator" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.register') . ' ' . __('custom.of') . ' ' . trans_choice('custom.users', 1) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="REGISTER_ADVISORY_MODERATOR_FORM"
                      action="{{ route('admin.advisory-boards.members.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="username">
                                    {{ __('validation.attributes.username') }}<span class="required">*</span>
                                </label>
                                <div class="col-12">
                                    <input type="text" id="username" name="username" class="form-control"/>
                                    <div class="text-danger mt-1 error_username"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="first_name">
                                    {{ __('validation.attributes.first_name') }}<span class="required">*</span>
                                </label>
                                <div class="col-12">
                                    <input type="text" id="first_name" name="first_name" class="form-control"/>
                                    <div class="text-danger mt-1 error_first_name"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="middle_name">
                                    {{ __('validation.attributes.middle_name') }}
                                </label>
                                <div class="col-12">
                                    <input type="text" id="middle_name" name="middle_name" class="form-control"/>
                                    <div class="text-danger mt-1 error_middle_name"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="last_name">
                                    {{ __('validation.attributes.last_name') }}<span class="required">*</span>
                                </label>
                                <div class="col-12">
                                    <input type="text" id="last_name" name="last_name" class="form-control"/>
                                    <div class="text-danger mt-1 error_last_name"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="email">
                                    {{ __('validation.attributes.email') }}
                                </label>
                                <div class="col-12">
                                    <input type="email" id="email" name="email" class="form-control"/>
                                    <div class="text-danger mt-1 error_email"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="password">
                                    {{ __('validation.attributes.password') }}<span class="required">*</span>
                                </label>
                                <div class="col-12">
                                    <input type="password" id="password" name="password" class="form-control passwords"
                                           autocomplete="new-password"/>

                                    <i>{{ __('auth.password_format') }}</i>

                                    <div class="text-danger mt-1 error_password"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="password_confirmation">
                                    {{ __('validation.attributes.password_confirm') }}
                                </label>
                                <div class="col-12">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                           class="form-control passwords"
                                           autocomplete="new-password">
                                    <div class="text-danger mt-1 error_password_confirmation"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="submitAjax(this, '{{ route('admin.advisory-boards.moderator.register', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.register') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
