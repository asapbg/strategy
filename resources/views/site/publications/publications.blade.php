@foreach($publications as $publication)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="consul-wrapper">
                <div class="single-library d-flex">


                    <div class="col-lg-10 py-5 right-side-content">
                        <h2 class="obj-title mb-4">{{ $publication->translation->title }}</h2>
                        <div class="row">
                            <div class="col-md-8">
                                <a href="#" class="text-decoration-none">
                                <span class="obj-icon-info me-2">
                                    <i class="far fa-calendar me-1 dark-blue" title="Дата на публикуване"></i>{{ displayDate($publication->published_at) }} г.
                                </span>
                                </a>
                                <a href="#" class="text-decoration-none">
                                <span class="obj-icon-info me-2">
                                    <i class="fas fa-sitemap me-1 dark-blue" title="Област на политика"></i>{{ $publication->category?->name }}
                                </span>
                                </a>
                            </div>
                            <div class="col-md-4 text-end">
                                @can('update', $publication)
                                    <a href="{{ route('admin.publications.edit' , [$publication->id]) }}" class="btn btn-sm btn-primary main-color">
                                        <i class="fas fa-pen me-2 main-color"></i>Редактиране на публикация
                                    </a>
                                @endcan
                                @can('delete', $publication)
                                    <a href="javascript:;"
                                       class="btn btn-sm btn-danger"
                                       data-target="#modal-delete-resource"
                                       data-resource-id="{{ $publication->id }}"
                                       data-resource-name="{{ $publication->title }}"
                                       data-resource-delete-url="{{ route('admin.publications.delete', $publication) }}"
                                       data-toggle="tooltip"
                                       title="{{ __('custom.delete') }}">
                                        <i class="fas fa-regular fa-trash-can me-2 text-danger"></i>Изтриване на публикация
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <hr>
                        <div>
                            {!! $publication->translation->content !!}
                            <a href="">Министерство на електронното управление</a>
                        </div>
                        <a class="btn btn-primary mt-4 mb-5" href="{{ route('library.news') }}">Обратно към списъка с новини</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
