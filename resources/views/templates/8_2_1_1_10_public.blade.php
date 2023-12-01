@extends('layouts.site', ['fullwidth' => true])
<style>

</style>
@section('pageTitle', '8.2.1.1.10.	Списък на физическите и юридическите лица, на които е възложено от държавата или общините да изработят проекти на нормативни актове, оценки на въздействието')

@section('content')


    <div class="row mt-4">
        <div class="col-md-8">
            <p class="fs-18 fw-600 m-0">
                Списъкът се изготвя в изпълнение на § 1 от Допълнителните разпоредби на Закона за нормативните актове.
            </p>
        </div>

        <div class="col-md-4 text-end">
          <button class="btn btn-sm btn-primary main-color">
            <i class="fas fa-pen me-2 main-color"></i>Редактиране</button>
        </div>
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
                    <label for="exampleFormControlInput1" class="form-label">Кратко описание</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <label for="exampleFormControlInput1" class="form-label">Начална дата:</label>
            <div class="input-group">
                <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker">
                <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
            </div>
        </div>



        <div class="col-md-3">
            <label for="exampleFormControlInput1" class="form-label">Крайна дата:</label>
            <div class="input-group">
                <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker">
                <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Брой резултати </label>
                    <select class="form-select" aria-label="Default select example">
                        <option value="1">5</option>
                        <option value="1">10</option>
                        <option value="1">50</option>
                        <option value="1">100</option>
                        <option value="1">150</option></option>
                        <option value="1">200</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Цена</label>
                    <span class="small">Над 1500лв.</span>
                    <input type="range" class="form-range" min="0" max="1000" step="0" id="customRange3">
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5 action-btn-wrapper">
      <div class="col-md-3 col-sm-12">
          <button class="btn rss-sub main-color" id="searchBtn"><i class="fas fa-search main-color"></i>Търсене</button>
      </div>
      <div class="col-md-9 text-end col-sm-12">
          <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS
              Абониране</button>
          <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
          <button class="btn btn-success text-success"><i class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
      </div>
  </div>

<div class="row pt-4 pb-2 px-2">
    <div class="col-md-12">
            <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2">
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>   Наименование на възложител	
                    </p>
                </div>
                <div class="col-md-2 cursor-pointer ">
                    <p class="mb-0">
                        <i class="fa-solid fa-sort"></i>  Наименование на изпълнител	
                    </p>
                </div>
                <div class="col-md-1">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>  ЕИК <br>(за юридически лица)	
                    </p>
                </div>
                <div class="col-md-1">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>  Дата на договора	
                    </p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>  Предмет на договора	
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>  Кратко описание на извършените услуги	
                    </p>
                </div>
                <div class="col-md-1">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>  Цена на договора (в лв. с ДДС)
                    </p>
                </div>
            </div>

      
   </div>
</div>  

<div class="row justify-content-end my-3">
  <div class="col-md-4">
  </div>
  <div class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
      <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой
          резултати:</label>
      <select class="form-select w-auto" id="paginationResults">
          <option value="5">5</option>
          <option value="20">20</option>
          <option value="30">30</option>
          <option value="40">40</option>
          <option value="50">50</option>
          <option value="100">100</option>
      </select>
  </div>
</div>

<div class="row">
<div class="col-md-12">
    <div class="custom-card pt-1 pb-4 px-3 mb-3">
        <div class="row m-0">
            <div class="col-md-12 text-end p-0">
                   <a href="#"><i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                  </a>
                  <a href="#" title="Редактиране"> <i class="fas fa-pen-to-square float-end main-color fs-4"></i></a>

            </div>
        </div>
        <div class="row single-record">
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">1. Министерство на регионалното развитие и благоустройството	</a>
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">ДЗЗД "Глобал Аквуекон"	</a> 	
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    <a href="#" class="main-color text-decoration-none">177282392	</a>
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    23.08.2018 г.	
                </p>
            </div>
            <div class="col-md-3">
                <p>
                    Консултантски услуги за изготвяне на становища, анализи, договори, документи, предложения за промени в приложимата нормативна уредба, свързани със стопанисване, поддържане и експлоатация на ВиК системите и съоръженията и предоставяне на ВиК услугите за Обособена позиция 5: оказване на правно-консултантска и техническа подкрепа на МРРБ за изготвяне на становища, анализи, документи и предложения за промени в приложимата нормативна уредба, свързани с развитието и управлението на ВиК отрасъла	
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    Изготвяне на проект на нормативен акт - Наредба за сервитутите на водоснабдителните и канализационните проводи, мрежи и съоръжения		
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    22 008 лв.
                </p>
            </div>
            <div class="col-md-12">
              <p class="mb-0 text-end">
                <strong>Информация за поръчката:</strong> <a href="#" class="text-decoration-none" title="ЦАИС">ЦАИС</a>
            </p>
          </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="custom-card pt-1 pb-4 px-3 mb-3">
            <div class="row m-0">
                <div class="col-md-12 text-end p-0">
                  <a href="#"><i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                  </a>
                  <a href="#" title="Редактиране"> <i class="fas fa-pen-to-square float-end main-color fs-4"></i></a>
                </div>
            </div>
            <div class="row single-record">
                <div class="col-md-2">
                    <p>
                        <a href="#" class="main-color text-decoration-none">2. Министерство на регионалното развитие и благоустройството</a> 
                    </p>
                </div>
                <div class="col-md-2">
                    <p>
                        <a href="#" class="main-color text-decoration-none">ДЗЗД "Глобал Аквуекон"</a> 
                    </p>
                </div>
                <div class="col-md-1">
                    <p>
                        <a href="#" class="main-color text-decoration-none">177282392	</a>
                    </p>
                </div>
                <div class="col-md-1">
                    <p>
                        23.08.2018 г.	
                    </p>
                </div>
                <div class="col-md-3">
                    <p>
                        Консултантски услуги за изготвяне на становища, анализи, договори, документи, предложения за промени в приложимата нормативна уредба, свързани със стопанисване, поддържане и експлоатация на ВиК системите и съоръженията и предоставяне на ВиК услугите за Обособена позиция 5: оказване на правно-консултантска и техническа подкрепа на МРРБ за изготвяне на становища, анализи, документи и предложения за промени в приложимата нормативна уредба, свързани с развитието и управлението на ВиК отрасъла	
                    </p>
                </div>
                <div class="col-md-2">
                    <p>
                        Изготвяне на проект на нормативен акт - Наредба за изменение и допълнение на Наредба № 4 от 2004 г. за условията и реда за присъединяване на потребителите и за ползване на водоснабдителните и канализационните системи	                </p>
                </div>
                <div class="col-md-1">
                    <p>
                        27 678 лв.
                    </p>
                </div>
                <div class="col-md-12">
                  <p class="mb-0 text-end">
                    <strong>Информация за поръчката:</strong> <a href="#" class="text-decoration-none" title="ЦАИС">ЦАИС</a>
                </p>
              </div>
            </div>
        </div>
