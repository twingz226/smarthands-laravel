<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="House Cleaning Service Management System Panel" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="icon" href="{{ asset('images/Smarthands.png') }}">
    <title>Smarthands Cleaning Services Management System | Dashboard</title>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-icons/entypo/css/entypo.css') }}">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/neon-core.css') }}">
    <link rel="stylesheet" href="{{ asset('css/neon-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/neon-forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('js/jvectormap/jquery-jvectormap-1.2.2.css') }}">
    <link rel="stylesheet" href="{{ asset('js/rickshaw/rickshaw.min.css') }}">
    
    <!-- Notification Styles -->
    <style>
        .dropdown-menu-list {
            padding: 0;
            list-style: none;
            margin: 0;
        }
        
        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .notification-item:hover {
            background-color: #f5f5f5;
        }
        
        .notification-item.unread {
            background-color: #f8f9fa;
        }
        
        .notification-item .notification-icon {
            float: left;
            margin-right: 10px;
            font-size: 18px;
            color: #6c757d;
        }
        
        .notification-item .notification-content {
            margin-left: 28px;
        }
        
        .notification-item .notification-message {
            display: block;
            color: #333;
        }
        
        .notification-item .notification-time {
            display: block;
            font-size: 11px;
            color: #999;
            margin-top: 2px;
        }
        
        .notification-item.unread .notification-message {
            font-weight: 600;
        }
        
        .notification-item.unread .notification-time {
            color: #007bff;
        }
        
        #notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 18px;
            height: 18px;
            line-height: 18px;
            font-size: 10px;
            padding: 0 5px;
        }
        
        .dropdown-menu > li > a {
            padding: 10px 15px;
        }
        
        .dropdown-menu > li.external > a {
            text-align: center;
            font-weight: 600;
            color: #007bff;
        }

        /* Scoped styles for the bell notifications dropdown to keep content consistent
           and force text color to black as requested */
        .notifications-dropdown {
            min-width: 320px;
        }
        .notifications-dropdown,
        .notifications-dropdown * {
            color: #000 !important;
        }
        /* Increase specificity for the bell container in case other rules win */
        .notifications.dropdown .dropdown-menu,
        .notifications.dropdown .dropdown-menu * {
            color: #000 !important;
        }
        .notifications-dropdown a:hover {
            color: #000 !important;
        }
        /* Ensure links and muted/alert text are also black */
        .notifications-dropdown a,
        .notifications-dropdown .text-muted,
        .notifications-dropdown .alert {
            color: #000 !important;
        }
        .notifications-dropdown .top p.small {
            margin: 10px 15px;
        }
        .notifications-dropdown .dropdown-menu-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .notifications-dropdown .notification-item {
            display: block;
            padding: 10px 12px;
        }
        .notifications-dropdown .notification-item a {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .notifications-dropdown .notification-icon {
            width: 34px;
            height: 34px;
            line-height: 34px;
            text-align: center;
            border-radius: 50%;
            background: #f1f5f9;
            margin-right: 10px;
            color: #000 !important; /* enforce black for icon wrapper */
        }
        .notifications-dropdown .notification-icon i {
            color: #000 !important; /* enforce black for icon itself */
        }
        .notifications-dropdown .notification-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .notifications-dropdown .notification-message {
            font-size: 13px;
            font-weight: 500;
        }
        .notifications-dropdown .notification-time {
            font-size: 11px;
        }
        .notifications-dropdown li.external a {
            color: #000 !important;
            font-weight: 600;
        }
    
/* Custom admin header icon alignment */
.user-info > li > a.dropdown-toggle.d-flex.align-items-center {
  padding-top: 8px !important;
  padding-bottom: 8px !important;
}
.user-info > li > a .fs-4 {
  vertical-align: middle !important;
  display: inline-block;
  line-height: 1;
}
.user-info > li > a .badge-info {
  vertical-align: top !important;
  margin-left: 2px;
  margin-top: -4px;
  position: relative;
  top: 0;
}

.user-info > li {
  display: flex !important;
  align-items: center !important;
}
.user-info > li > a .fs-4 {
  vertical-align: baseline !important;
  display: inline-block;
  line-height: 1;
}
.user-info > li > a .badge-info {
  vertical-align: top !important;
  margin-left: 2px;
  margin-top: 0 !important;
  position: relative;
  top: 0;
}
</style>

    <!-- Core Scripts (load jQuery first; app.js moved to bottom) -->
    <script src="{{ asset('js/jquery-1.11.3.min.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    
    <script>
        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Make CSRF token available to JavaScript
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    @stack('styles')
</head>
<body class="page-body page-left-in" data-url="http://neon.dev">

<div class="page-container">
    @include('admin.partials.sidebar')

    <div class="main-content">
        @include('admin.partials.topbar')

        <hr />

        @yield('content')
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/gsap/TweenMax.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/joinable.js') }}"></script>
<script src="{{ asset('js/resizeable.js') }}"></script>
<script src="{{ asset('js/neon-api.js') }}"></script>
<script src="{{ asset('js/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('js/jvectormap/jquery-jvectormap-europe-merc-en.js') }}"></script>
<script src="{{ asset('js/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('js/rickshaw/vendor/d3.v3.js') }}"></script>
<script src="{{ asset('js/rickshaw/rickshaw.min.js') }}"></script>
<script src="{{ asset('js/neon-chat.js') }}"></script>
<script src="{{ asset('js/neon-custom.js') }}"></script>
<script src="{{ asset('js/neon-demo.js') }}"></script>
<!-- Load compiled app scripts after jQuery and Bootstrap to avoid plugin conflicts -->
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ asset('js/admin-messages.js') }}"></script>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteLabel">
          <i class="entypo-trash"></i> Confirm Delete
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this item? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="entypo-cancel"></i> Cancel
        </button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
          <i class="entypo-trash"></i> Delete
        </button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    var deleteForm = null;
    
    // Handle delete buttons with data-delete-form attribute
    $('[data-delete-form]').on('click', function(e) {
        e.preventDefault();
        var formId = $(this).data('delete-form');
        deleteForm = $('#' + formId);
        $('#confirmDeleteModal').modal('show');
    });
    
    // Handle form submission when delete is confirmed
    $('#confirmDeleteBtn').on('click', function() {
        if (deleteForm) {
            deleteForm.submit();
        }
    });
    
    // Reset form reference when modal is closed
    $('#confirmDeleteModal').on('hidden.bs.modal', function() {
        deleteForm = null;
    });
});
</script>

@stack('scripts')

</body>
</html> 