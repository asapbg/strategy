<div class="row filter-results mb-2">
    <h2 class="mb-4">
        {{ __('custom.search') }}
    </h2>
    <div class="col-md-4">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Търсене в Заглавие/Съдържание</label>
                <input type="text" class="form-control" id="searchInTitle">
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">{{ __('ogp.from_date') }}:</label>
                <div class="input-group">
                    <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                           class="form-control datepicker-btn">
                    <span class="input-group-text datepicker-addon" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">{{ __('ogp.to_date') }}:</label>
                <div class="input-group">
                    <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                           class="form-control datepicker-btn">
                    <span class="input-group-text datepicker-addon" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
