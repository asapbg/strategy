<div>
    {{ $slot }}
    <div class="modal fade" tabindex="-1" id="{{ $modalId }}" aria-labelledby="{{ $modalId }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation"></i> {{ __('custom.deletion') . ' ' . __('custom.of') . ' ' . trans_choice('custom.comments', 1) }}
                    </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ $warningMessage }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn btn-danger ajax-modal-delete"
                            data-dismiss="modal"
                            data-url="{{ $url }}"
                            data-row-id="{{ $rowId }}"><i class="fas fa-ban"></i> {{ __('custom.continue') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
