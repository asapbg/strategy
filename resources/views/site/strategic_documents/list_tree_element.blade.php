<div class="card custom-card mb-2">
    <div class="card-header" id="heading{{ $item->id }}">
        <h2 class="mb-0">
            <a href="{{ route('strategy-document.view', $item->id) }}" target="_blank" class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed">
                <i class="me-1 fas fa-sign-in-alt main-color fs-18"></i>
                {{ $item->title }}
            </a>
        </h2>
    </div>
</div>
