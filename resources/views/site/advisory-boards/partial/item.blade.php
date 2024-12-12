<div class="row mb-4">
    <div class="col-md-12">
        <div class="consul-wrapper">
            <div class="single-consultation d-flex">
                <div class="consult-body">
                    <div class="consult-item-header d-flex justify-content-between">
                        <div class="consult-item-header-link">
                            <a href="{{ route('advisory-boards.view', $item) }}"
                               class="text-decoration-none"
                               title="{{ $item->name }}">
                                <h3>{{ $item->name }}</h3>
                            </a>
                        </div>

                        @if($item->active && auth()->user())
                            @canany(['update', 'delete'], $item)
                                <div class="consult-item-header-edit">
                                    @can(['delete'], $item)
                                        <a href="javascript:;"
                                           class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden text-decoration-none"
                                           data-target="#modal-delete-resource"
                                           data-resource-id="{{ $item->id }}"
                                           data-resource-name="{{ $item->name }}"
                                           data-resource-delete-url="{{ route('admin.advisory-boards.delete', $item) }}"
                                           data-toggle="tooltip"
                                           title="{{ __('custom.delete') }}"><span class="d-none"></span>
                                        </a>
                                    @endcan
                                    @can(['update'], $item)
                                        <a href="{{ route('admin.advisory-boards.edit', ['item' => $item]) }}" target="_blank"
                                           class="me-2">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                               role="button"
                                               title="{{ __('custom.edit') }}">
                                            </i>
                                        </a>
                                    @endcan
                                </div>
                            @endcanany
                        @endif
                    </div>
                    @if($item->policyArea)
                        <a href="{{ route('advisory-boards.index').'?fieldOfActions[]='.$item->policyArea->id }}"
                           title="{{ $item->policyArea->name }}" class="text-decoration-none mb-2 me-3">
                            <i class="text-primary {{ $item->policyArea->icon_class }} me-1" title="{{ $item->policyArea->name }}"></i>
                            {{ $item->policyArea->name }}
                        </a>
                    @endif
                    @if($item->authority)
                        <br/>
                        <span class="me-1"><strong>{{ trans_choice('custom.authority_advisory_board', 1) }}:</strong></span>

                        <a href="{{ route('advisory-boards.index').('?authoritys[]='.$item->authority->id) }}" class="main-color text-decoration-none me-3">
                            <i class="fa-solid fa-right-to-bracket me-1 main-color"
                               title="{{ $item->authority->name }}"></i>
                            {{ $item->authority->name }}
                        </a>
                    @endif
                    @if(isset($item->chairmen) && $item->chairmen->count() > 0)
                        <br/>
                        <span class="me-1"><strong>{{ __('custom.chairman_site') }}:</strong></span>

                        @foreach($item->chairmen as $chairmen)
                            @php $dataChairmen = [] @endphp
                            @foreach(['member_name', 'member_job', 'institution'] as $n)
                                @if(!empty($chairmen->{$n}))
                                    @php $dataChairmen[] = $n != 'institution' ? $chairmen->{$n} : $chairmen->institution->name @endphp
                                @endif
                            @endforeach
                            @if(sizeof($dataChairmen))
                                <span class="mb-2">{{ Str::ucfirst(implode(', ', $dataChairmen)) }}</span>
                            @endif
                            @if(!empty($chairmen->member_notes))
                                {!! $chairmen->member_notes !!}
                            @endif
                        @endforeach
                    @endif
                    @if($item->advisoryActType)
                        <br/>
                        <span class="me-1"><strong>{{ __('validation.attributes.act_of_creation') }}:</strong></span>

                        <a href="{{ route('advisory-boards.index').('?actOfCreations[]=' . $item->advisoryActType->id) }}" class="main-color text-decoration-none me-3">
                            {{ $item->advisoryActType?->translation->name }}
                        </a>
                    @endif
                    <div class="meta-consul mt-2">
                                <span>{{ __('custom.status') }}:
                                    @php $class = $item->active ? 'active-ks' : 'inactive-ks' @endphp
                                    <span
                                        class="{{ $class }}">{{ $item->active ? __('custom.active_m') : __('custom.inactive_m') }}</span>
                                </span>
                        <a href="{{ route('advisory-boards.view', $item) }}">
                            <i class="fas fa-arrow-right read-more text-end"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
