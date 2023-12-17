<form method="POST" action="#">
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-3 fs-4">{{ __('ogp.add_comment') }}</h3>
            <div class="summernote-wrapper">
                <textarea class="form-control mb-3 rounded summernote"
                          id="exampleFormControlTextarea1" rows="2" placeholder="{{ __('ogp.enter_comment') }}">
                  </textarea>
            </div>
            <button class="cstm-btn btn btn-primary login mt-3">{{ __('ogp.adding_comment') }}</button>
        </div>
    </div>
</form>
