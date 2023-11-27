<div class="row filter-results mb-2">
    <div class="col-md-6">
        <h2 class="mb-4">
            Търсене
        </h2>
    </div>
    <div class="col-md-6 text-end">
        <!--
        И в двата вида визуализация (табличен и дървовиден),
        нека най-отгоре да има линк към основната справка
         „Справка с всички документи“, която да сваля PDF
         с дървото и всички прилежащи файлове за всеки документ
         като линкове, за които е дадено „видими в справката“.
         Това е подобно на сегашната функционалност:
        -->
        <button class="btn btn-primary main-color"><i
                class="fas fa-download main-color me-1"></i>Справка с всички документи</button>
    </div>

    <div class="col-md-12">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Област на политика</label>
                <select class="form-select select2" multiple aria-label="Default select example" id="policySelect">
                    <option value="all">Всички</option>
                    @foreach ($policyAreas as $policyArea)
                        <option value="{{ $policyArea->id }}">{{ $policyArea->name }}</option>
                    @endforeach
                </select>
                <!--
                <select class="form-select select2" multiple aria-label="Default select example">
                    <option value="1">Всички</option>
                    <option value="1">Регионална политика</option>
                    <option value="1">Образование</option>
                    <option value="1">Външна политика, сигурност и отбрана</option>
                </select>
                -->
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Категория</label>
                <select class="form-select select2" multiple aria-label="Default select example" id="categorySelect">
                    <option value="all">Всички</option>
                    <option value="active">Действащи</option>
                    <option value="expired">Изтекли</option>
                    <option value="public_consultation">Процес на консултация</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="input-group" id="processOfConsultation">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Категории спрямо цикъла си на
                    живот</label>
                <select class="form-select select2" multiple aria-label="Default select example" >
                    <option value="1">Действащи</option> <!-- default -->
                    <option value="1">Изтекли</option>
                    <option value="1">В процес на консултация</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Търсене в
                    Заглавие/Съдържание</label>
                <input type="text" class="form-control" id="searchInTitle">
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Валидна от:</label>
                <div class="input-group">
                    <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                           class="form-control datepicker" id="valid_from">
                    <span class="input-group-text" id="basic-addon2"><i
                            class="fa-solid fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Валидна до:</label>
                <div class="input-group">
                    <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                           class="form-control datepicker" id="valid_to">
                    <span class="input-group-text" id="basic-addon2"><i
                            class="fa-solid fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <div class="input-group">
            <div class="mb-3 d-flex flex-column w-100">
                <label for="date_expiring_indefinite_checkbox" class="form-label">Безсрочна:</label>
                <div class="input-group">
                    <div class="mb-3 d-flex flex-column w-100">
                        <div class="form-check form-switch">
                            <input type="hidden" name="date_expiring_indefinite" value="0">
                            <input type="checkbox" id="date_expiring_indefinite_checkbox" name="date_expiring_indefinite"
                                   class="form-check-input" value="1">
                            <label class="form-check-label" for="date_expiring_indefinite_checkbox">Безсрочна</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Ниво</label>
                <select class="form-select" id="documentLevelSelect">
                    <!--
                        Важно - При второто и третото трябва да се появява или да става активно поле за избор на съответната област или община.
                    -->
                    <option value="all">--</option>
                    <option value="1">Централно</option>
                    <option value="2">Областно</option>
                    <option value="3">Общинско</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5 action-btn-wrapper">
    <div class="col-md-3 col-sm-12">
        <button id="searchBtn" class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
    </div>
    <div class="col-md-9 text-end col-sm-12">
        <button class="btn btn-primary  main-color"><i
                class="fas fa-square-rss text-warning me-1"></i>RSS
            Абониране</button>
        <button class="btn btn-primary main-color"><i
                class="fas fa-envelope me-1"></i>Абониране</button>
        <button class="btn btn-success text-success"><i
                class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
    </div>
</div>

<div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
    <div class="col-md-3">
        <p class="mb-0 cursor-pointer " id="policyAreaSort">
            <i class="fa-solid fa-sort me-2"></i> Област на политика
        </p>
    </div>
    <div class="col-md-3 ">
        <p class="mb-0 cursor-pointer">
            <i class="fa-solid fa-sort me-2"></i>Заглавие
        </p>
    </div>


    <div class="col-md-3">
        <p class="mb-0 cursor-pointer">
            <i class="fa-solid fa-sort me-2"></i>Валидна от
        </p>
    </div>
    <div class="col-md-3">
        <p class="mb-0 cursor-pointer ">
            <i class="fa-solid fa-sort me-2"></i>Валидна до
        </p>
    </div>
</div>

<div class="row justify-content-end my-3">
    <div class="col-md-4">

    </div>
    <div
        class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
        <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой
            резултати:</label>
        <select class="form-select w-auto" id="paginationResults">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
</div>
