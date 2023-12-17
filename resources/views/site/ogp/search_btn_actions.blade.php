<div class="row mb-5 action-btn-wrapper">
    <div class="col-md-3 col-sm-12">
        <button class="btn rss-sub main-color" id="searchBtn"><i class="fas fa-search main-color"></i>Търсене</button>
    </div>
    <div class="col-md-9 text-end col-sm-12">
        <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS</button>
        <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>

        @can('create', \App\Models\OgpArea::class)
        <a href="{{ route('admin.ogp.area.create') }}" class="btn btn-success text-success"><i class="fas fa-circle-plus text-success me-1"></i>{{ __('custom.adding') }}</a>
        @endcan
    </div>
</div>
