<div class="modal fade" id="modal-restore-resource" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">
                    <i class="fas fa-exclamation"></i>
                    {{__('custom.restore')}}  {{ $resource }}
                    <span class="resource-name d-none"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {{ __('custom.are_you_sure_to_restore') }} {{ mb_strtolower($resource) }}
                <b><span id="resource_label" class="resource-name"></span></b> ?
            </div>

            <div class="modal-footer">
                <form method="POST" action="" class="pull-left mr-4">
                    @csrf
                    @method('PUT')
                    <input name="id" value="" id="resource_id" type="hidden"/>
                    <input name="restore" value="true" type="hidden"/>
                    <button type="submit" class="btn btn-success js-restore-resource">
                        <i class="fa fa-plus"></i>&nbsp; {{ __('custom.restore') }}
                    </button>
                </form>

                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                    {{__('custom.cancel')}}
                </button>
            </div>
        </div>
    </div>
</div>
