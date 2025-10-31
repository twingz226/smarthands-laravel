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
        @include('pages.my_bookings_content', ['user' => $user])
      </div>
    </div>
  </div>
</div>
