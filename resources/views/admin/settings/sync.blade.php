@php
    $show_button    = $show_button  ?? false;
    $title          = $title        ?? __('custom.sync_all_institutions');
    $question       = $question     ?? __('custom.are_you_sure_sync_all');
@endphp

<!-- Button trigger modal -->
@if($show_button)
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sync-institutions-modal">
        {{ __('custom.sync_all_institutions') }}
    </button>
@endif

<!-- Modal -->
<div class="modal fade" id="sync-institutions-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="sync-institutions-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="sync-institutions-modalLabel">{{ $title }}</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{--        TITLE        --}}
                <div class="progress-title">
                    {{ $question }}
                </div>

                {{--       MESSAGE         --}}
                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol id="check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </symbol>
                </svg>

                <div class="alert alert-success d-flex align-items-center" role="alert" style="display: none !important;">
                    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Success:" style="height: 25px; width: 25px;"><use xlink:href="#check-circle-fill"/></svg>
                    <div id="sync-result-message"></div>
                </div>

                {{--      PROGRESS BAR          --}}
                <div class="progress" style="display: none;">
                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="sync-close" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="sync-button" onclick="startSync();">{{ __('custom.start_syncing') }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function startSync() {
            const modal = $('#sync-institutions-modal');
            const progressTitle = modal.find('.progress-title');
            const progressBarContainer = modal.find('.progress');
            const progressBar = modal.find('.progress-bar');
            const closeButton = modal.find('#sync-close');
            const syncButton = modal.find('#sync-button');
            const closeModalButton = modal.find('.btn-close');

            const syncStartedText = @json(__('custom.sync_started'));
            const closeText = @json(__('custom.close'));
            const syncSuccessText = @json(__('messages.sync_finished_successfully'));

            // Initialize UI state
            progressTitle.text(syncStartedText);
            progressBarContainer.show();
            syncButton.hide();
            closeModalButton.hide();
            closeButton.attr('disabled', true);

            const duration = 20000;         // Total duration: 20 seconds
            const updateInterval = 100;   // Update every 1 second
            let progress = 0;

            const interval = setInterval(() => {
                progress += (100 / (duration / updateInterval));
                progressBar.css('width', `${progress}%`).attr('aria-valuenow', Math.min(100, progress));

                if (progress >= 100) {
                    clearInterval(interval); // Stop when progress reaches 100%

                    // Update UI for completion
                    progressTitle.hide();
                    progressBarContainer.hide();
                    modal.find('.alert').show();
                    modal.find('#sync-result-message').text(syncSuccessText);
                    closeButton.attr('disabled', false).text(closeText);
                }
            }, updateInterval);
        }
    </script>
@endpush
