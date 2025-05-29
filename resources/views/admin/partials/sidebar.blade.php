<link rel="stylesheet" href="{{ mix('css/app.css') }}">
<script src="{{ mix('js/app.js') }}"></script>

<div class="sidebar-menu">
    <div class="sidebar-menu-inner">
        <header class="logo-env">
            <div class="logo">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/Smarthand.png') }}" width="130" alt="Logo" />
                </a>
            </div>

            <div class="sidebar-collapse">
                <a href="#" class="sidebar-collapse-icon">
                    <i class="entypo-menu"></i>
                </a>
            </div>

            <div class="sidebar-mobile-menu visible-xs">
                <a href="#" class="with-animation">
                    <i class="entypo-menu"></i>
                </a>
            </div>
        </header>

        <div class="sidebar-user-info">
            <div class="sui-normal">
                <a href="#" class="user-link">
                    <img src="{{ asset('images/admin-logo.png') }}" width="80" alt="User" class="img-circle" />
                    <span>Welcome,</span>
                    <strong>Ms. Gina Arban!</strong>
                </a>
            </div>
        </div>

        <ul id="main-menu" class="main-menu">
            <li class="{{ request()->is('dashboard') ? 'opened active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="entypo-gauge"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>

            <!-- <li class="has-sub {{ request()->is('admin/customers*')  ? 'opened active' : '' }}">
                <a href="#" class="menu-toggle">
                    <i class="entypo-users"></i>
                    <span class="title">Customer Management</span>
                </a>
                <ul class="sub-menu {{ request()->is('admin/customers*') ? 'show' : '' }}">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/customers') ? 'active' : '' }}" href="{{ route('customers.index') }}">Customer Database</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/bookings') ? 'active' : '' }}" href="{{ route('bookings.index') }}">Online Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/customers/portal') ? 'active' : '' }}" href="{{ route('customers.portal') }}">Customer Portal</a>
                    </li>
                </ul>
            </li> -->

            @php
                $currentRoute = Route::currentRouteName();
            @endphp

            <li class="has-sub {{ in_array($currentRoute, ['customers.index', 'bookings.index', 'customers.portal']) ? 'opened active' : '' }}">
                <a href="#" class="menu-toggle">
                    <i class="entypo-layout"></i>
                    <span class="title">Customer Management</span>
                </a>
                <ul>
                    <li class="{{ $currentRoute == 'customers.index' ? 'active' : '' }}">
                        <a href="{{ route('customers.index') }}"><span class="title">Customer Database</span></a>
                    </li>
                    <li class="{{ $currentRoute == 'bookings.index' ? 'active' : '' }}">
                        <a href="{{ route('bookings.index') }}"><span class="title">Online Booking/Scheduling</span></a>
                    </li>

                    <li class="{{ $currentRoute == 'customers.portal' ? 'active' : '' }}">
                        <a href="{{ route('customers.portal') }}"><span class="title">Customer Portal</span></a>
                    </li>
                </ul>
            </li>



            <li class="has-sub {{ in_array($currentRoute, ['services.index', 'jobs.dispatch', 'jobs.tracking', 'checklists.index']) ? 'opened active' : '' }}">
                <a href="#" class="menu-toggle">
                    <i class="entypo-briefcase"></i>
                    <span class="title">Job/Service Management</span>
                </a>
                <ul>
                    <li class="{{ $currentRoute == 'services.index' ? 'active' : '' }}">
                        <a href="{{ route('services.index') }}"><span class="title">Service Catalog</span></a>
                    </li>
                    <li class="{{ $currentRoute == 'jobs.dispatch' ? 'active' : '' }}">
                        <a href="{{ route('jobs.dispatch') }}"><span class="title">Job Dispatch</span></a>
                    </li>

                    <li class="{{ $currentRoute == 'jobs.tracking' ? 'active' : '' }}">
                        <a href="{{ route('jobs.tracking') }}"><span class="title">Job Tracking</span></a>
                    </li>
                    <li class="{{ $currentRoute == 'checklists.index' ? 'active' : '' }}">
                        <a href="{{ route('checklists.index') }}"><span class="title">Checklists</span></a>
                    </li>
                </ul>
            </li>

            <li class="has-sub {{ in_array($currentRoute, ['employees.index', 'employees.performance', 'employees.assignments']) ? 'opened active' : '' }}">
                <a href="#" class="menu-toggle">
                    <i class="entypo-user"></i>
                    <span class="title">Employee/Cleaner Management</span>
                </a>
                <ul>
                    <li class="{{ $currentRoute == 'employees.index' ? 'active' : '' }}">
                        <a href="{{ route('employees.index') }}"><span class="title">Cleaner Database</span></a>
                    </li>
                    <li class="{{ $currentRoute == 'employees.performance' ? 'active' : '' }}">
                        <a href="{{ route('employees.performance') }}"><span class="title">Performance</span></a>
                    </li>

                    <li class="{{ $currentRoute == 'employees.assignments' ? 'active' : '' }}">
                        <a href="{{ route('employees.assignments') }}"><span class="title">Assignments</span></a>
                    </li>
                </ul>
            </li>

            <li class="has-sub {{ in_array($currentRoute, ['reports.customers.list', 'reports.customers.history', 'reports.customers.feedback', 'reports.customers.retention']) ? 'opened active' : '' }}">
                <a href="#" class="menu-toggle">
                    <i class="entypo-doc-text"></i>
                    <span class="title">Customer Reports</span>
                </a>
                <ul>
                    <li class="{{ $currentRoute == 'reports.customers.list' ? 'active' : '' }}">
                        <a href="{{ route('reports.customers.list') }}"><span class="title">Customer List</span></a>
                    </li>
                    <li class="{{ $currentRoute == 'reports.customers.history' ? 'active' : '' }}">
                        <a href="{{ route('reports.customers.history') }}"><span class="title">Cleaning History</span></a>
                    </li>

                    <li class="{{ $currentRoute == 'reports.customers.feedback' ? 'active' : '' }}">
                        <a href="{{ route('reports.customers.feedback') }}"><span class="title">Feedbacks/Ratings</span></a>
                    </li>
                    <li class="{{ $currentRoute == 'reports.customers.retention' ? 'active' : '' }}">
                        <a href="{{ route('reports.customers.retention') }}"><span class="title">Customer Retention</span></a>
                    </li>
                </ul>
            </li>

            
            <li class="{{ request()->is('admin/reports/jobs*') ? 'opened active' : '' }}">
                <a href="{{ route('reports.jobs.completion') }}">
                    <i class="entypo-check"></i>
                    <span class="title">Job Completion Report</span>
                </a>
            </li>
        </ul>
    </div>
</div>