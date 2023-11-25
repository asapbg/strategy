<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.chairmen_list', 2) }}</h3>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-create-chairman">
                    <i class="fa fa-plus mr-3"></i>
                    {{ __('custom.add') . ' ' . trans_choice('custom.chairmen', 1) }}
                </button>
            </div>
        </div>
    </div>
</div>
