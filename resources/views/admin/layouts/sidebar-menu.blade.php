@php use Illuminate\Support\Facades\Gate; @endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('/dashboard') }}" class="app-brand-link">
            @if(checkRocketFlareUser() == 1 && checkRocketFlareUser() != 2)
            <input type="hidden" value="{{checkRocketFlareUser() ?? '0'}}">
            <img src="{{ asset('public/admin/assets/img/rocketflare/light-logo.png') }}" class="img-fluid light-logo img-logo" alt="Rocket Flare" />
            <img src="{{ asset('public/admin/assets/img/rocketflare/light-logo.png') }}" class="img-fluid dark-logo img-logo" alt="Rocket Flare" />
            @else

            @if(isset(settings()->logo) && !empty(settings()->logo))
            <img src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->logo }}" class="img-fluid light-logo img-logo" alt="{{ settings()->name }}" />
            <img src="{{ asset('public/admin/assets/img/logo/dark-logo.png') }}" class="img-fluid dark-logo img-logo" alt="{{ settings()->name }}" />
            @else
            <img src="{{ asset('public/admin/default.png') }}" class="img-fluid light-logo img-logo" alt="Default" />
            @endif


            @endif

        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->is('dashboard')?'active':'' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-home-2"></i>
                <div data-i18n="Dashboards">Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('chat')?'active':'' }}">
            <a href="{{ route('chat.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Chat">Chat</div>
            </a>
        </li>

        @if(
        Gate::check('notifications-list')
        )
        <li class="menu-item {{
                    request()->is('notifications')
                    ?'open active':''
                }}">
            @can('notifications-list')
            <a href="{{ route('notifications.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-bell"></i>
                <div data-i18n="Notificatios">Notifications</div>
            </a>
            @endcan
        </li>
        @endif

        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Apps &amp; Services</span>
        </li>
        @canany(['ip_management-list'])
        <li class="menu-item @if(Route::currentRouteName() == 'ip-managements.index')  open active  @endif ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-globe"></i>
                <div data-i18n="Ip Management">Ip Management</div>
            </a>
            <ul class="menu-sub">
                @can("ip_management-list")
                <li class="menu-item @if(Route::currentRouteName() == 'ip-managements.index')   active  @endif ">
                    <a href="{{ route('ip-managements.index') }}" class="menu-link">
                        <div data-i18n="All Ips">List</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany
        @if(
        Gate::check('employee_salary_details-list') ||
        Gate::check('monthly_salary_report-list') ||
        Gate::check('bank_accounts-create') ||
        Gate::check('bank_accounts-edit')
        )

        <li class="menu-item {{
                    request()->is('employees/salary_details') ||
                    request()->is('employees/salary_details/*') ||
                    request()->is('bank_accounts/create') ||
                    request()->is('monthly_salary_reports') ||
                    request()->is('monthly_salary_reports/*') ||
                    request()->is('bank_accounts/edit/*')
                    ?'open active':''
                }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-wallet"></i>
                <div data-i18n="Salary">Salary</div>
            </a>
            <ul class="menu-sub">
                @can('employee_salary_details-list')
                <li class="menu-item {{ request()->is('employees/salary_details') || request()->is('employees/salary_details/*')?'active':'' }}">
                    <a href="{{ route('employees.salary_details') }}" class="menu-link">
                        <div data-i18n="Salary Details">Salary Details</div>
                    </a>
                </li>
                @endcan

                @if(
                Gate::check('bank_accounts-create') ||
                Gate::check('bank_accounts-edit')
                )
                @if(!empty(bankDetail()))
                <li class="menu-item {{ request()->is('bank_accounts/edit/*')?'active':'' }}">
                    <a href="{{ route('bank_accounts.edit', bankDetail()->id) }}" class="menu-link">
                        <div data-i18n="Bank Account">Bank Account</div>
                    </a>
                </li>
                @else
                <li class="menu-item {{ request()->is('bank_accounts/create')?'active':'' }}">
                    <a href="{{ route('bank_accounts.create') }}" class="menu-link">
                        <div data-i18n="Bank Account">Bank Account</div>
                    </a>
                </li>
                @endif
                @endif
                @can('monthly_salary_report-list')
                <li class="menu-item {{ request()->is('monthly_salary_reports') || request()->is('monthly_salary_reports/*')?'active':'' }}">
                    <a href="{{ route('monthly_salary_reports.index') }}" class="menu-link">
                        <div data-i18n="Monthly Salary Report">Monthly Salary Report</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif

        @if(
        Gate::check('insurances-list') ||
        Gate::check('insurances-create')
        )
        @if(insuranceEligibility())
        <li class="menu-item {{
                    request()->is('insurances') ||
                    request()->is('insurances/trashed') ||
                    request()->is('insurances/create')
                    ?'open active':''
                }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-health-recognition"></i>
                <div data-i18n="Insurances">Insurance</div>
            </a>
            <ul class="menu-sub">
                @can('insurances-list')
                <li class="menu-item {{ request()->is('insurances') || request()->is('insurances/trashed')?'active':'' }}">
                    <a href="{{ route('insurances.index') }}" class="menu-link">
                        <div data-i18n="Insurances">Insurance List</div>
                    </a>
                </li>
                @endcan
                @if(!Auth::user()->hasRole('Admin'))
                @can('insurances-create')
                <li class="menu-item {{ request()->is('insurances/create')?'active':'' }}">
                    <a href="{{ route('insurances.create') }}" class="menu-link">
                        <div>Insurance</div>
                    </a>
                </li>
                @endcan
                @endif
            </ul>
        </li>
        @endif
        @endif

        @if(
        Gate::check('tickets-list') ||
        Gate::check('team_tickets-list') ||
        Gate::check('admin_team_tickets-list')
        )
        <li class="menu-item {{
                    request()->is('tickets') ||
                    request()->is('team_tickets-list') ||
                    request()->is('admin_team_tickets-list') ||
                    request()->is('tickets/trashed') ||
                    request()->is('tickets/all_tickets') ||
                    request()->is('tickets/waiting_approval_tickets') ||
                    request()->is('team/tickets') ||
                    request()->is('all/tickets/*') ||
                    request()->is('admin/tickets/all_tickets') ||
                    request()->is('team/tickets/*') ||
                    request()->is('admin/team/tickets')
                    ?'open active':''
                }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-ticket"></i>
                <div data-i18n="Tickets">Tickets</div>
            </a>
            <ul class="menu-sub">
                @can('tickets-list')
                <li class="menu-item {{ request()->is('tickets') || request()->is('all/tickets/*') || request()->is('tickets/trashed') ?'active':'' }}">
                    <a href="{{ route('tickets.index') }}" class="menu-link">
                        <div data-i18n="My Tickets">My Tickets</div>
                    </a>
                </li>
                @endcan
                @can(['team_tickets-list'])
                <li class="menu-item {{ request()->is('team/tickets') || request()->is('team/tickets/*')?'active':'' }}">
                    <a href="{{ route('team.tickets') }}" class="menu-link">
                        <div data-i18n="Tickets">Team Tickets</div>
                    </a>
                </li>
                @endcan
                @can(['admin_team_tickets-list'])
                <li class="menu-item {{ request()->is('admin/team/tickets') || request()->is('team/tickets/*')?'active':'' }}">
                    <a href="{{ route('admin.team.tickets') }}" class="menu-link">
                        <div data-i18n="Tickets">Team Tickets</div>
                    </a>
                </li>
                @endcan
                @can('all_tickets-list')
                <li class="menu-item {{ request()->is('tickets/all_tickets')?'active':'' }}">
                    <a href="{{ route('tickets.all_tickets') }}" class="menu-link">
                        <div data-i18n="All Tickets">All Tickets</div>
                    </a>
                </li>
                @endcan
                @can('admin_all_tickets-list')
                <li class="menu-item {{ request()->is('admin/tickets/all_tickets')?'active':'' }}">
                    <a href="{{ route('admin.tickets.all_tickets') }}" class="menu-link">
                        <div data-i18n="All Tickets">All Tickets</div>
                    </a>
                </li>
                @endcan
                @can('waiting_for_approval_tickets-list')
                <li class="menu-item {{ request()->is('tickets.waiting_approval_tickets') || request()->is('tickets/waiting_approval_tickets')?'active':'' }}">
                    <a href="{{ route('tickets.waiting_approval_tickets') }}" class="menu-link">
                        <div data-i18n="Waiting for Approval">Waiting for Approval</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif

        @if(
        Gate::check('employee_letters-list') ||
        Gate::check('employee_all_letters-list')
        )
        <li class="menu-item {{
                    request()->is('employee_letters') ||
                    request()->is('employee_letters.all_letters') ||
                    request()->is('employee_letters/trashed')
                    ?'open active':''
                }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-certificate"></i>
                <div data-i18n="Letters">Letters</div>
            </a>
            <ul class="menu-sub">
                @can('employee_letters-list')
                <li class="menu-item {{ request()->is('employee_letters') || request()->is('employee_letters/trashed')?'active':'' }}">
                    <a href="{{ route('employee_letters.index') }}" class="menu-link">
                        <div data-i18n="Employee Letters">Employee Letters</div>
                    </a>
                </li>
                @endcan
                @can('employee_all_letters-list')
                <li class="menu-item {{ request()->is('employee_letters/all_letters') || request()->is('employee_letters/all_letters')?'active':'' }}">
                    <a href="{{ route('employee_letters.all_letters') }}" class="menu-link">
                        <div data-i18n="Employee Letters">Employee Letters</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif

        @if(
        Gate::check('employee_leave_requests-list') ||
        Gate::check('manager_team_leaves-list') ||
        Gate::check('employee_leave_report-list') ||
        Gate::check('admin_leave_reports-list')
        )
        @if(!isOnProbation(Auth::user()))
        <li class="menu-item {{
                        request()->is('user_leaves') ||
                        request()->is('user/leaves/*') ||
                        request()->is('team/leaves') ||
                        request()->is('manager/team/leaves') ||
                        request()->is('team/leaves/*') ||
                        request()->is('user_leaves/report') ||
                        request()->is('employee/leaves/report') ||
                        request()->is('user_leaves/report/*')
                        ?'open active':''
                    }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-clock"></i>
                <div data-i18n="Leaves">Leaves</div>
            </a>
            <ul class="menu-sub">
                @can('employee_leave_requests-list')
                <li class="menu-item {{ request()->is('user_leaves')?'active':'' }}">
                    <a href="{{ route('user_leaves.index') }}" class="menu-link">
                        <div data-i18n="Leave Status">Leaves</div>
                    </a>
                </li>
                @endcan
                @can('team_leaves-list')
                <li class="menu-item {{ request()->is('team/leaves') || request()->is('team/leaves/*')?'active':'' }}">
                    <a href="{{ route('team.leaves') }}" class="menu-link">
                        <div data-i18n="Team Leaves">Team Leaves</div>
                    </a>
                </li>
                @endcan
                @can('manager_team_leaves-list')
                <li class="menu-item {{ request()->is('manager/team/leaves') || request()->is('manager/team/leaves/*')?'active':'' }}">
                    <a href="{{ route('manager.team.leaves') }}" class="menu-link">
                        <div data-i18n="Team Leaves">Team Leaves</div>
                    </a>
                </li>
                @endcan
                @can('employee_leave_report-list')
                <li class="menu-item {{ request()->is('employee/leaves/report') || request()->is('employee/leaves/report/*')?'active':'' }}">
                    <a href="{{ route('employee.leaves.report') }}" class="menu-link">
                        <div data-i18n="Leave Report">Leave Report</div>
                    </a>
                </li>
                @endcan
                @can('admin_leave_reports-list')
                <li class="menu-item {{ request()->is('user_leaves/report') || request()->is('user_leaves/report/*')?'active':'' }}">
                    <a href="{{ route('user_leaves.report') }}" class="menu-link">
                        <div data-i18n="Leave Report">Leave Report</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
        @endif

        @if(
        Gate::check('admin_attendance_daily_log-list') ||
        Gate::check('employee_attendance_daily_log-list') ||
        Gate::check('employee_summary-list') ||
        Gate::check('admin_summary-list') ||
        Gate::check('attendance_monthly_report-list') ||
        Gate::check('admin_attendance_filter-list') ||
        Gate::check('employee_attendance_filter-list') ||
        Gate::check('employee_discrepancies-list') ||
        Gate::check('manager_team_discrepancies-list')
        )
        <li class="menu-item {{
                    request()->is('user/discrepancies') ||
                    request()->is('user/discrepancies/*') ||
                    request()->is('team/discrepancies') ||
                    request()->is('manager/team/discrepancies') ||
                    request()->is('team/discrepancies/*') ||
                    request()->is('employee/attendance/summary') ||
                    request()->is('employee/monthly/attendance/report') ||
                    request()->is('user/attendance/summary') ||
                    request()->is('user/attendance/summary') ||
                    request()->is('user/attendance/summary/*') ||
                    request()->is('user/attendance/terminated_employee_summary') ||
                    request()->is('user/attendance/terminated_employee_summary/*') ||
                    request()->is('employee/attendance/advance-filter/summary') ||
                    request()->is('user/attendance/advance-filter/summary') ||
                    request()->is('user/attendance/daily-log/*') ||
                    request()->is('user/attendance/daily-log') ||
                    request()->is('employee/attendance/daily-log')
                    ?'open active':''
                }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-calendar"></i>
                <div data-i18n="Attendance">Attendance</div>
            </a>
            <ul class="menu-sub">
                @can('employee_attendance_daily_log-list')
                <li class="menu-item {{ request()->is('employee/attendance/daily-log') || request()->is('employee/attendance/daily-log/*')?'active':'' }}">
                    <a href="{{ route('employee.attendance.daily-log') }}" class="menu-link">
                        <div data-i18n="Daily Log">Daily Log</div>
                    </a>
                </li>
                @endcan
                @can('admin_attendance_daily_log-list')
                <li class="menu-item {{ request()->is('user/attendance/daily-log') || request()->is('user/attendance/daily-log/*')?'active':'' }}">
                    <a href="{{ route('user.attendance.daily-log') }}" class="menu-link">
                        <div data-i18n="Daily Log">Daily Log</div>
                    </a>
                </li>
                @endcan
                @can('employee_discrepancies-list')
                <li class="menu-item {{ request()->is('user/discrepancies')?'active':'' }}">
                    <a href="{{ route('user.discrepancies') }}" class="menu-link">
                        <div data-i18n="Discrepancies">Discrepancies</div>
                    </a>
                </li>
                @endcan
                @can('team_discrepancies-list')
                <li class="menu-item {{ request()->is('team/discrepancies') || request()->is('team/discrepancies/*')?'active':'' }}">
                    <a href="{{ route('team.discrepancies') }}" class="menu-link">
                        <div data-i18n="Team Discrepancies">Team Discrepancies</div>
                    </a>
                </li>
                @endcan
                @can('manager_team_discrepancies-list')
                <li class="menu-item {{ request()->is('manager/team/discrepancies') || request()->is('manager/team/discrepancies/*')?'active':'' }}">
                    <a href="{{ route('manager.team.discrepancies') }}" class="menu-link">
                        <div data-i18n="Team Discrepancies">Team Discrepancies</div>
                    </a>
                </li>
                @endcan
                @can('employee_summary-list')
                <li class="menu-item {{ request()->is('employee/attendance/summary') || request()->is('employee/attendance/summary/*')?'active':'' }}">
                    <a href="{{ route('employee.attendance.summary') }}" class="menu-link">
                        <div data-i18n="Summary">Summary</div>
                    </a>
                </li>
                @endcan
                @can('admin_summary-list')
                <li class="menu-item {{ request()->is('user/attendance/summary') || request()->is('user/attendance/summary/*')?'active':'' }}">
                    <a href="{{ route('user.attendance.summary') }}" class="menu-link">
                        <div data-i18n="Summary">Summary</div>
                    </a>
                </li>
                @endcan
                @can('filter_summary-list')
                <li class="menu-item {{ request()->is('employee/attendance/advance-filter/summary')?'active':'' }}">
                    <a href="{{ route('employee.attendance.advance-filter.summary') }}" class="menu-link">
                        <div data-i18n="Summary">Filter Summary</div>
                    </a>
                </li>
                @endcan
                @can('admin_attendance_filter-list')
                <li class="menu-item {{ request()->is('user/attendance/advance-filter/summary')?'active':'' }}">
                    <a href="{{ route('user.attendance.advance-filter.summary') }}" class="menu-link">
                        <div data-i18n="Summary">Filter Summary</div>
                    </a>
                </li>
                @endcan
                @can('terminated_employee_summary-list')
                <li class="menu-item {{ request()->is('user/attendance/terminated_employee_summary') || request()->is('user/attendance/terminated_employee_summary/*')?'active':'' }}">
                    <a href="{{ route('user.attendance.terminated_employee_summary') }}" class="menu-link">
                        <div data-i18n="Terminate Summary">Terminated Summary</div>
                    </a>
                </li>
                @endcan

                @can('attendance_monthly_report-list')
                <li class="menu-item {{ request()->is('employee/monthly/attendance/report')?'active':'' }}">
                    <a href="{{ route('employee.monthly.attendance.report') }}" class="menu-link">
                        <div data-i18n="Monthly Report">Monthly Report</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif


        <!-- inventory -->
        @if(
        Gate::check('inventory_category-list') ||
        Gate::check('assets-list')
        )
        <li class="menu-item
            {{
                request()->is('inventory-category') ||
                request()->is('assets')
                ?'open active':''
            }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-package"></i>
                <div data-i18n="Attendance">Inventory</div>
            </a>
            <ul class="menu-sub">
                @can("inventory_category-list")
                <li class="menu-item @if(Route::currentRouteName() == 'inventory-category.index')   active  @endif ">
                    <a href="{{ route('inventory-category.index') }}" class="menu-link">
                        <div data-i18n="Inventory Categories">Categories</div>
                    </a>
                </li>
                @endcan
                @can("assets-list")
                <li class="menu-item  @if(Route::currentRouteName() == 'assets.index')   active  @endif">
                    <a href="{{ route('assets.index') }}" class="menu-link">
                        <div data-i18n="Assets">Assets</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
        <!-- inventory -->

        <!-- Grievance -->
        @if(
        Gate::check('grievance-list') ||
        Gate::check('my_grievance-list')
        )
        <li class="menu-item
                {{
                    request()->is('grievances') ||
                    request()->is('my-grievance')
                    ?'open active':''
                }}
            ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-dislike"></i>
                <div>Grievance</div>
            </a>
            <ul class="menu-sub">
                @can("grievance-list")
                <li class="menu-item @if(Route::currentRouteName() == 'grievances.index')   active  @endif ">
                    <a href="{{ route('grievances.index') }}" class="menu-link">
                        <div data-i18n="All Grievance">All Grievance</div>
                    </a>
                </li>
                @endcan
                @can("my_grievance-list")
                <li class="menu-item @if(Route::currentRouteName() == 'grievances.myGrievance') active  @endif ">
                    <a href="{{ route('grievances.myGrievance') }}" class="menu-link">
                        <div data-i18n="My Grievance">My Grievance</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
        <!-- Grievance -->

        @if(
        Gate::check('roles-list') ||
        Gate::check('permissions-list') ||
        Gate::check('authorize_emails-list') ||
        Gate::check('positions-list') ||
        Gate::check('work_shifts-list') ||
        Gate::check('departments-list') ||
        Gate::check('holidays-list') ||
        Gate::check('announcements-list') ||
        Gate::check('profile_cover_images-list') ||
        Gate::check('leave_types-list')
        )
        <li class="menu-item  {{
                    request()->is('roles') ||
                    request()->is('permissions') ||
                    request()->is('authorize_emails') ||
                    request()->is('positions') ||
                    request()->is('work_shifts') ||
                    request()->is('departments') ||
                    request()->is('holidays') ||
                    request()->is('announcements') ||
                    request()->is('profile_cover_images') ||
                    request()->is('leave_types')
                    ?'open active':''
                }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div data-i18n="Administration">Administration</div>
            </a>
            <ul class="menu-sub">
                @can('roles-list')
                <li class="menu-item {{ request()->is('roles')?'active':'' }}">
                    <a href="{{ route('roles.index') }}" class="menu-link">
                        <div data-i18n="Roles">Roles</div>
                    </a>
                </li>
                @endcan
                @can('permissions-list')
                <li class="menu-item {{ request()->is('permissions')?'active':'' }}">
                    <a href="{{ route('permissions.index') }}" class="menu-link">
                        <div data-i18n="Permission">Permission</div>
                    </a>
                </li>
                @endcan
                @can('authorize_emails-list')
                <li class="menu-item {{ request()->is('authorize_emails')?'active':'' }}">
                    <a href="{{ route('authorize_emails.index') }}" class="menu-link">
                        <div data-i18n="Authorize Users">Authorize Users</div>
                    </a>
                </li>
                @endcan
                @can('positions-list')
                <li class="menu-item {{ request()->is('positions')?'active':'' }}">
                    <a href="{{ route('positions.index') }}" class="menu-link">
                        <div data-i18n="Positions">Positions</div>
                    </a>
                </li>
                @endcan
                @can('work_shifts-list')
                <li class="menu-item {{ request()->is('work_shifts')?'active':'' }}">
                    <a href="{{ route('work_shifts.index') }}" class="menu-link">
                        <div data-i18n="Work Shifts">Work Shifts</div>
                    </a>
                </li>
                @endcan
                @can('departments-list')
                <li class="menu-item {{ request()->is('departments')?'active':'' }}">
                    <a href="{{ route('departments.index') }}" class="menu-link">
                        <div data-i18n="Departments">Departments</div>
                    </a>
                </li>
                @endcan

                @can('holidays-list')
                <li class="menu-item {{ request()->is('holidays')?'active':'' }}">
                    <a href="{{ route('holidays.index') }}" class="menu-link">
                        <div>Holidays</div>
                    </a>
                </li>
                @endcan

                @can('announcements-list')
                <li class="menu-item {{ request()->is('announcements')?'active':'' }}">
                    <a href="{{ route('announcements.index') }}" class="menu-link">
                        <div data-i18n="Announcements">Announcements</div>
                    </a>
                </li>
                @endcan
                @can('profile_cover_images-list')
                <li class="menu-item {{ request()->is('profile_cover_images')?'active':'' }}">
                    <a href="{{ route('profile_cover_images.index') }}" class="menu-link">
                        <div data-i18n="Profile Cover Images">Profile Cover Images</div>
                    </a>
                </li>
                @endcan
                @can('leave_types-list')
                <li class="menu-item {{ request()->is('leave_types')?'active':'' }}">
                    <a href="{{ route('leave_types.index') }}" class="menu-link">
                        <div data-i18n="Leave Types">Leave Types</div>
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endif

        @if(
        Gate::check('documents-list')
        )
        <li class="menu-item {{
                    request()->is('documents') ||
                    request()->is('documents/trashed')
                    ?'open active':''
                }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-spreadsheet"></i>
                <div data-i18n="Documents">Documents</div>
            </a>
            <ul class="menu-sub">
                @can('documents-list')
                <li class="menu-item {{ request()->is('documents') ?'active':'' }}">
                    <a href="{{ route('documents.index') }}" class="menu-link">
                        <div> Documents</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif

        @if(
        Gate::check('stationary_category-list') ||
        Gate::check('stationary-list')
        )
        <li class="menu-item {{
                request()->is('stationary_categories') ||
                request()->is('stationary')
                ?'open active':''
            }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-checklist"></i>
                <div>Stationary</div>
            </a>
            <ul class="menu-sub">
                @can('stationary_category-list')
                <li class="menu-item {{ request()->is('stationary_categories')?'active':'' }}">
                    <a href="{{ route('stationary_categories.index') }}" class="menu-link">
                        <div>Stationaries</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
    @endif

    @if(
        Gate::check('pre_employees-list')
    )
        <li class="menu-item {{
                request()->is('pre_employees') ||
                request()->is('pre_employees/trashed')
                ?'open active':''
            }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div data-i18n="Pre-Employees">Pre-Employees</div>
            </a>
            <ul class="menu-sub">
                @can('pre_employees-list')
                    <li class="menu-item {{ request()->is('pre_employees') || request()->is('pre_employees/trashed')?'active':'' }}">
                        <a href="{{ route('pre_employees.index') }}" class="menu-link">
                        <div data-i18n="All Pre-Employees">All Pre-Employees</div>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
    @endif

    @can('office_boys-list')
    <li class="menu-item  @if(Route::currentRouteName() == 'office_boys.index' || Route::currentRouteName() == 'office_boys.trashed') open active @endif ">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-user"></i>
            <div data-i18n="Office Boys">Office Boys</div>
        </a>
        <ul class="menu-sub">
            @can('office_boys-list')
            <li class="menu-item @if(Route::currentRouteName() == 'office_boys.index' || Route::currentRouteName() == 'office_boys.trashed' ) active @endif ">
                <a href="{{ route('office_boys.index') }}" class="menu-link">
                    <div data-i18n="All Office Boys">All Office Boys</div>
                </a>
            </li>

            @endcan
        </ul>
    </li>

    @endcan


    @if(
    Gate::check('resignations-list') ||
    Gate::check('terminated_employees-list') ||
    Gate::check('employee_resignations-list') ||
    Gate::check('team_resignations-list') ||
    Gate::check('admin_employee_re_hire-list') ||
    Gate::check('employee_rehire-list')
    )
    <li class="menu-item {{
                    request()->is('resignations') ||
                    request()->is('terminated_employees') ||
                    request()->is('resignations/employee_resignations') ||
                    request()->is('resignations/team_resignations') ||
                    request()->is('resignations/re-hired/employees') ||
                    request()->is('admin/resignations/re-hired/employees') ||
                    request()->is('resignations/trashed')
                    ?'open active':''
                }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
            <div>&nbsp; Resignations</div>
        </a>
        <ul class="menu-sub">
            @can('resignations-list')
            <li class="menu-item {{ request()->is('resignations') || request()->is('resignations/trashed') ?'active':'' }}">
                <a href="{{ route('resignations.index') }}" class="menu-link">
                    <div>My Resignations</div>
                </a>
            </li>
            @endcan
            @can('team_resignations-list')
            <li class="menu-item {{ request()->is('resignations/team_resignations') ?'active':'' }}">
                <a href="{{ route('resignations.team_resignations') }}" class="menu-link">
                    <div>Team Resignations</div>
                </a>
            </li>
            @endcan
            @can('employee_resignations-list')
            <li class="menu-item {{ request()->is('resignations/employee_resignations') ?'active':'' }}">
                <a href="{{ route('resignations.employee_resignations') }}" class="menu-link">
                    <div>All Resignations</div>
                </a>
            </li>
            @endcan
            @can('terminated_employees-list')
                <li class="menu-item {{ request()->is('terminated_employees')?'active':'' }}">
                    <a href="{{ route('terminated_employees') }}" class="menu-link">
                        <div>Terminated Employees</div>
                    </a>
                </li>
            @endcan
            @can('employee_rehire-list')
            <li class="menu-item {{ request()->is('resignations/re-hired/employees') ?'active':'' }}">
                <a href="{{ route('resignations.re-hired.employees') }}" class="menu-link">
                    <div>Re-hired Employees</div>
                </a>
            </li>
            @endcan

            @can('admin_employee_re_hire-list')
            <li class="menu-item @if(Route::currentRouteName() == 'resignations.hiringHistory') active @endif ">
                <a href="{{ route('resignations.hiringHistory') }}" class="menu-link">
                    <div data-i18n="Re Hiring History">Hiring History</div>
                </a>
            </li>
            @endcan
            @can('employee_rehire_history-list')
            <li class="menu-item ">
                <a href="{{ route('resignations.hiringHistory') }}" class="menu-link">
                    <div data-i18n="Re Hiring History">Hiring History</div>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endif

    @if(
    Gate::check('mark_attendance-list')
    )
    <li class="menu-item {{
                    request()->is('mark_attendance') ||
                    request()->is('mark_attendance/*')
                    ?'open active':''
                }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-checklist"></i>
            <div data-i18n="Attendance Adjustments">Adjustments</div>
        </a>
        <ul class="menu-sub">
            @can('mark_attendance-list')
            <li class="menu-item {{ request()->is('mark_attendance') ?'active':'' }}">
                <a href="{{ route('mark_attendance.index') }}" class="menu-link">
                    <div>Adjustment List</div>
                </a>
            </li>
            {{-- <li class="menu-item {{ request()->is('admin') ?'active':'' }}">
                <a href="{{ route('get.mark.attendance.by.admin') }}" class="menu-link">
                    <div>Bluck Adjustments</div>
                </a>
            </li> --}}
            @endcan
        </ul>
    </li>
    @endif

    @if(
    Gate::check('vehicles-list') ||
    Gate::check('vehicle_owners-list') ||
    Gate::check('vehicle_inspections-list') ||
    Gate::check('vehicle_users-list') ||
    Gate::check('vehicle_users.all_users') ||
    Gate::check('vehicle_allowances-list')
    )
    <li class="menu-item
                {{
                    request()->is('vehicles') ||
                    request()->is('vehicles/trashed') ||
                    request()->is('vehicle_owners') ||
                    request()->is('vehicle_owners/trashed') ||
                    request()->is('vehicle_inspections') ||
                    request()->is('vehicle_inspections/trashed') ||
                    request()->is('vehicle_users/all_users') ||
                    request()->is('vehicle_users/all_users') ||
                    request()->is('vehicle_users/trashed') ||
                    request()->is('vehicle_allowances') ||
                    request()->is('vehicle_allowances/trashed') ||
                    request()->is('vehicle_rents') ||
                    request()->is('vehicle_rents/trashed')
                    ?'open active':''
                }}
            ">
        @can('vehicle_users-list')
        @if(getCars())
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-car"></i>
            <div data-i18n="Fleet">Fleet</div>
        </a>
        @endif
        @endcan
        @can('admin_vehicle_users_list-list')
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-car"></i>
            <div data-i18n="Fleet">Fleet</div>
        </a>
        @endcan
        <ul class="menu-sub">
            @can('vehicle_inspections-list')
            <li class="menu-item {{ Route::is('vehicle_inspections.index') || Route::is('vehicle_inspections.trashed')?'active':'' }}">
                <a href="{{ route('vehicle_inspections.index') }}" class="menu-link">
                    <div>Inspection List</div>
                </a>
            </li>
            @endcan
            @can('vehicle_users-list')
            @if(getCars())
            <li class="menu-item {{ Route::is('vehicle_users.index') || Route::is('vehicle_users.trashed')?'active':'' }}">
                <a href="{{ route('vehicle_users.index') }}" class="menu-link">
                    <div>My Vehicles</div>
                </a>
            </li>
            @endif
            @endcan
            @can('admin_vehicle_users_list-list')
            <li class="menu-item {{ Route::is('vehicle_users.all_users')?'active':'' }}">
                <a href="{{ route('vehicle_users.all_users') }}" class="menu-link">
                    <div>Users Vehicle</div>
                </a>
            </li>
            @endcan
            @can('vehicle_allowances-list')
            <li class="menu-item {{ Route::is('vehicle_allowances.index') || Route::is('vehicle_allowances.trashed')?'active':'' }}">
                <a href="{{ route('vehicle_allowances.index') }}" class="menu-link">
                    <div>Users Allowances</div>
                </a>
            </li>
            @endcan
            @can('vehicle_rents-list')
            <li class="menu-item {{ Route::is('vehicle_rents.index') || Route::is('vehicle_rents.trashed')?'active':'' }}">
                <a href="{{ route('vehicle_rents.index') }}" class="menu-link">
                    <div>Vehicle Rents</div>
                </a>
            </li>
            @endcan
            @can('vehicles-list')
            <li class="menu-item {{ Route::is('vehicles.index') || Route::is('vehicles.trashed')?'active':'' }}">
                <a href="{{ route('vehicles.index') }}" class="menu-link">
                    <div>Vehicle List</div>
                </a>
            </li>
            @endcan
            @can('vehicle_owners-list')
            <li class="menu-item {{ Route::is('vehicle_owners.index') || Route::is('vehicle_owners.trashed')?'active':'' }}">
                <a href="{{ route('vehicle_owners.index') }}" class="menu-link">
                    <div>Vehicle Vendors</div>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endif

    @if(
    Gate::check('employees-list') ||
    Gate::check('employees_for_it-list') ||
    Gate::check('new_employee_joinings-list') ||
    Gate::check('employment_status-list') ||
    Gate::check('designations-list')
    )
    <li class="menu-item {{
                    request()->is('employees') ||
                    request()->is('employees_for_it') ||
                    request()->is('new_joinings') ||
                    request()->is('employees/trashed') ||
                    request()->is('wfh_employees') ||
                    request()->is('wfh_employees/*') ||
                    request()->is('designations') ||
                    request()->is('new_joinings') ||
                    request()->is('designations/trashed') ||
                    request()->is('employment_status') ||
                    request()->is('employment_status/trashed')
                    ?'open active':''
                }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-users"></i>
            <div data-i18n="Employees">Employees</div>
        </a>
        <ul class="menu-sub">
            @can('employees-list')
                <li class="menu-item {{ request()->is('employees') || request()->is('employees/trashed')?'active':'' }}">
                    <a href="{{ route('employees.index') }}" class="menu-link">
                        <div data-i18n="Employees">All Employees</div>
                    </a>
                </li>
            @endcan
            @can('employees_for_it-list')
                <li class="menu-item {{ request()->is('employees_for_it') ?'active':'' }}">
                    <a href="{{ route('employees_for_it') }}" class="menu-link">
                        <div>All Employees</div>
                    </a>
                </li>
            @endcan
            @can('new_employee_joinings-list')
                <li class="menu-item {{ request()->is('new_joinings') || request()->is('new_joinings')?'active':'' }}">
                    <a href="{{ route('new_joinings') }}" class="menu-link">
                        <div>New Joinings</div>
                    </a>
                </li>
            @endcan
            @can('wfh_employee-list')
            <li class="menu-item {{ request()->is('wfh_employees') || request()->is('wfh_employees/*') || request()->is('wfh_employees/trashed')?'active':'' }}">
                <a href="{{ route('wfh_employees.index') }}" class="menu-link">
                    <div data-i18n="WFH Employees">WFH Employees</div>
                </a>
            </li>
            @endcan
            @can('designations-list')
            <li class="menu-item {{ request()->is('designations') || request()->is('designations/trashed')?'active':'' }}">
                <a href="{{ route('designations.index') }}" class="menu-link">
                    <div data-i18n="Designations">Designations</div>
                </a>
            </li>
            @endcan
            @can('employment_status-list')
            <li class="menu-item {{ request()->is('employment_status') || request()->is('employment_status/trashed')?'active':'' }}">
                <a href="{{ route('employment_status.index') }}" class="menu-link">
                    <div data-i18n="Employment Status">Employment Status</div>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endif





    @if(
    Gate::check('bank_accounts-list') ||
    Gate::check('manager_team_member-list') ||
    Gate::check('team_members-list')
    )
    <li class="menu-item {{
                    request()->is('employees/teams-members') ||
                    request()->is('manager/teams-members') ||
                    request()->is('employees/teams-members/*') ||
                    request()->is('bank_accounts')
                    ?'open active':''
                }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-tag"></i>
            <div data-i18n="Team">Team</div>
        </a>
        <ul class="menu-sub">
            @can('team_members-list')
            <li class="menu-item {{ request()->is('employees/teams-members') || request()->is('employees/teams-members/*')?'active':'' }}">
                <a href="{{ route('employees.team-members') }}" class="menu-link">
                    <div data-i18n="Team">Team</div>
                </a>
            </li>
            @endcan
            @can('manager_team_member-list')
            <li class="menu-item {{ request()->is('manager/teams-members') || request()->is('manager/teams-members/*')?'active':'' }}">
                <a href="{{ route('manager.team-members') }}" class="menu-link">
                    <div data-i18n="Team">Team</div>
                </a>
            </li>
            @endcan
            @can('bank_accounts-list')
            <li class="menu-item {{ request()->is('bank_accounts')?'active':'' }}">
                <a href="{{ route('bank_accounts.index') }}" class="menu-link">
                    <div data-i18n="Bank Accounts">Bank Accounts</div>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endif

    @can('logs-list')
    <li class="menu-item {{
                        request()->is('logs')
                        ?'open active':''
                    }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-list"></i>
            <div data-i18n="Logs">Logs</div>
        </a>
        <ul class="menu-sub">
            @can('logs-list')
            <li class="menu-item {{ request()->is('logs')?'active':'' }}">
                <a href="{{ route('logs.index') }}" class="menu-link">
                    <div data-i18n="All Logs">Logs List</div>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endcan
    </ul>
</aside>
