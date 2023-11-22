@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    {{ trans_choice('custom.legislative_initiatives_list', 1) }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mr-2 fw-bold"
                                       for="from_date">{{ __('validation.attributes.from_date') }}
                                    : </label>{{ date('m-Y', strtotime($item->created_at)) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card card-primary">
                            <div class="card-body">
                                <div class="row gap-3">
                                    <div class="col-12">
                                        <h3 class="border-bottom border-4 border-primary pb-2">
                                            {{ __('custom.change_f') . ' ' . __('custom.in') . ' ' . mb_strtolower($item->operationalProgram?->value) }}
                                        </h3>
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-bold">{{ __('custom.name_of_normative_act') }}:</span>
                                        {{ $item->operationalProgram?->value }}
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-bold">{{ __('custom.author') }}:</span>
                                        {{ $item->user->fullName() }}
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-bold">{{ __('custom.legislative_vote_need') }}:</span>
                                        {{ $item->cap . ' ' . trans_choice('custom.likes', 2) }}
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-bold">{{ __('custom.status') }}:</span>
                                        {{ __('custom.legislative_' . \Illuminate\Support\Str::lower($item->getStatus($item->status)->name)) }}
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-bold">{{ __('custom.description_of_suggested_change') }}:</span>
                                        {!! $item->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card card-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h3 class="border-bottom border-4 border-primary pb-2">
                                            {{ trans_choice('custom.comments', 2) }}
                                            @if(isset($comments) && $comments->count() > 0)
                                                ({{ $comments->count() }})
                                            @endif
                                        </h3>

                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                @php $checked = request()->get('show_deleted_comments', '0') == '1' ? 'checked' : '' @endphp
                                                <input type="checkbox" class="custom-control-input"
                                                       id="show-deleted" {{ $checked }}>
                                                <label class="custom-control-label"
                                                       for="show-deleted">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                                            </div>
                                        </div>

                                        <div class="toast-container">
                                            @if(isset($comments) && $comments->count() > 0)
                                                @foreach($comments as $comment)
                                                    <div class="toast show" role="alert" aria-live="assertive"
                                                         aria-atomic="true">
                                                        <div class="toast-header">
                                                            <strong
                                                                    class="me-auto">{{ $comment->user->fullName() }}</strong>
                                                            <small
                                                                    class="text-muted">{{ $comment->created_at->format('d.m.Y h:i') }}</small>

                                                            @if(!$comment->deleted_at)
                                                                <form class="d-none"
                                                                      method="POST"
                                                                      action="{{ route('legislative_initiatives.comments.delete', $comment) }}"
                                                                      name="DELETE_COMMENT_{{ $comment->id }}"
                                                                >
                                                                    @csrf
                                                                </form>

                                                                <button type="button"
                                                                        class="btn-close open-delete-modal"
                                                                        aria-label="Close"></button>
                                                            @endif
                                                        </div>
                                                        <div class="toast-body">
                                                            @php $deleted_class = $comment->deleted_at ? 'text-decoration-line-through' : '' @endphp
                                                            <span class="{{ $deleted_class }}">{{ $comment->description }}</span>

                                                            <div class="row mt-3 justify-content-end">
                                                                <div class="col-auto p-0">
                                                                    {{ $comment->countLikes() }}
                                                                </div>

                                                                <div class="col-auto">
                                                                    <i class="fa fa-thumbs-up fs-18"
                                                                       aria-hidden="true"></i>
                                                                </div>

                                                                <div class="col-auto p-0">
                                                                    {{ $comment->countDislikes() }}
                                                                </div>

                                                                <div class="col-auto">
                                                                    <i class="fa fa-thumbs-down fs-18"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.consultations.operational_programs.index') }}"
                               class="btn btn-primary">{{ __('custom.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.delete-modal', [
        'cancel_btn_text'           => __('custom.cancel'),
        'continue_btn_text'         => __('custom.continue'),
        'title_text'                => __('custom.deletion') . ' ' . __('custom.of') . ' ' . trans_choice('custom.comments', 1),
        'file_change_warning_txt'   => __('custom.legislative_admin_comment_delete_warning'),
    ])

    @push('scripts')
        <script type="application/javascript">
            const url = @json(url()->current());

            document.querySelector('#show-deleted').addEventListener('change', function () {
                console.log(this, this.value, this.checked, url);
                if (this.checked) {
                    window.location = url + '?show_deleted_comments=1';
                    return;
                }

                window.location = url;
            })
        </script>
    @endpush
@endsection
