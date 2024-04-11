
    @if(isset($pageTopContent) && !empty($pageTopContent->value))
        <div class="col-12 mb-5">
            {!! $pageTopContent->value !!}
        </div>
    @endif
    @php($addBtn = auth()->user() && auth()->user()->can('create', \App\Models\StrategicDocument::class))
    @include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'btn_add' => $addBtn, 'add_url' => route('admin.strategic_documents.edit', ['item' => 0])])

    @if(sizeof($items))
        <div class="row p-1 mb-2">
            <div class="accordion" id="accordionExample">
                @foreach($items as $catId => $cat)
                    @if(isset($cat['items']) && sizeof($cat['items']))
                        <div class="card custom-card mb-2">
                            <div class="card-header" id="headingcat{{ $catId }}">
                                <h2 class="mb-0">
                                    <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#collapsecat{{ $catId }}" aria-expanded="false" aria-controls="collapsecat{{ $catId }}">
                                        <i class="me-1 bi bi-pin-map-fill main-color fs-18"></i>
                                        {{ $cat['name'] }}
                                    </button>
                                </h2>
                            </div>
                            <div id="collapsecat{{ $catId }}" class="collapse" aria-labelledby="headingcat{{ $catId }}" data-parent="#accordionExample" style="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            @php($policy = '')
                                            @php($sdId = 0)
                                            @foreach($cat['items'] as $i)
                                                @if(($policy != $i->sd_policy_title && !$loop->first) || $loop->last)
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($policy != $i->sd_policy_title)
                                                    @php($policy = $i->sd_policy_title)
                                                    <div class="accordion" id="accordionpolicy{{ $i->sd_policy_id }}">
                                                        <div class="card custom-card mb-2 ms-3">
                                                            <div class="card-header" id="headingpolicy{{ $i->sd_policy_id }}">
                                                                <h2 class="mb-0">
                                                                    <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#collapsepolicy{{ $i->sd_policy_id }}" aria-expanded="false" aria-controls="collapsepolicy{{ $i->sd_policy_id }}">
                                                                        <i class="me-1 fas fa-sign-in-alt main-color fs-18"></i>
                                                                        {{ $policy }}
                                                                    </button>
                                                                </h2>
                                                            </div>
                                                            <div id="collapsepolicy{{ $i->sd_policy_id }}" class="collapse" aria-labelledby="headingpolicy{{ $i->sd_policy_id }}" data-parent="#accordionpolicy{{ $i->sd_policy_id }}" style="">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-12 mb-2">
                                                @endif

                                                @if($i->sd_id != $sdId)
                                                    @php($sdId = $i->sd_id)
                                                    <div class="card custom-card mb-2" style="margin-left: 20px">
                                                        <div class="card-header" id="heading{{ $i->sd_id }}" >
                                                            <h2 class="mb-0">
                                                                <a href="{{ route('strategy-document.view', $i->sd_id) }}" target="_blank" class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed">
                                                                    <i class="me-1 fas fa-sign-in-alt main-color fs-18"></i>
                                                                    {{ html_entity_decode($i->sd_title) }}
                                                                </a>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(!is_null($i->child_id))
                                                    <div class="card custom-card mb-2" style="margin-left: {{ (($i->child_depth * 30) + 60) }}px">
                                                        <div class="card-header" id="heading{{ $i->sd_id }}">
                                                            <h2 class="mb-0">
                                                                <a href="{{ route('strategy-document.view', $i->sd_id) }}" target="_blank" class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed">
                                                                    <i class="me-1 fas fa-sign-in-alt main-color fs-18"></i>
                                                                    {{ html_entity_decode($i->child_title) }}
                                                                </a>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                @endif
{{--                                                @include('site.strategic_documents.list_tree_element', ['item' => $i])--}}
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

{{--    @push('scripts')--}}
{{--        <script type="text/javascript">--}}
{{--            let centralLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value; ?>';--}}
{{--            let areaLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::AREA->value; ?>';--}}
{{--            let municipalityLevel = '<?php echo \App\Enums\InstitutionCategoryLevelEnum::MUNICIPAL->value; ?>';--}}

{{--            let fieldOfActions = $('#fieldOfActions');--}}
{{--            let areas = $('#areas');--}}
{{--            let municipalities = $('#municipalities');--}}

{{--            function categoriesControl(){--}}
{{--                let level = $('#level');--}}
{{--                let levelVals = level.val();--}}
{{--                console.log(level.val(), centralLevel, levelVals.indexOf(centralLevel) != -1 || !levelVals.length);--}}
{{--                if(levelVals.indexOf(centralLevel) != -1 || !levelVals.length){--}}
{{--                    fieldOfActions.parent().removeClass('d-none');--}}
{{--                } else{--}}
{{--                    fieldOfActions.parent().addClass('d-none');--}}
{{--                    fieldOfActions.val('');--}}
{{--                }--}}
{{--                console.log(level.val(), areaLevel, levelVals.indexOf(areaLevel) != -1 ||!levelVals.length);--}}
{{--                if(levelVals.indexOf(areaLevel) != -1 ||!levelVals.length){--}}
{{--                    areas.parent().removeClass('d-none');--}}
{{--                } else{--}}
{{--                    areas.parent().addClass('d-none');--}}
{{--                    areas.val('');--}}
{{--                }--}}
{{--                console.log(level.val(), municipalityLevel, levelVals.indexOf(municipalityLevel) != -1 || !levelVals.length);--}}
{{--                if(levelVals.indexOf(municipalityLevel) != -1 || !levelVals.length){--}}
{{--                    municipalities.parent().removeClass('d-none');--}}
{{--                } else{--}}
{{--                    municipalities.parent().addClass('d-none');--}}
{{--                    municipalities.val('');--}}
{{--                }--}}
{{--            }--}}

{{--            $(document).ready(function (){--}}
{{--                $('#level').on('change', function (){--}}
{{--                    categoriesControl();--}}
{{--                });--}}
{{--                $('#level').trigger('change');--}}
{{--            });--}}
{{--        </script>--}}
{{--    @endpush--}}
