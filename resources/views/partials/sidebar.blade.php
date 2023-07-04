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
