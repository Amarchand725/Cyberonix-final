<nav
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar" >
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
                <i class="ti ti-search ti-md me-2"></i>
                <span class="d-none d-md-inline-block text-muted">Search (Ctrl+/)</span>
                </a>
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Style Switcher -->
            <li class="nav-item me-2 me-xl-0">
                <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                <i class="ti ti-md"></i>
                </a>
            </li>
            <!--/ Style Switcher -->

            <!-- Quick links  -->
            @if(Auth::user()->hasRole('Admin'))
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a
                    class="nav-link dropdown-toggle hide-arrow"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                    >
                    <i class="ti ti-layout-grid-add ti-md"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                    <div class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                        <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                        <a
                            href="javascript:void(0)"
                            class="dropdown-shortcuts-add text-body"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Add shortcuts"
                            ><i class="ti ti-sm ti-apps"></i
                        ></a>
                        </div>
                    </div>
                    <div class="dropdown-shortcuts-list scrollable-container">
                        <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                            <i class="ti ti-users fs-4"></i>
                            </span>
                            <a href="{{ route('employees.index') }}" class="stretched-link">User Management</a>
                            <small class="text-muted mb-0">Manage Users</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                            <i class="ti ti-lock fs-4"></i>
                            </span>
                            <a href="{{ route('roles.index') }}" class="stretched-link">Role Management</a>
                            <small class="text-muted mb-0">Permission</small>
                        </div>
                        </div>
                        <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                            <i class="tf-icons ti ti-car fs-4"></i>
                            </span>
                            <a href="{{ route('vehicles.index') }}" class="stretched-link">Fleet Management</a>
                            <small class="text-muted mb-0">Manage Fleet</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                            <i class="ti ti-settings fs-4"></i>
                            </span>
                            <a href="{{ route('settings.create') }}" class="stretched-link">Setting</a>
                            <small class="text-muted mb-0">Account Settings</small>
                        </div>
                        </div>
                    </div>
                    </div>
                </li>
            @endif
            <!-- Quick links -->

            <!-- Message Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                <a
                class="nav-link dropdown-toggle hide-arrow"
                href="{{ URL::to('/chat') }}"
                >
                <i class="ti ti-mail ti-md"></i>
                    <span class="badge bg-danger rounded-pill badge-notifications" id="message_total_show_number">1</span>
                </a>
            </li>
            <!-- Message Notification -->

            <!-- Announcement, Leave, Discrepancy Notifications -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                <a
                    class="nav-link dropdown-toggle hide-arrow"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                >
                <i class="ti ti-bell ti-md"></i>
                    <span class="badge bg-danger rounded-pill badge-notifications read-badge-notification">{{ count(auth()->user()->unreadnotifications) }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">Notification</h5>
                            <a
                                href="javascript:void(0)"
                                class="dropdown-notifications-all text-body read-all-notifications"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Mark all as read"
                                ><i class="ti ti-mail-opened fs-4"></i
                            ></a>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush">
                            @foreach (auth()->user()->unreadnotifications as $unreadnotification)
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex gap-2">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                @if(isset($unreadnotification->data['profile']) && !empty($unreadnotification->data['profile']))
                                                    <img class="rounded-circle" src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$unreadnotification->data['profile'], null) }}" alt="Avatar" style="width:50px; height:50px"/>
                                                @else
                                                    <img class="rounded-circle" src="{{ asset('public/admin/default.png') }}" alt="Avatar" style="width:50px; height:50px" />
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $unreadnotification->data['name'] }}</h6>
                                            <p class="mb-0">{{ $unreadnotification->data['title'] }}</p>
                                            <small class="text-muted">{{ $unreadnotification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read">
                                                <span class="badge badge-dot"></span>
                                            </a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive">
                                                <span class="ti ti-x"></span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="dropdown-menu-footer border-top">
                        <a href="{{ route('notifications.index') }}"
                            class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Announcement, Leave, Discrepancy Notifications -->

            <!-- User Profile -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    @if(isset(Auth::user()->profile) && !empty(Auth::user()->profile->profile))
                        <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.Auth::user()->profile->profile, null) }}" style="width:40px !important; height:40px !important;  object-fit:cover;" alt class="h-auto rounded-circle" />
                    @else
                        <img src="{{ asset('public/admin') }}/default.png" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
                    @endif
                </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        @if(isset(Auth::user()->profile) && !empty(Auth::user()->profile->profile))
                                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.Auth::user()->profile->profile, null) }}" style="width:40px !important; height:40px !important;  object-fit:cover;" alt class="h-auto rounded-circle" />
                                        @else
                                            <img src="{{ asset('public/admin') }}/default.png" style="width:40px !important; height:40px !important;" alt class="h-auto rounded-circle" />
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                    <small class="text-muted">
                                        {{ Auth::user()->getRoleNames()->first() }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="ti ti-user-check me-2 ti-sm"></i>
                        <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    @can('setting-create')
                        <li>
                            <a class="dropdown-item" href="{{ route('settings.create') }}">
                                <i class="ti ti-settings me-2 ti-sm"></i>
                                <span class="align-middle">Settings</span>
                            </a>
                        </li>
                    @endcan
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('user.logout') }}">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- User Profile -->
        </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input
        type="text"
        class="form-control search-input container-xxl border-0"
        placeholder="Search..."
        aria-label="Search..."
        data-logined-role="{{ Auth::user()->getRoleNames()->first() }}"
        data-base-url="{{ url('/') }}";
        />
        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
</nav>
