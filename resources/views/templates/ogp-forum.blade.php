@extends('layouts.site', ['fullwidth' => true])
<style>
     .public-page {
        padding: 0px 0px !important;
    }
</style>
@section('pageTitle', 'OGP Forum')



@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Партньорство за открито управление
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2"><a href="#"
                                        class="link-dark text-decoration-none">Национални планове за действие</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Оценка за изпълнението на плановете за действие - мониторинг</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Разработване на нов план за действие</a>
                                </li>
                                <li class="mb-2 active-item-left p-1"><a href="#" class="link-dark text-decoration-none">OGP FORUM</a>
                                </li> 
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Новини и събития</a>
                                </li>                             
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>


    <div class="col-lg-10 py-5 right-side-content">
       
    <div class="row align-items-center justify-content-center">
        <div class="col-md-8 col-sm-12">
            <div class="row">
                <div class="col-md-6">
                    <a href="#" class="box-link gr-color-bgr mb-4">
                        <div class="info-box">
                            <div class="icon-wrap mt-2">
                                <i class="bi bi-diagram-3 text-light"></i>
                            </div>
                            <div class="link-heading">
                                <span class="fs-5 p-2">
                                    Правомощия, органи и структура <br>на Форума
                                </span>
                            </div>
                        </div>
                    </a>
                    
            <a href="#" class="box-link navy-marine-bgr mb-4">
                <div class="info-box">
                    <div class="icon-wrap mt-2">
                        <i class="bi bi-people text-light"></i>
                    </div>
                    <div class="link-heading">
                        <span class="fs-5 p-2">
                            Работни <br>групи 
                        </span>
                    </div>
                </div>
            </a>
                </div>
                <div class="col-md-6">
                    <a href="#" class="box-link light-blue-bgr mb-4">
                        <div class="info-box">
                            <div class="icon-wrap mt-2">
                                <i class="bi bi-file-earmark-text text-light"></i>
                            </div>
                            <div class="link-heading">
                                <span class="fs-5 p-2">
                                   Протоколи от заседания <br>и срещи
                                </span>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="box-link dark-blue-bgr mb-4">
                        <div class="info-box">
                            <div class="icon-wrap mt-2">
                                <i class="bi bi-info-circle text-light"></i>
                            </div>
                            <div class="link-heading">
                                <span class="fs-5 p-2">
                                    Членове на Форума – контактна <br>информация
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

  

        </div>
    </div>      
           
        
    </div>
</div>
@endsection
