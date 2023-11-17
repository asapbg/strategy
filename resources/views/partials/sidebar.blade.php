<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Logo -->
    <a href="{{ route('admin.home') }}" class="brand-link">
        <span class="ml-2 brand-text">
            <img src="{{ url('img/logo.png') }}" style="height: 40px; width: auto;">
            {{ env('APP_NAME') }}
        </span>
        <span class="ml-2 font-weight-light"></span>
    </a>

    @php
        $user = currentUser();
        $userIsAdmin = $user && $user->hasRole([\App\Models\CustomRole::ADMIN_USER_ROLE]);
    @endphp
    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('admin.home') }}"
                       class="nav-link @if(strstr(url()->current(), '/home')) active @endif">
                        <i class="fas fa-home"></i>
                        <p>{{ __('custom.home')  }}</p>
                    </a>
                </li>

                @if($userIsAdmin)
                    @php($activePublicationCategories = str_contains('nomenclature/publication_category', request()->url()))
                    @php($activePublications = in_array(request()->route()->getName(), ['admin.publications.index', 'admin.publications.edit']))
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($activePublicationCategories || $activePublications) active @endif">
                            <i class="nav-icon fas fa-ellipsis-v"></i>
                            <p>{{ trans_choice('custom.public_sections', 2) }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('admin.publications.index') }}"
                                   class="nav-link @if($activePublications) active @endif">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.publications', 2) }} <span style="font-size: 10px;">({{ __('custom.news_library_ogp') }})</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.nomenclature.publication_category') }}"
                                   class="nav-link @if($activePublicationCategories) active @endif">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.publication_category', 2) }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.page') }}"
                                   class="nav-link @if(Str::endsWith(url()->current(), 'pages')) active @endif">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.static_pages', 2) }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @canany(['manage.*', 'manage.advisory'])
                    <li class="nav-item">
                        <a href="#" class="nav-link @if(strstr(url()->current(), 'consultations')) active @endif">
                            <i class="nav-icon fas fa-bullhorn"></i>
                            <p>{{ trans_choice('custom.public_consultations', 2) }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('admin.consultations.legislative_programs.index') }}"
                                   class="nav-link @if(strstr(url()->current(), 'legislative_programs')) active @endif">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.legislative_programs', 2) }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.consultations.operational_programs.index') }}"
                                   class="nav-link @if(strstr(url()->current(), 'operational_programs')) active @endif">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.operational_programs', 2) }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.consultations.public_consultations.index') }}"
                                   class="nav-link @if(strstr(url()->current(), 'public_consultations')) active @endif">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.consultations', 2) }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.consultations.comments.index') }}"
                                   class="nav-link @if(strstr(url()->current(), 'comments')) active @endif">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.comments', 2) }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.nomenclature.consultation_document_type') }}"
                                   class="nav-link @if(strstr(url()->current(), 'consultation_document_type')) active @endif">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.nomenclature.consultation_document_type', 2) }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany
                <li class="nav-item">
                    <a href="{{ route('admin.impact_assessment.index') }}"
                       class="nav-link @if(Str::endsWith(url()->current(), 'impact-assessments')) active @endif">
                        <i class="fas fa-chart-line"></i>
                        <p>{{ trans_choice('custom.impact_assessments', 2) }}</p>
                    </a>
                </li>
                <!-- Admin -->
{{--                <li class="nav-item">--}}
{{--                    <a href="#" class="nav-link @if(strstr(url()->current(), 'content')) active @endif">--}}
{{--                        <i class="nav-icon fas fa-cubes"></i>--}}
{{--                        <p>{{ trans_choice('validation.attributes.content', 2) }}<i class="fas fa-angle-left right"></i></p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview" style="display: none;">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('admin.impact_pages.index') }}"--}}
{{--                            class="nav-link @if(Str::endsWith(url()->current(), 'impact_assessment')) active @endif">--}}
{{--                                <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>--}}
{{--                                <p>{{ trans_choice('custom.impact_assessment', 2) }}</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                    <ul class="nav nav-treeview" style="display: none;">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('admin.page') }}"--}}
{{--                            class="nav-link @if(Str::endsWith(url()->current(), 'multicriteria_analysis')) active @endif">--}}
{{--                                <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>--}}
{{--                                <p>{{ trans_choice('custom.multicriteria_analysis', 2) }}</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a href="{{ route('admin.polls.index') }}"
                    class="nav-link @if(Str::endsWith(url()->current(), 'polls')) active @endif">
                        <i class="fal fa-check-square"></i>
                        <p>{{ trans_choice('custom.polls', 2) }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link @if(strstr(url()->current(), 'strategic_documents')) active @endif">
                        <i class="nav-icon fas fa-info"></i>
                        <p>{{ trans_choice('custom.strategic_documents', 2) }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.strategic_documents.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'strategic_documents')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.strategic_documents', 2) }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link @if(strstr(url()->current(), 'ogp')) active @endif">
                        <i class="fas fa-hand-point-up"></i>
                        <p>{{ trans_choice('custom.ogp', 2) }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.ogp.plan_elements.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'plan_elements')) active @endif">
                                <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.ogp.plan_elements', 2) }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link @if(strstr(url()->current(), 'links')) active @endif">
                        <i class="nav-icon fas fa-link"></i>
                        <p>{{ trans_choice('custom.links', 2) }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.nomenclature.link_category') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'link_category')) active @endif">
                                <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.link_categories', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.links.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'links')) active @endif">
                                <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.links', 2) }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link @if(strstr(url()->current(), 'pc_subjects')) active @endif">
                        <i class="fas fa-weight"></i>
                        <p>{{ trans_choice('custom.entities_and_payments', 2) }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.pc_subjects.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'pc_subjects')) active @endif">
                                <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.pc_subjects', 2) }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link @if(strstr(url()->current(), 'pc_subjects')) active @endif">
                        <i class="fas fa-weight"></i>
                        <p>{{ trans_choice('custom.legislative_initiatives', 2) }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.legislative_initiatives.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'legislative_initiatives')) active @endif">
                                <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.legislative_initiatives_list', 2) }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pris') }}"
                       class="nav-link @if(str_contains(url()->current(), 'pris')) active @endif">
                        <i class="fal fa-check-square"></i>
                        <p>{{ __('custom.pris') }}</p>
                    </a>
                </li>
                <li class="nav-header">{{ trans_choice('custom.nomenclatures', 2) }}</li>
                @canany(['manage.*','manage.nomenclatures'])
                    <li class="nav-item">
                        <a href="{{route('admin.nomenclature')}}"
                           class="nav-link @if(str_contains(url()->current(), 'nomenclature')) active @endif">
                            <i class="fas fa-file"></i>
                            <p>{{ trans_choice('custom.nomenclatures', 2) }}</p>
                        </a>
                    </li>
                @endcan
                <li class="nav-header">{{ trans_choice('custom.users', 2) }}</li>
                <li class="nav-item">
                    <a href="{{route('admin.roles')}}"
                       class="nav-link @if(str_contains(url()->current(), 'roles')) active @endif">
                        <i class="fas fa-users"></i>
                        <p>{{ trans_choice('custom.roles', 2) }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.users')}}"
                       class="nav-link @if(str_contains(url()->current(), 'users')) active @endif">
                        <i class="fas fa-user"></i>
                        <p>{{ trans_choice('custom.users', 2) }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.permissions')}}"
                       class="nav-link @if(str_contains(url()->current(), 'permissions')) active @endif">
                        <i class="fas fa-gavel"></i>
                        <p>{{ trans_choice('custom.permissions', 2) }}</p>
                    </a>
                </li>

                @if($user)
                    <li class="nav-header">Лични данни</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.profile.edit', $user->id) }}"
                           class="nav-link @if(str_contains(url()->current(), 'users/profile/')) active @endif">
                            <i class="fas fa-user-cog"></i>
                            <p>{{ trans_choice('custom.profiles', 1) }}</p>
                        </a>
                    </li>
                @endif

                <hr class="text-white">
                @if(auth()->user()->hasRole([\App\Models\CustomRole::ADMIN_USER_ROLE, \App\Models\CustomRole::SUPER_USER_ROLE]))
                    <li class="nav-item">
                        <a href="{{route('admin.activity-logs')}}"
                           class="nav-link @if(strstr(url()->current(), 'activity-logs')) active @endif">
                            <i class="fas fa-history"></i>
                            <p>{{ trans_choice('custom.activity_logs', 2) }}</p>
                        </a>
                    </li>
                @endif
                @canany(['manage.*', 'manage.settings'])
                    <li class="nav-item">
                        <a href="{{ route('admin.settings') }}"
                           class="nav-link @if(str_contains(url()->current(), 'settings')) active @endif">
                            <i class="fas fa-cogs"></i>
                            <p>{{ trans_choice('custom.settings', 1) }}</p>
                        </a>
                    </li>
                @endcanany
                @canany(['manage.*', 'manage.dynamic_structures'])
                    <li class="nav-item">
                        <a href="{{ route('admin.dynamic_structures') }}"
                           class="nav-link @if(str_contains(url()->current(), 'dynamic-structures')) active @endif">
                            <i class="fas fa-cogs"></i>
                            <p>{{ trans_choice('custom.dynamic_structures', 2) }}</p>
                        </a>
                    </li>
                @endcanany
            </ul>
        </nav>
    </div>
</aside>