</div>




<div class="col-md-12">
    <div class="custom-card pt-1 pb-4 px-3 mb-3">
        <div class="row m-0">
            <div class="col-md-12 text-end p-0">
              <a href="#"><i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
              </a>
              <a href="#" title="Редактиране"> <i class="fas fa-pen-to-square float-end main-color fs-4"></i></a>
            </div>
        </div>
        <div class="row single-record">
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">3. Министерство на регионалното развитие и благоустройството</a> 
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">ДЗЗД "Глобал Аквуекон"		</a> 
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    <a href="#" class="main-color text-decoration-none">177282392	</a>
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    23.08.2018 г.	
                </p>
            </div>
            <div class="col-md-3">
                <p>
                    Консултантски услуги за изготвяне на становища, анализи, договори, документи, предложения за промени в приложимата нормативна уредба, свързани със стопанисване, поддържане и експлоатация на ВиК системите и съоръженията и предоставяне на ВиК услугите за Обособена позиция 5: оказване на правно-консултантска и техническа подкрепа на МРРБ за изготвяне на становища, анализи, документи и предложения за промени в приложимата нормативна уредба, свързани с развитието и управлението на ВиК отрасъла	
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    Изготвяне на проект на нормативен акт - Наредба за изменение и допълнение на Наредба № 4 от 2004 г. за условията и реда за присъединяване на потребителите и за ползване на водоснабдителните и канализационните системи	                </p>
            </div>
            <div class="col-md-1">
                <p>
                    27 678 лв.
                </p>
            </div>
            <div class="col-md-12">
              <p class="mb-0 text-end">
                <strong>Информация за поръчката:</strong> <a href="#" class="text-decoration-none" title="ЦАИС">ЦАИС</a>
            </p>
          </div>
        </div>
    </div>
</div>



<div class="col-md-12">
    <div class="custom-card pt-1 pb-4 px-3 mb-3">
        <div class="row m-0">
            <div class="col-md-12 text-end p-0">
              <a href="#"><i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
              </a>
              <a href="#" title="Редактиране"> <i class="fas fa-pen-to-square float-end main-color fs-4"></i></a>
            </div>
        </div>
        <div class="row single-record">
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">4. Министерство на регионалното развитие и благоустройството</a> 
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">ДЗЗД "Глобал Аквуекон"		</a> 
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    <a href="#" class="main-color text-decoration-none">177282392	</a>
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    23.08.2018 г.	
                </p>
            </div>
            <div class="col-md-3">
                <p>
                    Консултантски услуги за изготвяне на становища, анализи, договори, документи, предложения за промени в приложимата нормативна уредба, свързани със стопанисване, поддържане и експлоатация на ВиК системите и съоръженията и предоставяне на ВиК услугите за Обособена позиция 5: оказване на правно-консултантска и техническа подкрепа на МРРБ за изготвяне на становища, анализи, документи и предложения за промени в приложимата нормативна уредба, свързани с развитието и управлението на ВиК отрасъла	
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    Изготвяне на проект на нормативен акт - Наредба за изменение и допълнение на Наредба № 4 от 2004 г. за условията и реда за присъединяване на потребителите и за ползване на водоснабдителните и канализационните системи	                </p>
            </div>
            <div class="col-md-1">
                <p>
                    27 678 лв.
                </p>
            </div>
            <div class="col-md-12">
              <p class="mb-0 text-end">
                <strong>Информация за поръчката:</strong> <a href="#" class="text-decoration-none" title="ЦАИС">ЦАИС</a>
            </p>
          </div>
        </div>
    </div>
</div>


</div>

    <div class="row mb-5">
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