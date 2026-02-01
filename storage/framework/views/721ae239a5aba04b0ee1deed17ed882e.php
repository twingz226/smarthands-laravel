<div class="sidebar-menu">
    <div class="sidebar-menu-inner">
        <header class="logo-env">
            <?php
    use App\Models\Setting;
    use Illuminate\Support\Str;
    $companyLogo = Setting::getValue('company_logo');
?>
            <div class="logo">
                <a href="<?php echo e(route('admin.dashboard')); ?>">
                    <img src="<?php echo e($companyLogo ? asset('storage/' . $companyLogo) : asset('images/Smarthands.png')); ?>" width="130" alt="Logo" />
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
                <a href="<?php echo e(route('admin.profile.show')); ?>" class="user-link">
                    <?php if(Auth::user()->profile_photo_url && !Str::endsWith(Auth::user()->profile_photo_url, 'admin-logo.png')): ?>
                        <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" width="80" alt="User" style="border-radius: 50%; object-fit: cover; aspect-ratio: 1/1;" />
                    <?php else: ?>
                        <i class="entypo-user" style="font-size: 48px; color: #aaa;"></i>
                    <?php endif; ?>
                    <span>Welcome, </span>
                    <strong><?php echo e(Auth::user()->name ?? 'Admin'); ?></strong>
                </a>
            </div>
        </div>

        <ul id="main-menu" class="main-menu">
            <!-- Top Level: Dashboard & Profile -->
            <li class="<?php echo e((request()->is('admin') || request()->is('admin/dashboard') || Route::currentRouteName() === 'admin.dashboard') ? 'opened active' : ''); ?>">
                <a href="<?php echo e(route('admin.dashboard')); ?>">
                    <i class="entypo-gauge"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="<?php echo e(request()->is('admin/profile*') ? 'opened active' : ''); ?>">
                <a href="<?php echo e(route('admin.profile.show')); ?>">
                    <i class="entypo-user"></i>
                    <span class="title">My Profile</span>
                </a>
            </li>

            <!-- Main Management Sections -->
            <?php $currentRoute = Route::currentRouteName(); ?>

            <li class="has-sub <?php echo e(in_array($currentRoute, [
                'admin.customers.index', 'admin.customers.show', 'admin.customers.edit', 'admin.customers.create',
                'bookings.index', 'bookings.show', 'bookings.edit', 'bookings.create',
                'bookings.confirm', 'bookings.cancel', 'bookings.reschedule']) ? 'opened active' : ''); ?>">
                <a href="#" class="menu-toggle">
                    <i class="entypo-layout"></i>
                    <span class="title">Customer Management</span>
                </a>
                <ul>
                    <li class="<?php echo e(Str::startsWith($currentRoute, 'admin.customers.') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.customers.index')); ?>"><span class="title">Customer Database</span></a>
                    </li>
                    <li class="<?php echo e(Str::startsWith($currentRoute, 'bookings.') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('bookings.index')); ?>">
                            <span class="title">Online Booking/Scheduling</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="has-sub <?php echo e(in_array($currentRoute, [
                'jobs.dispatch', 'jobs.tracking', 'jobs.show', 'jobs.assign',
                'jobs.complete', 'jobs.update-status', 'jobs.reassign', 'jobs.update-tracking',
                'checklists.index', 'checklists.add', 'checklists.edit']) ? 'opened active' : ''); ?>">
                <a href="#" class="menu-toggle">
                    <i class="entypo-briefcase"></i>
                    <span class="title">Job/Service Management</span>
                </a>
                <ul>
                    <li class="<?php echo e(in_array($currentRoute, ['jobs.tracking', 'jobs.show', 'jobs.assign', 'jobs.complete', 'jobs.update-status', 'jobs.reassign']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('jobs.tracking')); ?>"><span class="title">Job Tracking</span></a>
                    </li>
                    <li class="<?php echo e($currentRoute == 'jobs.daily_schedule' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('jobs.daily_schedule')); ?>"><span class="title">Daily Schedule</span></a>
                    </li>
                    <li class="<?php echo e(Str::startsWith($currentRoute, 'checklists.') ? 'active' : ''); ?>" style="display: none;">
                        <a href="<?php echo e(route('checklists.index')); ?>"><span class="title">Checklists</span></a>
                    </li>
                </ul>
            </li>

            <li class="has-sub <?php echo e(in_array($currentRoute, [
                'employees.index', 'employees.show', 'employees.edit', 'employees.create',
                'employees.performance', 'employees.assignments']) ? 'opened active' : ''); ?>">
                <a href="#" class="menu-toggle">
                    <i class="entypo-users"></i>
                    <span class="title">Employee/Cleaner Management</span>
                </a>
                <ul>
                    <li class="<?php echo e(in_array($currentRoute, ['employees.index', 'employees.show', 'employees.edit', 'employees.create']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('employees.index')); ?>"><span class="title">Employee Database</span></a>
                    </li>
                    <li class="<?php echo e($currentRoute == 'employees.performance' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('employees.performance')); ?>"><span class="title">Performance</span></a>
                    </li>
                </ul>
            </li>

            <!-- Website Management Section -->
            <li class="has-sub <?php echo e(in_array($currentRoute, [
                'services.index', 'services.show', 'services.edit', 'services.create',
                'admin.disabled_dates.index',
                'admin.hero_media.index', 'admin.hero_media.show', 'admin.hero_media.edit', 'admin.hero_media.create',
                'admin.contact.edit', 'admin.settings.logo.edit']) ? 'opened active' : ''); ?>">
                <a href="#" class="menu-toggle">
                    <i class="entypo-globe"></i>
                    <span class="title">Website Management</span>
                </a>
                <ul>
                    <li class="<?php echo e(Str::startsWith($currentRoute, 'services.') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('services.index')); ?>"><span class="title">Service Page</span></a>
                    </li>
                    <li class="<?php echo e(Str::startsWith($currentRoute, 'admin.disabled_dates.') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.disabled_dates.index')); ?>"><span class="title">Disabled Dates</span></a>
                    </li>
                    <li class="<?php echo e(request()->is('admin/hero-media*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.hero_media.index')); ?>"><span class="title">Homepage Banner</span></a>
                    </li>
                    <li class="<?php echo e(request()->is('admin/contact*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.contact.edit')); ?>"><span class="title">Contact Information</span></a>
                    </li>
                    <li class="<?php echo e(request()->is('settings/logo') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.settings.logo.edit')); ?>"><span class="title">Company Logo</span></a>
                    </li>
                </ul>
            </li>

            <!-- Reports Section -->
            <li class="has-sub <?php echo e(in_array($currentRoute, [
                'reports.customers.list', 'reports.customers.history',
                'reports.customers.feedback', 'reports.customers.retention',
                'reports.jobs.completion']) ? 'opened active' : ''); ?>">
                <a href="#" class="menu-toggle">
                    <i class="entypo-doc-text"></i>
                    <span class="title">Reports</span>
                </a>
                <ul>
                    <li class="<?php echo e($currentRoute == 'reports.customers.list' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('reports.customers.list')); ?>"><span class="title">Customer List</span></a>
                    </li>
                    <li class="<?php echo e($currentRoute == 'reports.customers.history' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('reports.customers.history')); ?>"><span class="title">Cleaning History</span></a>
                    </li>
                    <li class="<?php echo e($currentRoute == 'reports.customers.feedback' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('reports.customers.feedback')); ?>"><span class="title">Feedbacks/Ratings</span></a>
                    </li>
                    <li class="<?php echo e($currentRoute == 'reports.customers.retention' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('reports.customers.retention')); ?>"><span class="title">Customer Retention</span></a>
                    </li>
                    <li class="<?php echo e($currentRoute == 'reports.jobs.completion' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('reports.jobs.completion')); ?>"><span class="title">Job Completion</span></a>
                    </li>
                </ul>
            </li>

    </div>
</div><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/partials/sidebar.blade.php ENDPATH**/ ?>