<div id="subscribe-form">
    <input type="hidden" id="channel" value="{{ $data['channel'] ?? null }}">
    <input type="hidden" id="model" value="{{ $data['model'] ?? null }}">
    <input type="hidden" id="model_id" value="{{ $data['model_id'] ?? null }}">
    <input type="hidden" id="model_filter" value="{{ $data['model_filter'] ?? null }}">
    <input type="hidden" id="route_name" value="{{ $data['route_name'] ?? null }}">
    <div class="form-group">
        <label class="col-sm-12 control-label" for="subscribe_name">{{ __('site.subscribe_name') }} <span class="required">*</span></label>
        <input type="text" id="subscribe_name" name="subscribe_name"
               class="form-control form-control-sm " value="" required>
    </div>
</div>
