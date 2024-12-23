@can('createComment', $offer)
<form class="col-12 mb-4" method="POST" action="{{ route('ogp.develop_new_action_plans.add_comment', $offer->id) }}" class="ogp-offer-comments">
    @csrf
    <div class="row">
        <div class="col-12 text-danger comment-error"></div>
        <div class="col-md-12">
            <h3 class="mb-3 fs-4">{{ __('ogp.add_comment') }}</h3>
            <div class="summernote-wrapper">
                <textarea class="form-control mb-3 rounded summernote" name="comment" rows="2" placeholder="{{ __('ogp.enter_comment') }}"></textarea>
            </div>
            <button type="button" class="cstm-btn btn btn-primary login mt-3 submit-comment">{{ __('ogp.adding_comment') }}</button>
        </div>
    </div>
</form>
@endcan
