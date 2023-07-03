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
                    <li class="nav-header">{{ trans_choice('custom.nomenclatures', 2) }}</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.institution_level') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/institution_level')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.institution_level', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.consultation_category') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/consultation_category')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.consultation_category', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.act_type') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/act_type')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.act_type', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link @if(Str::contains(url()->current(), ['nomenclature/legal_act_type'])) active @endif">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>{{ trans_choice('custom.legal_information', 1) }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('admin.nomenclature.legal_act_type') }}"
                                   class="nav-link @if(strstr(url()->current(), 'nomenclature/legal_act_type')) active @endif">
                                    <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                                    <p>{{ trans_choice('custom.nomenclature.legal_act_type', 2) }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.strategic_document_level') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/strategic_document_level')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.strategic_document_level', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.strategic_document_type') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/strategic_document_type')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.strategic_document_type', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.authority_accepting_strategic') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/authority_accepting_strategic')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.authority_accepting_strategic', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.authority_advisory_board') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/authority_advisory_board')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.authority_advisory_board', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.advisory_act_type') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/advisory_act_type')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.advisory_act_type', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.strategic_act_type') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/strategic_act_type')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.strategic_act_type', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.advisory_chairman_type') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/advisory_chairman_type')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.advisory_chairman_type', 2) }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.nomenclature.document_type') }}"
                            class="nav-link @if(strstr(url()->current(), 'nomenclature/document_type')) active @endif">
                            <i class="fas fa-circle nav-icon nav-item-sub-icon"></i>
                            <p>{{ trans_choice('custom.nomenclature.document_type', 2) }}</p>
                        </a>
                    </li>

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
