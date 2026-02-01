<!-- My Bookings Modal -->
<div class="modal fade" id="myBookingsModal" tabindex="-1" aria-labelledby="myBookingsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myBookingsModalLabel">
          <i class="bi bi-journal-check me-2"></i>My Bookings
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php echo $__env->make('pages.my_bookings_content', ['user' => $user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      </div>
    </div>
  </div>
</div>
<?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/partials/my_bookings_modal.blade.php ENDPATH**/ ?>