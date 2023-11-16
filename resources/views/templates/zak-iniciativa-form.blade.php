@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Законодателна инициатива')

@section('content')
<section class="public-page">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">

                <div class="left-nav-panel" style="background: #fff !important;">
                    <div class="flex-shrink-0 p-2">
                        <ul class="list-unstyled">
                            <li class="mb-1">
                                <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                                    data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                                    <i class="fa-solid fa-bars me-2 mb-2"></i>Гражданско участие
                                </a>
                                <hr class="custom-hr">
                                <div class="collapse show mt-3" id="home-collapse">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                        <li class="mb-2  active-item-left p-1"><a href="#"
                                                class="link-dark text-decoration-none">Законодателни инициативи</a>
                                        </li>
                                        <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Отворено
                                                управление</a>
                                        </li>
                                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                            <ul class="list-unstyled ps-3">
                                                <hr class="custom-hr">
                                                <li class="my-2"><a href="#"
                                                        class="link-dark  text-decoration-none">Планове
                                                    </a></li>
                                                <hr class="custom-hr">
                                                <li class="my-2"><a href="#"
                                                        class="link-dark  text-decoration-none">Отчети</a>
                                                </li>
                                                <hr class="custom-hr">
                                            </ul>
                                        </ul>

                                        <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Анкети</a>
                                        </li>


                                    </ul>
                                </div>
                            </li>
                            <hr class="custom-hr">
                        </ul>
                    </div>
                </div>

            </div>


            <div class="col-lg-10">
                <div class="col-md-12 col-lg-8">
                    <h1 class="h2 mb-4">Submit issue</h1>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Your name">
                    </div>
        
                    <div class="form-group">
                      <label for="email">Email address</label>
                      <input type="email" class="form-control" id="email" placeholder="Enter email">
                      <small class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
        
                    <div class="form-group">
                      <label>Describe the condition in detail</label>
                      <textarea id="editor"></textarea>
                    </div>
        
                    <div class="form-group">
                        <label for="phone">Primary phone number</label>
                        <input type="text" class="form-control" id="phone" placeholder="">
                    </div>
        
                    <hr>
        
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="terms">
                        <label class="form-check-label" for="terms">I agree to the <a href="#">terms and conditions</a></label>
                    </div>
        
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>



            </div>
        </div>


    </div>
    </div>
</section>
</body>


@endsection
<script>
           tinymce.init({
            selector:'#editor',
            menubar: false,
            statusbar: false,
            plugins: 'autoresize anchor autolink charmap code codesample directionality fullpage help hr image imagetools insertdatetime link lists media nonbreaking pagebreak preview print searchreplace table template textpattern toc visualblocks visualchars',
            toolbar: 'h1 h2 bold italic blockquote bullist numlist backcolor| removeformat fullscreen align',
            skin: 'bootstrap',
            toolbar_drawer: 'floating',
            min_height: 200,           
            autoresize_bottom_margin: 16,
            setup: (editor) => {
                editor.on('init', () => {
                    editor.getContainer().style.transition="border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out"
                });
                editor.on('focus', () => {
                    editor.getContainer().style.boxShadow="0 0 0 .2rem rgba(0, 123, 255, .25)",
                    editor.getContainer().style.borderColor="#80bdff"
                });
                editor.on('blur', () => {
                    editor.getContainer().style.boxShadow="",
                    editor.getContainer().style.borderColor=""
                });
            }
        });
</script>