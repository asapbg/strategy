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

                <!-- Admin -->
                @can('manage.*')
                <li class="nav-item">
                    <a href="{{ route('admin.polls.index') }}"
                    class="nav-link @if(Str::endsWith(url()->current(), 'polls')) active @endif">
                        <i class="fal fa-check-square"></i>
                        <p>{{ trans_choice('custom.polls', 2) }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link @if(strstr(url()->current(), 'news')) active @endif">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>{{ trans_choice('custom.news', 2) }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.news.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'news')) active @endif">
                                <i class="fas fa-info nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.news', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.news.categories.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'news/categories')) active @endif">
                                <i class="fas fa-folder nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.news_category', 2) }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link @if(strstr(url()->current(), 'publications')) active @endif">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>{{ trans_choice('custom.publications', 2) }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.publications.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'publications')) active @endif">
                                <i class="fas fa-newspaper nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.publications', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.publications.categories.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'publications/categories')) active @endif">
                                <i class="fas fa-folder nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.publications_categories', 2) }}</p>
                            </a>
                        </li>
                    </ul>
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
                            <i class="fas fa-info nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.strategic_documents', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.strategic_documents.institutions.index') }}"
                            class="nav-link @if(strstr(url()->current(), 'institutions')) active @endif">
                            <i class="fas fa-info-circle nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.institutions', 2) }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link @if(strstr(url()->current(), 'consultations')) active @endif">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>{{ trans_choice('custom.public_consultations', 2) }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.consultations.legislative_programs.index') }}"
                            class="nav-link @if(strstr(url()->current(), 'legislative_programs')) active @endif">
                            <i class="fas fa-university nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.legislative_programs', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.consultations.operational_programs.index') }}"
                            class="nav-link @if(strstr(url()->current(), 'operational_programs')) active @endif">
                            <i class="fas fa-cube nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.operational_programs', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.consultations.public_consultations.index') }}"
                            class="nav-link @if(strstr(url()->current(), 'public_consultations')) active @endif">
                            <i class="fas fa-bullhorn nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.consultations', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.consultations.comments.index') }}"
                            class="nav-link @if(strstr(url()->current(), 'comments')) active @endif">
                            <i class="fas fa-comment nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.comments', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.nomenclature.consultation_document_type') }}"
                            class="nav-link @if(strstr(url()->current(), 'consultation_document_type')) active @endif">
                            <i class="fas fa-cube nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.nomenclature.consultation_document_type', 2) }}</p>
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
                                <i class="fas fa-calendar nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.ogp.plan_elements', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.ogp.articles.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'ogp/articles')) active @endif">
                                <i class="fas fa-info nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.ogp.articles', 2) }}</p>
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
                                <i class="fas fa-folder nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.link_categories', 2) }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.links.index') }}"
                            class="nav-link @if(Str::endsWith(url()->current(), 'links')) active @endif">
                                <i class="fas fa-list nav-icon nav-item-sub-icon"></i>
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
                                <i class="fas fa-list nav-icon nav-item-sub-icon"></i>
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
                                <i class="fas fa-list nav-icon nav-item-sub-icon"></i>
                                <p>{{ trans_choice('custom.legislative_initiatives_list', 2) }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">{{ trans_choice('custom.nomenclatures', 2) }}</li>
                    @canany(['manage.*', 'manage.advisory'])
                    @endcan
                    @can('manage.nomenclatures')
                    <li class="nav-item">
                        <a href="{{route('admin.nomenclature')}}"
                           class="nav-link @if(strstr(url()->current(), 'nomenclature')) active @endif">
                            <i class="fas fa-file"></i>
                            <p>{{ trans_choice('custom.nomenclatures', 2) }}</p>
                        </a>
                    </li>
                    @endcan
                    <li class="nav-header">{{ trans_choice('custom.users', 2) }}</li>
                    <li class="nav-item">
                        <a href="{{route('admin.roles')}}"
                           class="nav-link @if(strstr(url()->current(), 'roles')) active @endif">
                            <i class="fas fa-users"></i>
                            <p>{{ trans_choice('custom.roles', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.users')}}"
                           class="nav-link @if(strstr(url()->current(), 'users')) active @endif">
                            <i class="fas fa-user"></i>
                            <p>{{ trans_choice('custom.users', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.permissions')}}"
                           class="nav-link @if(strstr(url()->current(), 'permissions')) active @endif">
                            <i class="fas fa-gavel"></i>
                            <p>{{ trans_choice('custom.permissions', 2) }}</p>
                        </a>
                    </li>

                    <li class="nav-header">{{ trans_choice('custom.activity_logs', 1) }}</li>
                    <li class="nav-item">
                        <a href="{{route('admin.activity-logs')}}"
                           class="nav-link @if(strstr(url()->current(), 'activity-logs')) active @endif">
                            <i class="fas fa-history"></i>
                            <p>{{ trans_choice('custom.activity_logs', 2) }}</p>
                        </a>
                    </li>
                @endcan

                @if($user)
                    <li class="nav-header">Лични данни</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.profile.edit', $user->id) }}"
                           class="nav-link @if(strstr(url()->current(), 'users/profile/')) active @endif">
                            <i class="fas fa-user-cog"></i>
                            <p>{{ trans_choice('custom.profiles', 1) }}</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
