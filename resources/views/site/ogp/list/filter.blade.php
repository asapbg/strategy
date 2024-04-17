<div class="row filter-results mb-2">
    <h2 class="mb-4">
        Търсене
    </h2>
    <div class="col-md-12">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Категория:</label>
                <select class="form-select select2" multiple aria-label="Default select example">
                    <option value="1">Всички</option>
                    <option value="1">Национални планове за действие</option>
                    <option value="1">Оценка за изпълнението на плановете за действие - мониторинг</option>
                    <option value="1">Разработване на нов план за действие</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Търсене в Заглавие/Съдържание</label>
                <input type="text" class="form-control" id="searchInTitle">
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Дата от:</label>
                <div class="input-group">
                    <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker-btn">
                    <span class="input-group-text datepicker-addon" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Дата до:</label>
                <div class="input-group">
                    <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker-btn">
                    <span class="input-group-text datepicker-addon" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Брой резултати:</label>
                <select class="form-select" id="paginationResults">
                    <option value="10" selected>9</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>
</div>
