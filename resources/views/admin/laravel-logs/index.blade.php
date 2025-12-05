@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body table-responsive">

                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width: 10%">{{ __('custom.date') }}</th>
                            <th>{{ $title }}</th>
                            <th style="width: 0.1%"></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($laravel_errors as $data)
                                @continue($loop->iteration <= $start || $loop->iteration >= $start + $per_page)
                                    <tr>
                                        <td>{{ displayDateTime($data['date']) }}</td>
                                        <td>{{ $data['error'] }}</td>
                                        <td></td>
                                    </tr>
                                @isset($data['exception'])
                                    <tr>
                                        <td></td>
                                        <td>{{ $data['exception'] }}</td>
                                        <td></td>
                                    </tr>
                                @endisset
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($pages > 1)
                <div class="card-footer mt-2">
                    <nav>
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
{{--                            @if ($paginator->onFirstPage())--}}
{{--                                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">--}}
{{--                                    <span class="page-link" aria-hidden="true">&lsaquo;</span>--}}
{{--                                </li>--}}
{{--                            @else--}}
{{--                                <li class="page-item">--}}
{{--                                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>--}}
{{--                                </li>--}}
{{--                            @endif--}}

                            {{-- Pagination Elements --}}
                            @for($page = 1; $page <= $pages; $page++)
                                {{-- "Three Dots" Separator --}}
{{--                                @if (is_string($element))--}}
{{--                                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>--}}
{{--                                @endif--}}

                                @if ($page == $current_page)
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ url()->current() }}?page={{ $page }}">{{ $page }}</a></li>
                                @endif
                            @endfor

                            {{-- Next Page Link --}}
{{--                            @if ($paginator->hasMorePages())--}}
{{--                                <li class="page-item">--}}
{{--                                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>--}}
{{--                                </li>--}}
{{--                            @else--}}
{{--                                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">--}}
{{--                                    <span class="page-link" aria-hidden="true">&rsaquo;</span>--}}
{{--                                </li>--}}
{{--                            @endif--}}
                        </ul>
                    </nav>
                </div>
            @endif

        </div>
    </section>

    @includeIf('modals.delete-resource', ['resource' => $title_singular])

@endsection
