@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Законодателна инициатива')

@section('content')
<div class="row">



    <div class="col-lg-12 py-5">
        <h2 class="obj-title mb-4">
            Ръководство за извършване на предварителна оценка на въздействието
        </h2>
        <div class="row">
            <div class="col-md-8">
                <a href="#" class="text-decoration-none"><span class="obj-icon-info me-2"><i
                            class="far fa-calendar me-1 dark-blue" title="Дата на публикуване"></i>12.7.2023 г.</span>
                </a>

            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-sm btn-primary main-color">
                    <i class="fas fa-pen me-2 main-color"></i>Редактиране на ръкодоство
                </button>
                <button class="btn btn-sm btn-danger">
                    <i class="fas fa-regular fa-trash-can me-2 text-danger"></i>Изтриване на ръкодоство
                </button>
            </div>
        </div>
       
        <hr class="custom-hr my-4">
        <div class="row pdf-wrapper d-flex justify-content-center">
          <div class="col-md-10">
            <object
            data="/img/13-Rakovostvo.pdf"
            type="application/pdf"
            width="100%"
            height="800px"
         ></object> 
         <a href="#" class="btn btn-primary w-auto mt-4">Изтегляне</a>
          </div>         
        </div>

    </div>
</div>
</div>



</div>
</div>
</div>
</body>


@endsection
