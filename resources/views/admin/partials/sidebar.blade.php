<div class="sidebar-menu">
    <div class="sidebar-menu-inner">
        <header class="logo-env">
            @php
    use App\Models\Setting;
    use Illuminate\Support\Str;
    $companyLogo = Setting::getValue('company_logo');
@endphp
            <div class="logo">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ $companyLogo ? asset('storage/' . $companyLogo) : asset('images/Smarthands.png') }}" width="130" alt="Logo" />
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
                <a href="{{ route('admin.profile.show') }}" class="user-link">
                    @if(Auth::user()->profile_photo_url && !Str::endsWith(Auth::user()->profile_photo_url, 'admin-logo.png'))
                        <img src="{{ Auth::user()->profile_photo_url }}" width="80" alt="User" style="border-radius: 50%; object-fit: cover; aspect-ratio: 1/1;" />
                    @else
                        <i class="entypo-user" style="font-size: 48px; color: #aaa;"></i>
                    @endif
                    <span>Welcome, </span>
                    <strong>{{ Auth::user()->name ?? 'Admin' }}</strong>
                </a>
            </div>
        </div>

        <ul id="main-menu" class="main-menu">
            <!-- Top Level: Dashboard & Profile -->
            <li class="{{ (request()->is('admin') || request()->is('admin/dashboard') || Route::currentRouteName() === 'admin.dashboard') ? 'opened active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="entypo-gauge"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/profile*') ? 'opened active' : '' }}">
                <a href="{{ route('admin.profile.show') }}">
                    <i class="entypo-user"></i>
                    <span class="title">My Profile</span>
                </a>
            </li>

            <!-- Main Management Sections -->
            @php $currentRoute = Route::currentRouteName(); @endphp

            <li class="has-sub {{ in_array($currentRoute, [
                'admin.customers.index', 'admin.customers.show', 'admin.customers.edit', 'admin.customers.create',
                'bookings.index', 'bookings.show', 'bookings.edit', 'bookings.create',
                'bookings.confirm', 'bookings.cancel', 'bookings.reschedule']) ? 'opened active' : '' }}">
                <a href="#" class="menu-toggle">
                    <i class="entypo-layout"></i>
                    <span class="title">Customer Management</span>
                </a>
                <ul>
                    <li class="{{ Str::startsWith($currentRoute, 'admin.customers.') ? 'active' : '' }}">
                        <a href="{{ route('admin.customers.index') }}"><span class="title">Customer Database</span></a>
                    </li>
                    <li class="{{ Str::startsWith($currentRoute, 'bookings.') ? 'active' : '' }}">
                        <a href="{{ route('bookings.index') }}">
                            <span class="title">Online Booking/Scheduling</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="has-sub {{ in_array($currentRoute, [
                'jobs.dispatch', 'jobs.tracking', 'jobs.show', 'jobs.assign',
                'jobs.complete', 'jobs.update-status', 'jobs.reassign', 'jobs.update-tracking',
                'checklists.index', 'checklists.add', 'checklists.edit']) ? 'opened active' : '' }}">
                <a href="#" class="menu-toggle">
                    <i class="entypo-briefcase"></i>
                    <span class="title">Job/Service Management</span>
                </a>
                <ul>
                    <li class="{{ in_array($currentRoute, ['jobs.tracking', 'jobs.show', 'jobs.assign', 'jobs.complete', 'jobs.update-status', 'jobs.reassign']) ? 'active' : '' }}">
                        <a href="{{ route('jobs.tracking') }}"><span class="title">Job Tracking</span></a>
                    </li>
                    <li class="{{ Str::startsWith($currentRoute, 'checklists.') ? 'active' : '' }}">
                        <a href="{{ route('checklists.index') }}"><span class="title">Checklists</span></a>
                    </li>
                </ul>
            </li>

            <li class="has-sub {{ in_array($currentRoute, [
                'employees.index', 'employees.show', 'employees.edit', 'employees.create',
                'employees.performance', 'employees.assignments']) ? 'opened active' : '' }}">
                <a href="#" class="menu-toggle">
                    <i class="entypo-users"></i>
                    <span class="title">Employee/Cleaner Management</span>
                </a>
                <ul>
                    <li class="{{ in_array($currentRoute, ['employees.index', 'employees.show', 'employees.edit', 'employees.create']) ? 'active' : '' }}">
                        <a href="{{ route('employees.index') }}"><span class="title">Employee Database</span></a>
                    </li>
                    <li class="{{ $currentRoute == 'employees.performance' ? 'active' : '' }}">
                        <a href="{{ route('employees.performance') }}"><span class="title">Performance</span></a>
                    </li>
                </ul>
            </li>

            <!-- Website Management Section -->
            <li class="has-sub {{ in_array($currentRoute, [
                'services.index', 'services.show', 'services.edit', 'services.create',
                'admin.disabled_dates.index',
                'admin.hero_media.index', 'admin.hero_media.show', 'admin.hero_media.edit', 'admin.hero_media.create',
                'admin.contact.edit', 'admin.settings.logo.edit']) ? 'opened active' : '' }}">
                <a href="#" class="menu-toggle">
                    <i class="entypo-globe"></i>
                    <span class="title">Website Management</span>
                </a>
                <ul>
                    <li class="{{ Str::startsWith($currentRoute, 'services.') ? 'active' : '' }}">
                        <a href="{{ route('services.index') }}"><span class="title">Service Page</span></a>
                    </li>
                    <li class="{{ Str::startsWith($currentRoute, 'admin.disabled_dates.') ? 'active' : '' }}">
                        <a href="{{ route('admin.disabled_dates.index') }}"><span class="title">Disabled Dates</span></a>
                    </li>
                    <li class="{{ request()->is('admin/hero-media*') ? 'active' : '' }}">
                        <a href="{{ route('admin.hero_media.index') }}"><span class="title">Homepage Banner</span></a>
                    </li>
                    <li class="{{ request()->is('admin/contact*') ? 'active' : '' }}">
                        <a href="{{ route('admin.contact.edit') }}"><span class="title">Contact Information</span></a>
                    </li>
                    <li class="{{ request()->is('settings/logo') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings.logo.edit') }}"><span class="title">Company Logo</span></a>
                    </li>
                </ul>
            </li>

            <!-- Reports Section -->
            <li class="has-sub {{ in_array($currentRoute, [
                'reports.customers.list', 'reports.customers.history',
                'reports.customers.feedback', 'reports.customers.retention',
                'reports.jobs.completion']) ? 'opened active' : '' }}">
                <a href="#" class="menu-toggle">
                    <i class="entypo-doc-text"></i>
                    <span class="title">Reports</span>
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
                    <li class="{{ $currentRoute == 'reports.jobs.completion' ? 'active' : '' }}">
                        <a href="{{ route('reports.jobs.completion') }}"><span class="title">Job Completion</span></a>
                    </li>
                </ul>
            </li>

    </div>
</div>