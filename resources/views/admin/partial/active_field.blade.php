<div class="form-group">
    <label class="col-sm-12 control-label" for="active">
        {{ __('custom.active_m') }}?
    </label>
    <div class="col-12">
        <div class="icheck-primary d-inline mr-3">
            <input type="radio" name="active" id="active_1" value="1" @if($disabled) readonly disabled @endif
                   @if(old('active', $item->active ?? 0) == 1)  checked="checked" @endif>
            <label for="active_1">Да</label>
        </div>
        <div class="icheck-primary  d-inline ">
            <input type="radio" name="active" id="active_0" value="0" @if($disabled) readonly disabled @endif
                   @if(old('active', $item->active ?? 0) == 0)  checked="checked" @endif>
            <label for="active_0">Не</label>
        </div>
    </div>
</div>
