@can('vote', $item)
<a href="{{ route($route, ['id' => $item->id, 'like' => 1]) }}" class="ogp-vote-ajax me-2 text-decoration-none" data-container="{{ $container }}">
    {{ $item->likes_cnt }} <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
</a>
<a href="{{ route($route, ['id' => $item->id, 'like' => 0]) }}" class="ogp-vote-ajax text-decoration-none" data-container="{{ $container }}">
    {{ $item->dislikes_cnt }} <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
</a>
@else
<span class="me-2 main-color text-decoration-none">
    {{ $item->likes_cnt }} <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
</span>
<span class="main-color text-decoration-none">
    {{ $item->dislikes_cnt }} <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
</span>
@endif
