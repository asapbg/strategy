@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }
</style>

@section('pageTitle', 'Законодателна програма 1 юли - 31 септември')

@section('content')
    <section>
        <div class="container-fluid p-0">
            <div class="row breadcrumbs py-1">
                <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Начало</a></li>
                        <li class="breadcrumb-item"><a href="#">Планиране</a></li>
                        <li class="breadcrumb-item"><a href="#">Законодателна програма</a></li>
                        <li class="breadcrumb-item"><a href="#">Законодателна програма 1 юли - 31 септември </a></li>
                    </ol>
                    </ol>
                </nav>
            </div>
    </section>


    <section class="public-page">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-2 side-menu pt-5 mt-1" style="background:#f5f9fd;">

                    <div class="left-nav-panel" style="background: #fff !important;">
                        <div class="flex-shrink-0 p-2">
                            <ul class="list-unstyled">
                                <li class="mb-1">
                                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                                        data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                                        <i class="fa-solid fa-bars me-2 mb-2"></i>Начало
                                    </a>
                                    <hr class="custom-hr">
                                    <div class="collapse show mt-3" id="home-collapse">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                                            <li class="mb-2  active-item-left p-1"><a href="#"
                                                    class="link-dark text-decoration-none">Планиране</a></li>
                                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                                <ul class="list-unstyled ps-3">
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="text-white active-item-left p-1  text-decoration-none">Законодателна
                                                            програма</a></li>
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="link-dark  text-decoration-none">Оперативна програма</a>
                                                    </li>
                                                    <hr class="custom-hr">
                                                </ul>
                                            </ul>

                                            <li class="mb-2"><a href="#"
                                                    class="link-dark text-decoration-none">Актове на МС</a></li>
                                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1">
                                                <ul class="list-unstyled ps-3">
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="link-dark  text-decoration-none">Постановления</a></li>
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="link-dark  text-decoration-none">Решения</a></li>
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="link-dark  text-decoration-none">Становища</a></li>
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="link-dark  text-decoration-none">Протоколи</a></li>
                                                    <hr class="custom-hr">
                                                </ul>
                                            </ul>

                                </li>
                                <li class="mb-2"><a href="#" class="link-dark  text-decoration-none">Архив</a></li>
                            </ul>
                        </div>
                        </li>
                        <hr class="custom-hr">
                        </ul>
                    </div>
                </div>

            </div>


            <div class="col-lg-10  home-results home-results-two pris-list mt-5 mb-5">           
              <ul class=" tab nav nav-tabs mb-3">
                <li class="nav-item pb-0">
                  <a class="nav-link tablinks" aria-current="page" href="#" onclick="openCity(event, 'July')" id="defaultOpen">Юли</a>
                </li>
                <li class="nav-item pb-0">
                  <a class="nav-link tablinks" href="#" onclick="openCity(event, 'August')">Август</a>
                </li>
                <li class="nav-item pb-0">
                  <a class="nav-link tablinks" href="#" onclick="openCity(event, 'September')">Септември</a>
                </li>
              </ul>

                <div id="July" class="tabcontent">


                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button text-dark fs-18 fw-600" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                  <div class="custom-card py-4 px-3 mb-5">

  
                                    <div class="row mb-3 mt-1 ">
                                      <div class="col-md-6">
                                        <p class="fw-bold fs-18 mb-1">
                                          Наименование на законопроекта
                                        </p>
                              
                                        <p>
                                          Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване 
                                        </p>
                                      </div>
                              
                                      <div class="col-md-6">
                                        <p class="fw-bold fs-18 mb-1">
                                          Вносител
                                        </p>
                              
                                        <p>
                                          Агенция за държавна финансова инспекция
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                                    <div class="row mb-3">
                                      <div class="col-md-12">
                                        <p class="fw-bold fs-18 mb-1">
                                          Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)
                                        </p>
                              
                                        <p>
                                          Да / Mярка № 87
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                                    <div class="row mb-3">
                                      <div class="col-md-12">
                                        <p class="fw-bold fs-18 mb-1">
                                          Цели, основни положения и очаквани резултати
                            
                                        </p>
                              
                                        <p>
                                          С проекта на ЗИД на КСО се извършват
                                          промени в регламентацията на допълнителното
                                          пенсионно осигуряване, като част от тях са
                                          свързани с изпълнение на мерките за засилване
                                          на надзора и регулациите в областта на
                                          небанковия финансов сектор, залегнали в
                                          Националната програма за реформи. 
                                          
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                            
                                    <div class="row mb-3">
                                      <div class="col-md-6 ">
                                        <p class="fw-bold fs-18 mb-1">
                                          Изготвяне на цялостна оценка на въздействието (да/не)
                                        </p>
                              
                                        <p>
                                          Да
                                        </p>
                                      </div>
                              
                                      <div class="col-md-6">
                                        <p class="fw-bold fs-18 mb-1">
                                          Месец на публикуване за обществени консултации
                                        </p>
                              
                                        <p>
                                          юли 2023г.
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                            
                                    <div class="row mb-3">
                                      <div class="col-md-6 ">
                                        <p class="fw-bold fs-18 mb-1">
                                          Месец на изпращане за предварително съгласуване
                            
                                        </p>
                              
                                        <p>
                                          Да / Mярка № 87
                                        </p>
                                      </div>
                              
                                      <div class="col-md-6 ">
                                        <p class="fw-bold fs-18 mb-1">
                                          Месец на внасяне в Министерския съвет
                            
                                        </p>
                              
                                        <p>
                                          юли 2023г.
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                                    <div class="row mb-3">
                                      <div class="col-md-12">
                                        <p class="fw-bold fs-18 mb-1">
                                          Необходими промени в други закони
                                        </p>
                              
                                        <p>
                                          Не
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                                    
                                    <div class="row mb-3">
                                      <div class="col-md-6 ">
                                        <p class="fw-bold fs-18 mb-1">
                                          Оценка на въздействието
                            
                                        </p>
                              
                                        <p class="mb-0">
                                          <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                        </p>
                                      </div>
                              
                                      <div class="col-md-6">
                                        <p class="fw-bold fs-18 mb-1">
                                          Становище
                                        </p>
                              
                                        <p class="mb-0">
                                          <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                        </p>
                                      </div>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed fs-18 fw-600" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                  <div class="custom-card py-4 px-3 mb-5">                           
                                    <div class="row mb-3 mt-1 ">
                                      <div class="col-md-6">
                                        <p class="fw-bold fs-18 mb-1">
                                          Наименование на законопроекта
                                        </p>
                              
                                        <p>
                                          ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия                                        </p>
                                      </div>
                              
                                      <div class="col-md-6">
                                        <p class="fw-bold fs-18 mb-1">
                                          Вносител
                                        </p>
                              
                                        <p>
                                          Агенция за държавна финансова инспекция
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                                    <div class="row mb-3">
                                      <div class="col-md-12">
                                        <p class="fw-bold fs-18 mb-1">
                                          Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)
                                        </p>
                              
                                        <p>
                                          Да / Mярка № 87
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                                    <div class="row mb-3">
                                      <div class="col-md-12">
                                        <p class="fw-bold fs-18 mb-1">
                                          Цели, основни положения и очаквани резултати
                            
                                        </p>
                              
                                        <p>
                                          С проекта на ЗИД на КСО се извършват
                                          промени в регламентацията на допълнителното
                                          пенсионно осигуряване, като част от тях са
                                          свързани с изпълнение на мерките за засилване
                                          на надзора и регулациите в областта на
                                          небанковия финансов сектор, залегнали в
                                          Националната програма за реформи. 
                                          
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                            
                                    <div class="row mb-3">
                                      <div class="col-md-6 ">
                                        <p class="fw-bold fs-18 mb-1">
                                          Изготвяне на цялостна оценка на въздействието (да/не)
                                        </p>
                              
                                        <p>
                                          Да
                                        </p>
                                      </div>
                              
                                      <div class="col-md-6">
                                        <p class="fw-bold fs-18 mb-1">
                                          Месец на публикуване за обществени консултации
                                        </p>
                              
                                        <p>
                                          юли 2023г.
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                            
                                    <div class="row mb-3">
                                      <div class="col-md-6 ">
                                        <p class="fw-bold fs-18 mb-1">
                                          Месец на изпращане за предварително съгласуване
                            
                                        </p>
                              
                                        <p>
                                          Да / Mярка № 87
                                        </p>
                                      </div>
                              
                                      <div class="col-md-6 ">
                                        <p class="fw-bold fs-18 mb-1">
                                          Месец на внасяне в Министерския съвет
                            
                                        </p>
                              
                                        <p>
                                          юли 2023г.
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                                    <div class="row mb-3">
                                      <div class="col-md-12">
                                        <p class="fw-bold fs-18 mb-1">
                                          Необходими промени в други закони
                                        </p>
                              
                                        <p>
                                          Не
                                        </p>
                                      </div>
                                      <hr class="custom-hr">
                                    </div>
                            
                                    
                                    <div class="row mb-3">
                                      <div class="col-md-6 ">
                                        <p class="fw-bold fs-18 mb-1">
                                          Оценка на въздействието
                            
                                        </p>
                              
                                        <p class="mb-0">
                                          <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                        </p>
                                      </div>
                              
                                      <div class="col-md-6">
                                        <p class="fw-bold fs-18 mb-1">
                                          Становище
                                        </p>
                              
                                        <p class="mb-0">
                                          <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                        </p>
                                      </div>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

             
                <div id="August" class="tabcontent">


                  <div class="accordion" id="accordionExample">
                      <div class="accordion-item">
                          <h2 class="accordion-header" id="headingOne">
                              <button class="accordion-button text-dark fs-18 fw-600" type="button" data-bs-toggle="collapse"
                                  data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                  АВГУСТ - Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване
                              </button>
                          </h2>
                          <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                              data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                <div class="custom-card py-4 px-3 mb-5">


                                  <div class="row mb-3 mt-1 ">
                                    <div class="col-md-6">
                                      <p class="fw-bold fs-18 mb-1">
                                        Наименование на законопроекта
                                      </p>
                            
                                      <p>
                                       АВГУСТ - Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване 
                                      </p>
                                    </div>
                            
                                    <div class="col-md-6">
                                      <p class="fw-bold fs-18 mb-1">
                                        Вносител
                                      </p>
                            
                                      <p>
                                        Агенция за държавна финансова инспекция
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                                  <div class="row mb-3">
                                    <div class="col-md-12">
                                      <p class="fw-bold fs-18 mb-1">
                                        Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)
                                      </p>
                            
                                      <p>
                                        Да / Mярка № 87
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                                  <div class="row mb-3">
                                    <div class="col-md-12">
                                      <p class="fw-bold fs-18 mb-1">
                                        Цели, основни положения и очаквани резултати
                          
                                      </p>
                            
                                      <p>
                                        С проекта на ЗИД на КСО се извършват
                                        промени в регламентацията на допълнителното
                                        пенсионно осигуряване, като част от тях са
                                        свързани с изпълнение на мерките за засилване
                                        на надзора и регулациите в областта на
                                        небанковия финансов сектор, залегнали в
                                        Националната програма за реформи. 
                                        
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                          
                                  <div class="row mb-3">
                                    <div class="col-md-6 ">
                                      <p class="fw-bold fs-18 mb-1">
                                        Изготвяне на цялостна оценка на въздействието (да/не)
                                      </p>
                            
                                      <p>
                                        Да
                                      </p>
                                    </div>
                            
                                    <div class="col-md-6">
                                      <p class="fw-bold fs-18 mb-1">
                                        Месец на публикуване за обществени консултации
                                      </p>
                            
                                      <p>
                                        юли 2023г.
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                          
                                  <div class="row mb-3">
                                    <div class="col-md-6 ">
                                      <p class="fw-bold fs-18 mb-1">
                                        Месец на изпращане за предварително съгласуване
                          
                                      </p>
                            
                                      <p>
                                        Да / Mярка № 87
                                      </p>
                                    </div>
                            
                                    <div class="col-md-6 ">
                                      <p class="fw-bold fs-18 mb-1">
                                        Месец на внасяне в Министерския съвет
                          
                                      </p>
                            
                                      <p>
                                        юли 2023г.
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                                  <div class="row mb-3">
                                    <div class="col-md-12">
                                      <p class="fw-bold fs-18 mb-1">
                                        Необходими промени в други закони
                                      </p>
                            
                                      <p>
                                        Не
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                                  
                                  <div class="row mb-3">
                                    <div class="col-md-6 ">
                                      <p class="fw-bold fs-18 mb-1">
                                        Оценка на въздействието
                          
                                      </p>
                            
                                      <p class="mb-0">
                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                      </p>
                                    </div>
                            
                                    <div class="col-md-6">
                                      <p class="fw-bold fs-18 mb-1">
                                        Становище
                                      </p>
                            
                                      <p class="mb-0">
                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                      </p>
                                    </div>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                              </div>
                          </div>
                      </div>
                      <div class="accordion-item">
                          <h2 class="accordion-header" id="headingTwo">
                              <button class="accordion-button collapsed fs-18 fw-600" type="button" data-bs-toggle="collapse"
                                  data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                  АВГУСТ - ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия
                              </button>
                          </h2>
                          <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                              data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                <div class="custom-card py-4 px-3 mb-5">                           
                                  <div class="row mb-3 mt-1 ">
                                    <div class="col-md-6">
                                      <p class="fw-bold fs-18 mb-1">
                                        Наименование на законопроекта
                                      </p>
                            
                                      <p>
                                       АВГУСТ - ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия                                        </p>
                                    </div>
                            
                                    <div class="col-md-6">
                                      <p class="fw-bold fs-18 mb-1">
                                        Вносител
                                      </p>
                            
                                      <p>
                                        Агенция за държавна финансова инспекция
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                                  <div class="row mb-3">
                                    <div class="col-md-12">
                                      <p class="fw-bold fs-18 mb-1">
                                        Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)
                                      </p>
                            
                                      <p>
                                        Да / Mярка № 87
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                                  <div class="row mb-3">
                                    <div class="col-md-12">
                                      <p class="fw-bold fs-18 mb-1">
                                        Цели, основни положения и очаквани резултати
                          
                                      </p>
                            
                                      <p>
                                        С проекта на ЗИД на КСО се извършват
                                        промени в регламентацията на допълнителното
                                        пенсионно осигуряване, като част от тях са
                                        свързани с изпълнение на мерките за засилване
                                        на надзора и регулациите в областта на
                                        небанковия финансов сектор, залегнали в
                                        Националната програма за реформи. 
                                        
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                          
                                  <div class="row mb-3">
                                    <div class="col-md-6 ">
                                      <p class="fw-bold fs-18 mb-1">
                                        Изготвяне на цялостна оценка на въздействието (да/не)
                                      </p>
                            
                                      <p>
                                        Да
                                      </p>
                                    </div>
                            
                                    <div class="col-md-6">
                                      <p class="fw-bold fs-18 mb-1">
                                        Месец на публикуване за обществени консултации
                                      </p>
                            
                                      <p>
                                        юли 2023г.
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                          
                                  <div class="row mb-3">
                                    <div class="col-md-6 ">
                                      <p class="fw-bold fs-18 mb-1">
                                        Месец на изпращане за предварително съгласуване
                          
                                      </p>
                            
                                      <p>
                                        Да / Mярка № 87
                                      </p>
                                    </div>
                            
                                    <div class="col-md-6 ">
                                      <p class="fw-bold fs-18 mb-1">
                                        Месец на внасяне в Министерския съвет
                          
                                      </p>
                            
                                      <p>
                                        юли 2023г.
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                                  <div class="row mb-3">
                                    <div class="col-md-12">
                                      <p class="fw-bold fs-18 mb-1">
                                        Необходими промени в други закони
                                      </p>
                            
                                      <p>
                                        Не
                                      </p>
                                    </div>
                                    <hr class="custom-hr">
                                  </div>
                          
                                  
                                  <div class="row mb-3">
                                    <div class="col-md-6 ">
                                      <p class="fw-bold fs-18 mb-1">
                                        Оценка на въздействието
                          
                                      </p>
                            
                                      <p class="mb-0">
                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                      </p>
                                    </div>
                            
                                    <div class="col-md-6">
                                      <p class="fw-bold fs-18 mb-1">
                                        Становище
                                      </p>
                            
                                      <p class="mb-0">
                                        <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                      </p>
                                    </div>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>

              </div>

                 
              <div id="September" class="tabcontent">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button text-dark fs-18 fw-600" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                СЕПТВЕМВРИ - Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                              <div class="custom-card py-4 px-3 mb-5">


                                <div class="row mb-3 mt-1 ">
                                  <div class="col-md-6">
                                    <p class="fw-bold fs-18 mb-1">
                                      Наименование на законопроекта
                                    </p>
                          
                                    <p>
                                      СЕПТВЕМВРИ  - Проект на Закон за изменение и допълнение на Кодекса за търговското корабоплаване 
                                    </p>
                                  </div>
                          
                                  <div class="col-md-6">
                                    <p class="fw-bold fs-18 mb-1">
                                      Вносител
                                    </p>
                          
                                    <p>
                                      Агенция за държавна финансова инспекция
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                                <div class="row mb-3">
                                  <div class="col-md-12">
                                    <p class="fw-bold fs-18 mb-1">
                                      Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)
                                    </p>
                          
                                    <p>
                                      Да / Mярка № 87
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                                <div class="row mb-3">
                                  <div class="col-md-12">
                                    <p class="fw-bold fs-18 mb-1">
                                      Цели, основни положения и очаквани резултати
                        
                                    </p>
                          
                                    <p>
                                      С проекта на ЗИД на КСО се извършват
                                      промени в регламентацията на допълнителното
                                      пенсионно осигуряване, като част от тях са
                                      свързани с изпълнение на мерките за засилване
                                      на надзора и регулациите в областта на
                                      небанковия финансов сектор, залегнали в
                                      Националната програма за реформи. 
                                      
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                        
                                <div class="row mb-3">
                                  <div class="col-md-6 ">
                                    <p class="fw-bold fs-18 mb-1">
                                      Изготвяне на цялостна оценка на въздействието (да/не)
                                    </p>
                          
                                    <p>
                                      Да
                                    </p>
                                  </div>
                          
                                  <div class="col-md-6">
                                    <p class="fw-bold fs-18 mb-1">
                                      Месец на публикуване за обществени консултации
                                    </p>
                          
                                    <p>
                                      юли 2023г.
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                        
                                <div class="row mb-3">
                                  <div class="col-md-6 ">
                                    <p class="fw-bold fs-18 mb-1">
                                      Месец на изпращане за предварително съгласуване
                        
                                    </p>
                          
                                    <p>
                                      Да / Mярка № 87
                                    </p>
                                  </div>
                          
                                  <div class="col-md-6 ">
                                    <p class="fw-bold fs-18 mb-1">
                                      Месец на внасяне в Министерския съвет
                        
                                    </p>
                          
                                    <p>
                                      юли 2023г.
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                                <div class="row mb-3">
                                  <div class="col-md-12">
                                    <p class="fw-bold fs-18 mb-1">
                                      Необходими промени в други закони
                                    </p>
                          
                                    <p>
                                      Не
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                                
                                <div class="row mb-3">
                                  <div class="col-md-6 ">
                                    <p class="fw-bold fs-18 mb-1">
                                      Оценка на въздействието
                        
                                    </p>
                          
                                    <p class="mb-0">
                                      <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                    </p>
                                  </div>
                          
                                  <div class="col-md-6">
                                    <p class="fw-bold fs-18 mb-1">
                                      Становище
                                    </p>
                          
                                    <p class="mb-0">
                                      <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                    </p>
                                  </div>
                                </div>
                                <hr class="custom-hr">
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fs-18 fw-600" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                СЕПТВЕМВРИ  - ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                              <div class="custom-card py-4 px-3 mb-5">                           
                                <div class="row mb-3 mt-1 ">
                                  <div class="col-md-6">
                                    <p class="fw-bold fs-18 mb-1">
                                      Наименование на законопроекта
                                    </p>
                          
                                    <p>
                                      СЕПТВЕМВРИ  - ЗИД на Закона за тютюна, тютюневите и свързаните с тях изделия                                        </p>
                                  </div>
                          
                                  <div class="col-md-6">
                                    <p class="fw-bold fs-18 mb-1">
                                      Вносител
                                    </p>
                          
                                    <p>
                                      Агенция за държавна финансова инспекция
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                                <div class="row mb-3">
                                  <div class="col-md-12">
                                    <p class="fw-bold fs-18 mb-1">
                                      Включен в Плана за действие с мерките, произтичащи от членството на РБ в ЕС (№ в плана/не)
                                    </p>
                          
                                    <p>
                                      Да / Mярка № 87
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                                <div class="row mb-3">
                                  <div class="col-md-12">
                                    <p class="fw-bold fs-18 mb-1">
                                      Цели, основни положения и очаквани резултати
                        
                                    </p>
                          
                                    <p>
                                      С проекта на ЗИД на КСО се извършват
                                      промени в регламентацията на допълнителното
                                      пенсионно осигуряване, като част от тях са
                                      свързани с изпълнение на мерките за засилване
                                      на надзора и регулациите в областта на
                                      небанковия финансов сектор, залегнали в
                                      Националната програма за реформи. 
                                      
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                        
                                <div class="row mb-3">
                                  <div class="col-md-6 ">
                                    <p class="fw-bold fs-18 mb-1">
                                      Изготвяне на цялостна оценка на въздействието (да/не)
                                    </p>
                          
                                    <p>
                                      Да
                                    </p>
                                  </div>
                          
                                  <div class="col-md-6">
                                    <p class="fw-bold fs-18 mb-1">
                                      Месец на публикуване за обществени консултации
                                    </p>
                          
                                    <p>
                                      юли 2023г.
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                        
                                <div class="row mb-3">
                                  <div class="col-md-6 ">
                                    <p class="fw-bold fs-18 mb-1">
                                      Месец на изпращане за предварително съгласуване
                        
                                    </p>
                          
                                    <p>
                                      Да / Mярка № 87
                                    </p>
                                  </div>
                          
                                  <div class="col-md-6 ">
                                    <p class="fw-bold fs-18 mb-1">
                                      Месец на внасяне в Министерския съвет
                        
                                    </p>
                          
                                    <p>
                                      юли 2023г.
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                                <div class="row mb-3">
                                  <div class="col-md-12">
                                    <p class="fw-bold fs-18 mb-1">
                                      Необходими промени в други закони
                                    </p>
                          
                                    <p>
                                      Не
                                    </p>
                                  </div>
                                  <hr class="custom-hr">
                                </div>
                        
                                
                                <div class="row mb-3">
                                  <div class="col-md-6 ">
                                    <p class="fw-bold fs-18 mb-1">
                                      Оценка на въздействието
                        
                                    </p>
                          
                                    <p class="mb-0">
                                      <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                    </p>
                                  </div>
                          
                                  <div class="col-md-6">
                                    <p class="fw-bold fs-18 mb-1">
                                      Становище
                                    </p>
                          
                                    <p class="mb-0">
                                      <a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Изтегляне</a>
                                    </p>
                                  </div>
                                </div>
                                <hr class="custom-hr">
                              </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            </div>
        </div>


        </div>
        </div>
    </section>


    </body>
    <script>
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>

@endsection
