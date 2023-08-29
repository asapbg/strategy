@extends('layouts.site')

@section('pageTitle', 'Списък на физическите и юридическите лица')

@section('content')
    <div>
        Списъкът се изготвя в изпълнение на § 1 от Допълнителните разпоредби на Закона за нормативните актове.
    </div>
    <hr>
    <div class="row filter-results mb-2">
        <h2 class="mb-4">
            Търсене
        </h2>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Изпълнител</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Предмет на договора</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Възложител</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Сортиране</label>
                    <select class="form-select" aria-label="Default select example">
                        <option value="1">Сума (възходящо)</option>
                        <option value="1">Сума (низходящо)</option>
                        <option value="1">Дата (възходящо)</option>
                        <option value="1">Дата (низходящо)</option>
                        <option value="1">Дата (възходящо)</option>
                        <option value="1">Име възложител (възходящо)</option>
                        <option value="1">Име възложител (низходящо)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-8">
            <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
        </div>

        <div class="col-md-4">
            <div class="info-consul">
                <h4>
                    Общо 225 резултата
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead class="table-secondary">
            <tr>
                <th></th>
                <th>Наименование на възложител</th>
                <th>Наименование на изпълнител</th>
                <th>ЕИК (за юридически лица)</th>
                <th>Дата на договора</th>
                <th>Предмет на договора</th>
                <th>Кратко описание на извършените услуги</th>
                <th>Цена на договора (в лв. с ДДС)</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>Министерство на регионалното развитие и благоустройството</td>
                <td>ДЗЗД "Глобал Аквуекон"</td>
                <td>177282392</td>
                <td>23.08.2018 г.</td>
                <td>Консултантски услуги за изготвяне на становища, анализи, договори, документи, предложения за промени в приложимата нормативна уредба, свързани със стопанисване, поддържане и експлоатация на ВиК системите и съоръженията и предоставяне на ВиК услугите за Обособена позиция 5: оказване на правно-консултантска и техническа подкрепа на МРРБ за изготвяне на становища, анализи, документи и предложения за промени в приложимата нормативна уредба, свързани с развитието и управлението на ВиК отрасъла</td>
                <td>Изготвяне на проект на нормативен акт - Наредба за сервитутите на водоснабдителните и  канализационните проводи, мрежи и съоръжения</td>
                <td>22008 лв.</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Министерство на регионалното развитие и благоустройството</td>
                <td>ДЗЗД "Глобал Аквуекон"</td>
                <td>177282392</td>
                <td>23.08.2018 г.</td>
                <td>Консултантски услуги за изготвяне на становища, анализи, договори, документи, предложения за промени в приложимата нормативна уредба, свързани със стопанисване, поддържане и експлоатация на ВиК системите и съоръженията и предоставяне на ВиК услугите за Обособена позиция 5: оказване на правно-консултантска и техническа подкрепа на МРРБ за изготвяне на становища, анализи, документи и предложения за промени в приложимата нормативна уредба, свързани с развитието и управлението на ВиК отрасъла</td>
                <td>Изготвяне на проект на нормативен акт - Наредба за изменение и допълнение на Наредба № 4 от 2004 г. за условията и реда за присъединяване на потребителите и за ползване на водоснабдителните и канализационните системи</td>
                <td>27678 лв.</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
        <nav aria-label="Page navigation example">
            <ul class="pagination m-0">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">«</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">...</a></li>
                <li class="page-item"><a class="page-link" href="#">57</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">»</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endsection
