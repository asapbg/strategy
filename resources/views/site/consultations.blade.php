@extends('layouts.site')

@section('pageTitle', trans_choice('custom.public_consultations', 2))

@section('content')
    <div class="col-lg-12  home-results home-results-two " style="padding: 0px !important;">

    <div class="row filter-results mb-2">
      <h2 class="mb-4">
        Търсене
      </h2>
      <div class="col-md-3">
        <div class="input-group ">
          <div class="mb-3 d-flex flex-column  w-100">
            <label for="exampleFormControlInput1" class="form-label">Тип консултации</label>
            <select class="form-select" aria-label="Default select example" >
              <option selected ="1">Всички</option>
              <option value="1">Национални</option>
              <option value="2">Областни и общински</option>  
            </select>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="input-group ">
          <div class="mb-3 d-flex flex-column  w-100">
            <label for="exampleFormControlInput1" class="form-label">Избор на тема</label>
            <select class="form-select" aria-label="Default select example" >
              <option selected ="1">Всички</option>
              <option value="2">Енергетика</option>
              <option value="3">Защита на потребителите</option>
              <option value="4">Здравеопазване</option>
            </select>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="input-group ">
          <div class="mb-3 d-flex flex-column  w-100">
            <label for="exampleFormControlInput1" class="form-label">Статут</label>
            <select class="form-select" aria-label="Default select example" >
              <option selected ="1">Всички</option>
              <option value="1">Открити</option>
              <option value="2">Приключили</option>
            </select>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="input-group ">
          <div class="mb-3 d-flex flex-column  w-100">
            <label for="exampleFormControlInput1" class="form-label">Сортиране</label>
            <select class="form-select" aria-label="Default select example">
              <option value="1">Най-нови</option>
              <option value="2">Най-стари</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
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

      <div class="row mt-5">
        <div class="col-md-12">
          <div class="consul-wrapper">
            <div class="single-consultation d-flex">
                <div class="consult-img-holder">
                  <i class="fa-solid fa-tractor gr-color"></i>
                </div>
                <div class="consult-body">
                  <a href="#" class="consul-item">
                    <p>
                      <span class="consul-cat">Земеделие и селски райони</span>
                    </p>

                    <h3>
                      Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.
                    </h3>

                    <div class="meta-consul">
                      <span class="text-secondary">
                        4.7.2023 г. -  3.8.2023 г. | 1 <i class="far fa-comment text-secondary"></i>
                      </span>

                      <i class="fas fa-arrow-right read-more"></i>
                    </div>
                  </a>

                </div>
            </div>
        </div>
        </div>
      </div>


      <div class="row">
        <div class="col-md-12">
          <div class="consul-wrapper">
            <div class="single-consultation d-flex">
                <div class="consult-img-holder">
                  <i class="fa-solid fa-euro-sign light-blue"></i>
                </div>
                <div class="consult-body">
                  <a href="#" class="consul-item">
                    <p>
                      <span class="consul-cat">Финанси и данъчна политика</span>
                    </p>

                    <h3>
                      Проект на заповед, която се издава от директора на Агенция ,,Митници“ на основание чл. 66б, ал. 2 от Закона за митниците
                    </h3>

                    <div class="meta-consul">
                      <span class="text-secondary">
                        30.06.2023 г. -  30.07.2023 г. | 5 <i class="far fa-comment text-secondary"></i>
                      </span>

                      <i class="fas fa-arrow-right read-more"></i>
                    </div>
                  </a>

                </div>
            </div>
        </div>
        </div>
      </div>



      <div class="row">
        <div class="col-md-12">
          <div class="consul-wrapper">
            <div class="single-consultation d-flex">
                <div class="consult-img-holder">
                  <i class="fa-solid fa-user-tie dark-blue"></i>
                </div>
                <div class="consult-body">
                  <a href="#" class="consul-item">
                    <p>
                      <span class="consul-cat">Бизнес среда</span>
                    </p>

                    <h3>
                      Проект на Закон за изменение и допълнение на Закона за адвокатурата
                    </h3>

                    <div class="meta-consul">
                      
                        <span class="text-secondary">
                          03.07.2023 г. -  04.08.2023 г. | 12 <i class="far fa-comment text-secondary"></i>
                        </span>
                    

                      <div>
                        <i class="fas fa-arrow-right read-more"></i>
                      </div>
                    
                    </div>
                  </a>

                </div>
            </div>
        </div>
        </div>
      </div>

      
      <div class="row ">
        <div class="col-md-12">
          <div class="consul-wrapper">
            <div class="single-consultation d-flex">
                <div class="consult-img-holder">
                  <i class="fa-solid fa-leaf gr-color"></i>
                </div>
                <div class="consult-body">
                  <a href="#" class="consul-item">
                    <p>
                      <span class="consul-cat">Околна среда</span>
                    </p>

                    <h3>
                      Проект на Постановление на Министерския съвет за създаване на Консултативен съвет за Европейската зелена сделка
                    </h3>

                    <div class="meta-consul">
                      <div>
                        <span class="text-secondary">
                          30.06.2023 г. -  14.07.2023 г. | 2 <i class="far fa-comment text-secondary"></i>
                        </span>
                      </div>
                      <div>
                        <i class="fas fa-arrow-right read-more"></i>
                      </div>                   
                    </div>
                  </a>
                </div>
            </div>
        </div>
        </div>
      </div>

      <div class="row">
        <nav aria-label="Page navigation example">
          <ul class="pagination m-0">
            <li class="page-item">
              <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
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
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>

      <div class="row">
        <div class="col-md-8">
          <button class="btn rss-sub main-color">Всички консултации <i class="fas fa-long-arrow-right main-color"></i></button>
        </div>
    </div>


      </div>
  </div>
</div>
@endsection