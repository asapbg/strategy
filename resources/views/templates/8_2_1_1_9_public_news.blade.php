@extends('layouts.site')

@section('pageTitle', 'Новини')

@section('content')
<h2 class="obj-title mb-4">Удостоверение за наследници няма да се изисква пред Службите по кадастър, НОИ и Агенцията по
    горите</h2>
<div class="row">
    <div class="col-md-8">
        <a href="#" class="text-decoration-none"><span class="obj-icon-info me-2"><i class="far fa-calendar me-1 dark-blue"
                    title="Дата на публикуване"></i>12.7.2023 г.</span>
        </a>
        <a href="#" class="text-decoration-none">
            <span class="obj-icon-info me-2"><i class="fas fa-sitemap me-1 dark-blue"
                    title="Сфера на действие"></i>Държавна администрация</span>

        </a>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-sm btn-primary main-color">
            <i class="fas fa-pen me-2 main-color"></i>Редактиране на новина
        </button>
        <button class="btn btn-sm btn-danger">
            <i class="fas fa-regular fa-trash-can me-2 text-danger"></i>Изтриване на новина
        </button>
    </div>
</div>
<hr>
<div>
    Удостоверение за наследници няма да се изисква от гражданите от Службите по геодезия, картография и кадастър,
    Националния осигурителен институт (НОИ) и Изпълнителна агенция по горите. Администрациите ще си набавят по служебен
    път необходимата им информация чрез реализираната от Министерство на електронното управление (МЕУ) нова вътрешна
    услуга. Тя се предоставя през Системата за сигурно електронно връчване на МЕУ.<br>
    Новата услуга е създадена в изпълнение на Закона за електронно управление и цели намаляване на административната
    тежест за гражданите и бизнеса.<br>
    Източник: <a href="">Министерство на електронното управление</a>
</div>
<a class="btn btn-primary mt-4" href="#">Обратно към списъка с новини</a>
@endsection
