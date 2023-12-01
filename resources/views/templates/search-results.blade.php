@extends('layouts.site', ['fullwidth' => true])


<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>
@section('pageTitle', 'Резултати от търсене')

@section('content')

<div class="row pt-5">
    <div class="col-md-12">
        <h2 class="mb-2">Резултати от вашето търсене:</h2>
    </div>
</div>

<div class="search-results-wrapper pt-3 pb-5">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="result-content">
                <div class="result-number-wrapper">
                    <span class="result-number fs-1 main-color">01</span>
                </div>
                <div class="result-heading-wrapper">
                    <h3 class="fs-5">Областна стратегия за развитие на Област Варна 2005 - 2015 г.</h3>
                </div>
                <div class="result-heading-wrapper">
                    <a href="#" class="text-decoration-none">Научете повече <i class="fas fa-solid fa-arrow-right main-color mx-2"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="result-content">
                <div class="result-number-wrapper">
                    <span class="result-number fs-1 main-color">02</span>
                </div>
                <div class="result-heading-wrapper">
                    <h3 class="fs-5">Oпростяване на данъчните документи за наемодатели</h3>
                </div>
                <div class="result-heading-wrapper">
                    <a href="#" class="text-decoration-none">Научете повече <i class="fas fa-solid fa-arrow-right main-color mx-2"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="result-content">
                <div class="result-number-wrapper">
                    <span class="result-number fs-1 main-color">03</span>
                </div>
                <div class="result-heading-wrapper">
                    <h3 class="fs-5">Упростяване на данъчните документи</h3>
                </div>
                <div class="result-heading-wrapper">
                    <a href="#" class="text-decoration-none">Научете повече <i class="fas fa-solid fa-arrow-right main-color mx-2"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="result-content">
                <div class="result-number-wrapper">
                    <span class="result-number fs-1 main-color">04</span>
                </div>
                <div class="result-heading-wrapper">
                    <h3 class="fs-5">Общински план за развитие на Община Якоруда 2007 - 2013 г.</h3>
                </div>
                <div class="result-heading-wrapper">
                    <a href="#" class="text-decoration-none">Научете повече <i class="fas fa-solid fa-arrow-right main-color mx-2"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="result-content">
                <div class="result-number-wrapper">
                    <span class="result-number fs-1 main-color">05</span>
                </div>
                <div class="result-heading-wrapper">
                    <h3 class="fs-5">Областна стратегия за развитие на Област Видин 2005 - 2015 г.</h3>
                </div>
                <div class="result-heading-wrapper">
                    <a href="#" class="text-decoration-none">Научете повече <i class="fas fa-solid fa-arrow-right main-color mx-2"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="result-content">
                <div class="result-number-wrapper">
                    <span class="result-number fs-1 main-color">06</span>
                </div>
                <div class="result-heading-wrapper">
                    <h3 class="fs-5">Проведе се обществено обсъждане на проектопрограмата за по-добро регулиране 2008-2009 г.</h3>
                </div>
                <div class="result-heading-wrapper">
                    <a href="#" class="text-decoration-none">Научете повече <i class="fas fa-solid fa-arrow-right main-color mx-2"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="result-content">
                <div class="result-number-wrapper">
                    <span class="result-number fs-1 main-color">07</span>
                </div>
                <div class="result-heading-wrapper">
                    <h3 class="fs-5">Общински план за развитие на Община Бобов дол 2007 - 2013 г.</h3>
                </div>
                <div class="result-heading-wrapper">
                    <a href="#" class="text-decoration-none">Научете повече <i class="fas fa-solid fa-arrow-right main-color mx-2"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="result-content">
                <div class="result-number-wrapper">
                    <span class="result-number fs-1 main-color">08</span>
                </div>
                <div class="result-heading-wrapper">
                    <h3 class="fs-5">Необходими документи за започване на работа</h3>
                </div>
                <div class="result-heading-wrapper">
                    <a href="#" class="text-decoration-none">Научете повече <i class="fas fa-solid fa-arrow-right main-color mx-2"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="result-content">
                <div class="result-number-wrapper">
                    <span class="result-number fs-1 main-color">09</span>
                </div>
                <div class="result-heading-wrapper">
                    <h3 class="fs-5">Общински план за развитие на Община Невестино 2007 - 2013 г.</h3>
                </div>
                <div class="result-heading-wrapper">
                    <a href="#" class="text-decoration-none">Научете повече <i class="fas fa-solid fa-arrow-right main-color mx-2"></i></a>
                </div>
            </div>
        </div>
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
                <li class="page-item"><a class="page-link" href="#">25</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">»</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
