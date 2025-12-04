<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Logo -->
    <a href="{{ route('site.home') }}" target="_blank" class="brand-link">
        <span>
            <img src="{{ url('img/logo.png') }}" style="height: 40px; width: auto;">
        </span>
        <span class="ml-2 brand-text font-weight-light">
            {{ env('APP_NAME') }}
        </span>
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

                @canany(['manage.*', 'manage.advisory'])
                    <li class="nav-item @if(strstr(url()->current(), 'consultations')) menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="fas fa-bullhorn"></i>
                            <p>{{ trans_choice('custom.public_consultations', 2) }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('admin.consultations.public_consultations.index') }}"
                                   class="nav-link @if(strstr(url()->current(), 'public-consultations')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.consultations', 2) }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.consultations.comments.index') }}"
                                   class="nav-link @if(strstr(url()->current(), 'consultations/comment')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.comments', 2) }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany

                @canany(['manage.*', 'manage.legislative_operational_programs'])
                    <li class="nav-item @if(strstr(url()->current(), 'legislative-programs') || strstr(url()->current(), 'operational-programs')) menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="fas fa-tasks"></i>
                            <p>{{ __('custom.lp_op_programs') }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('admin.consultations.legislative_programs.index') }}"
                                   class="nav-link @if(in_array(request()->route()->getName(), ['admin.consultations.legislative_programs.index', 'admin.consultations.legislative_programs.edit', 'admin.consultations.legislative_programs.view'])) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.legislative_programs', 2) }}</p>
                                </a>
                            </li>
                            @canany(['manage.*'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.consultations.legislative_programs.info') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'legislative-programs/info')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ __('custom.general_info_lp') }}</p>
                                    </a>
                                </li>
                            @endcan

                            <li class="nav-item">
                                <a href="{{ route('admin.consultations.operational_programs.index') }}"
                                   class="nav-link @if(in_array(request()->route()->getName(), ['admin.consultations.operational_programs.index', 'admin.consultations.operational_programs.edit', 'consultations.operational_programs.view'])) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.operational_programs', 2) }}</p>
                                </a>
                            </li>

                            @canany(['manage.*'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.consultations.operational_programs.info') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'operational-programs/info')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ __('custom.general_info_op') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['manage.*', 'manage.advisory', 'manage.executors'])
                    <li class="nav-item @if(strstr(url()->current(), 'impact-assessments')) menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-line"></i>
                            <p>{{ trans_choice('custom.impact_assessment', 2) }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            @canany(['manage.*'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.impact_assessment.info') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'impact-assessments/info')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ __('custom.base_information') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @canany(['manage.*', 'manage.advisory'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.impact_assessment.index') }}"
                                       class="nav-link @if(Str::endsWith(url()->current(), 'impact-assessments')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ trans_choice('custom.impact_assessments', 2) }}</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['manage.*'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.impact_assessments.library', ['module' => \App\Enums\PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value]) }}"
                                       class="nav-link @if(str_contains(url()->current(), 'impact-assessments/library')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ __('custom.library') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @canany(['manage.*', 'manage.executors'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.executors.index') }}"
                                       class="nav-link @if(strstr(url()->current(), 'impact-assessments/executors')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ __('custom.zna_list') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['manage.*','manage.pris'])
                    <li class="nav-item">
                        <a href="{{ route('admin.pris') }}"
                           class="nav-link @if(str_contains(url()->current(), 'pris')) active @endif">
                            <i class="fal fa-check-square"></i>
                            <p>{{ __('site.menu.pris') }}</p>
                        </a>
                    </li>
                @endcan

                @canany(['manage.*','manage.legislative_initiatives'])
                    <li class="nav-item @if(str_contains(url()->current(), 'legislative-initiatives')) menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="fas fa-weight"></i>
                            <p>{{ trans_choice('custom.legislative_initiatives', 2) }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('admin.legislative_initiatives.index') }}"
                                   class="nav-link @if(in_array(request()->route()->getName(), ['admin.legislative_initiatives.index', 'admin.legislative_initiatives.view'])) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.legislative_initiatives_list', 2) }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.legislative_initiatives.page.info') }}"
                                   class="nav-link @if(str_contains(url()->current(), 'legislative-initiatives/page/base-information')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ __('custom.base_information') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.legislative_initiatives.settings', ['section' => \App\Models\Setting::OGP_LEGISLATIVE_INIT_SECTION]) }}"
                                   class="nav-link @if(request()->route()->getName() == 'admin.legislative_initiatives.settings') active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ __('custom.settings') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

{{--                @canany(['manage.*','manage.partnership'])--}}
{{--                    <li class="nav-item @if(strstr(url()->current(), 'plan_elements') || strstr(url()->current(), 'estimations')) menu-open @endif">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="fas fa-handshake"></i>--}}
{{--                            <p>{{ trans_choice('custom.ogp', 2) }}<i class="fas fa-angle-left right"></i></p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview" style="display: none;">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('admin.ogp.plan_elements.index') }}"--}}
{{--                                   class="nav-link @if(strstr(url()->current(), 'plan_elements') || strstr(url()->current(), 'estimations')) active @endif">--}}
{{--                                    <i class="fas fa-circle nav-item-sub-icon"></i>--}}
{{--                                    <p>{{ trans_choice('custom.ogp.plan_elements', 2) }}</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                @endcan--}}

                @canany(['manage.*', 'manage.partnership'])
                    <li class="nav-item @if(str_contains(url()->current(), 'ogp/')) menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="fas fa-handshake"></i>
                            <p>{{ __('custom.open_government_partnership') }}<i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('admin.ogp.area.index') }}"
                                   class="nav-link @if(strstr(url()->current(), 'areas')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.area', 2) }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ogp.plan.develop.index') }}"
                                   class="nav-link @if(strstr(url()->current(), 'ogp/develop-plan')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ __('custom.develop_plan') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ogp.plan.index') }}"
                                   class="nav-link @if(strstr(url()->current(), 'plans')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.ogp_national_plans', 2) }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ogp.page.info') }}"
                                   class="nav-link @if(str_contains(url()->current(), 'ogp/page/base-information')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ __('custom.base_information') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ogp.news.index') }}"
                                   class="nav-link @if(str_contains(url()->current(), 'ogp/news')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.news', 2) }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ogp.library', ['module' => \App\Enums\PageModulesEnum::MODULE_OGP->value]) }}"
                                   class="nav-link @if(str_contains(url()->current(), 'ogp/library')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ __('custom.library') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ogp.settings') }}"
                                   class="nav-link @if(request()->route()->getName() == 'admin.ogp.settings') active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ __('custom.settings') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @canany(['manage.*','manage.pools', 'manage.advisory'])
                    <li class="nav-item">
                        <a href="{{ route('admin.polls.index') }}"
                           class="nav-link @if(Str::endsWith(url()->current(), 'polls')) active @endif">
                            <i class="fal fa-check-square"></i>
                            <p>{{ trans_choice('custom.polls', 2) }}</p>
                        </a>
                    </li>
                @endcan

                @canany(['manage.*','manage.strategic'])
                    <li class="nav-item @if(strstr(url()->current(), 'strategic-documents')) menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="fas fa-info"></i>
                            <p>{{ trans_choice('custom.strategic_documents', 2) }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('admin.strategic_documents.index') }}"
                                   class="nav-link @if(strstr(url()->current(), 'strategic-documents') && !strstr(url()->current(), 'page')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.strategic_documents', 2) }}</p>
                                </a>
                            </li>
                            @if($user && ($user->can('manage.*') || $user->hasAnyRole(['moderator-strategics'])))
                                <li class="nav-item">
                                    <a href="{{ route('admin.strategic_documents.page.info') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'strategic-documents/page/base-information')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ __('custom.base_information') }}</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.strategic_documents.page.documents') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'strategic-documents/page/documents')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ trans_choice('custom.documents', 2) }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.sd.users') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'strategic-documents/users')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ trans_choice('custom.moderators', 2) }}</p>
                                    </a>
                                </li>
                            @endif

                            @canany(['manage.*', 'manage.strategic-documents.nomenclatures'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.strategic_documents.nomenclatures') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'strategic-documents/nomenclature')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ trans_choice('custom.nomenclatures', 2) }}</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcan

                @canany(['manage.*', 'manage.advisory-boards', 'manage.advisory-board'])
                    <li class="nav-item @if(strstr(url()->current(), 'advisory-boards')) menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="fas fa-weight"></i>
                            <p>{{ trans_choice('custom.advisory_boards', 2) }}<i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('admin.advisory-boards.index') }}"
                                   class="nav-link @if(Str::endsWith(url()->current(), 'advisory-boards')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.advisory_board_list', 2) }}</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.advisory-boards.news.index') }}"
                                   class="nav-link @if(str_contains(url()->current(), 'advisory-boards/news')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.news', 2) }}</p>
                                </a>
                            </li>
                            @canany(['manage.*', 'manage.advisory-boards'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.advisory-boards.page.info') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'advisory-boards/page/base-information')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ __('custom.base_information') }}</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.advisory-boards.page.documents') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'advisory-boards/page/documents')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ trans_choice('custom.documents', 2) }}</p>
                                    </a>
                                </li>
                            @endcan

                            <li class="nav-item">
                                <a href="{{ route('admin.advisory-boards.archive.index') }}"
                                   class="nav-link @if(Str::endsWith(url()->current(), 'inactive')) active @endif">
                                    <i class="fas fa-circle nav-item-sub-icon"></i>
                                    <p>{{ __('custom.inactive_adv_board') }}</p>
                                </a>
                            </li>

                            @canany(['manage.*', 'manage.advisory-boards'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.advisory-boards.messages') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'advisory-boards/messages')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ __('custom.messages') }}</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['manage.*', 'manage.advisory-boards'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.advisory-boards.settings') }}"
                                       class="nav-link @if(request()->route()->getName() == 'admin.advisory-boards.settings') active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ __('custom.settings') }}</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['manage.*', 'manage.advisory-boards.nomenclatures'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.advisory-boards.nomenclature.index') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'advisory-boards/nomenclature')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ trans_choice('custom.nomenclatures', 2) }}</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['manage.*', 'manage.advisory-boards'])
                                <li class="nav-item">
                                    <a href="{{ route('admin.advisory-boards.contacts.index') }}"
                                       class="nav-link @if(str_contains(url()->current(), 'advisory-boards/contacts')) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ trans_choice('custom.contacts', 2) }}</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcan




                @if($userIsAdmin)
                    @php($activePublicationCategories = str_contains('nomenclature/publication_category', request()->url()))
                    @php($activePublications = in_array(request()->route()->getName(), ['admin.publications.index', 'admin.publications.edit']))
                    <li class="nav-item @if($activePublicationCategories || $activePublications) menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="fas fa-ellipsis-v"></i>
                            <p>{{ trans_choice('custom.public_sections', 2) }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            @foreach (App\Enums\PublicationTypesEnum::options() as $key => $value)
                                @continue(in_array($key, ["TYPE_ADVISORY_BOARD", "TYPE_OGP_NEWS"]))
                                <li class="nav-item">
                                    <a href="{{ route('admin.publications.index', ).'?type='.$value }}"
                                       class="nav-link @if(request()->route('type') == $value || request()->offsetGet('type') == $value) active @endif">
                                        <i class="fas fa-circle nav-item-sub-icon"></i>
                                        <p>{{ trans_choice("custom.public_sections.types.$key", 2) }}</p>
                                    </a>
                                </li>
                            @endforeach
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('admin.nomenclature.publication_category') }}"--}}
{{--                                   class="nav-link @if($activePublicationCategories) active @endif">--}}
{{--                                    <i class="fas fa-circle nav-item-sub-icon"></i>--}}
{{--                                    <p>{{ trans_choice('custom.publication_category', 2) }}</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.page') }}"
                           class="nav-link @if(strstr(url()->current(), 'admin/page')) active @endif">
                            <i class="fas fa-book"></i>
                            <p>{{ trans_choice('custom.static_pages', 2) }}</p>
                        </a>
                    </li>
{{--                    <li class="nav-item @if(strstr(url()->current(), 'admin/page')) menu-open @endif">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="fas fa-book"></i>--}}
{{--                            <p>{{ trans_choice('custom.static_pages', 2) }}<i class="fas fa-angle-left right"></i></p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview" style="display: none;">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('admin.page') }}"--}}
{{--                                   class="nav-link @if(strstr(url()->current(), '/page'))  active @endif">--}}
{{--                                    <i class="fas fa-circle nav-item-sub-icon"></i>--}}
{{--                                    <p>{{ trans_choice('custom.static_pages', 2) }}</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
                @endif


                <!-- Admin -->
{{--                <li class="nav-item">--}}
{{--                    <a href="#" class="nav-link @if(strstr(url()->current(), 'content')) active @endif">--}}
{{--                        <i class="fas fa-cubes"></i>--}}
{{--                        <p>{{ trans_choice('validation.attributes.content', 2) }}<i class="fas fa-angle-left right"></i></p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview" style="display: none;">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('admin.impact_pages.index') }}"--}}
{{--                            class="nav-link @if(Str::endsWith(url()->current(), 'impact_assessment')) active @endif">--}}
{{--                                <i class="fas fa-circle nav-item-sub-icon"></i>--}}
{{--                                <p>{{ trans_choice('custom.impact_assessment', 2) }}</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                    <ul class="nav nav-treeview" style="display: none;">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('admin.page') }}"--}}
{{--                            class="nav-link @if(Str::endsWith(url()->current(), 'multicriteria_analysis')) active @endif">--}}
{{--                                <i class="fas fa-circle nav-item-sub-icon"></i>--}}
{{--                                <p>{{ trans_choice('custom.multicriteria_analysis', 2) }}</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}



{{--                <li class="nav-item">--}}
{{--                    <a href="#" class="nav-link @if(strstr(url()->current(), 'links')) active @endif">--}}
{{--                        <i class="fas fa-link"></i>--}}
{{--                        <p>{{ trans_choice('custom.links', 2) }}<i class="fas fa-angle-left right"></i></p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview" style="display: none;">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('admin.nomenclature.link_category') }}"--}}
{{--                            class="nav-link @if(Str::endsWith(url()->current(), 'link_category')) active @endif">--}}
{{--                                <i class="fas fa-circle nav-item-sub-icon"></i>--}}
{{--                                <p>{{ trans_choice('custom.link_categories', 2) }}</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('admin.links.index') }}"--}}
{{--                            class="nav-link @if(Str::endsWith(url()->current(), 'links')) active @endif">--}}
{{--                                <i class="fas fa-circle nav-item-sub-icon"></i>--}}
{{--                                <p>{{ trans_choice('custom.links', 2) }}</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a href="#" class="nav-link @if(strstr(url()->current(), 'pc_subjects')) active @endif">--}}
{{--                        <i class="fas fa-weight"></i>--}}
{{--                        <p>{{ trans_choice('custom.entities_and_payments', 2) }}<i class="fas fa-angle-left right"></i></p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview" style="display: none;">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('admin.pc_subjects.index') }}"--}}
{{--                            class="nav-link @if(Str::endsWith(url()->current(), 'pc_subjects')) active @endif">--}}
{{--                                <i class="fas fa-circle nav-item-sub-icon"></i>--}}
{{--                                <p>{{ trans_choice('custom.pc_subjects', 2) }}</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}






                @canany(['manage.*','manage.nomenclatures'])
                    <li class="nav-header">{{ trans_choice('custom.nomenclatures', 2) }}</li>
                    <li class="nav-item">
                        <a href="{{route('admin.nomenclature')}}"
                           class="nav-link @if(str_contains(url()->current(), 'nomenclature') && !str_contains(url()->current(), 'advisory-boards')) active @endif">
                            <i class="fas fa-file"></i>
                            <p>{{ trans_choice('custom.nomenclatures', 2) }}</p>
                        </a>
                    </li>
                @endcan
                @if(auth()->user()->hasRole([\App\Models\CustomRole::ADMIN_USER_ROLE, \App\Models\CustomRole::SUPER_USER_ROLE]))
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
                           class="nav-link
                           @if(
                                str_contains(url()->current(), 'admin/users')
                                && !request()->routeIs('admin.users.profile.edit')
                                && !request()->routeIs('admin.users.change_request')
                            ) active @endif"
                        >
                            <i class="fas fa-user"></i>
                            <p>{{ trans_choice('custom.users', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.users.change_request')}}"
                           class="nav-link @if(str_contains(url()->current(), 'users/change-requests')) active @endif">
                            <i class="fas fa-user-edit"></i>
                            <p>{{ __('custom.change_requests') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.permissions')}}"
                           class="nav-link @if(str_contains(url()->current(), 'permissions')) active @endif">
                            <i class="fas fa-gavel"></i>
                            <p>{{ trans_choice('custom.permissions', 2) }}</p>
                        </a>
                    </li>
                @endif
                @if($user)
                    <li class="nav-header">Лични данни</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.profile.edit', $user->id) }}"
                           class="nav-link @if(request()->routeIs('admin.users.profile.edit')) active @endif">
                            <i class="fas fa-user-cog"></i>
                            <p>{{ trans_choice('custom.profiles', 1) }}</p>
                        </a>
                    </li>
                @endif

                <hr class="text-white">
                @if(auth()->user()->hasRole([\App\Models\CustomRole::ADMIN_USER_ROLE, \App\Models\CustomRole::SUPER_USER_ROLE]))
                    <li class="nav-header">{{ trans_choice('custom.activity_logs', 1) }}</li>
                    <li class="nav-item">
                        <a href="{{route('admin.activity-logs')}}"
                           class="nav-link @if(strstr(url()->current(), 'activity-logs')) active @endif">
                            <i class="fas fa-history"></i>
                            <p>{{ trans_choice('custom.activity_logs', 2) }}</p>
                        </a>
                    </li>
                @endif
                @if($user->hasRole('super-admin'))
                    <li class="nav-header">{{ trans_choice('custom.logs', 2) }}</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.laravel-logs', [ 'log' => 'laravel' ]) }}"
                           class="nav-link @if(strstr(url()->current(), 'laravel-logs/laravel')) active @endif">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>{{ trans_choice('custom.laravel_logs', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.laravel-logs', [ 'log' => 'eauth' ]) }}"
                           class="nav-link @if(strstr(url()->current(), 'laravel-logs/eauth')) active @endif">
                            <i class="fas fa-signature"></i>
                            <p>{{ trans_choice('custom.eauth_logs', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.laravel-logs', [ 'log' => 'notifications' ]) }}"
                           class="nav-link @if(strstr(url()->current(), 'laravel-logs/notifications')) active @endif">
                            <i class="fas fa-envelope"></i>
                            <p>{{ trans_choice('custom.notification_logs', 2) }}</p>
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
                <li class="nav-item">
                    <a href="{{ route('admin.help') }}"
                       class="nav-link @if(str_contains(url()->current(), 'help')) active @endif">
                        <i class="fas fa-question"></i>
                        <p>{{ trans_choice('custom.help', 2) }}</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
