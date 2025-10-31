<?php $__env->startSection('content'); ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <!-- Modern Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-1 text-gray-800">Homepage Banner</h1>
          <p class="text-muted mb-0">Manage your homepage banner content</p>
        </div>
        <button type="button" class="btn btn-primary btn-modern" data-toggle="modal" data-target="#changeBannerModal">
          <i class="entypo-upload me-2"></i>Upload New Banner
        </button>
      </div>

<!-- Add Service Media Modal -->
<div class="modal fade" id="addServiceMediaModal" tabindex="-1" role="dialog" aria-labelledby="addServiceMediaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header text-white" style="background-color: #274c77;">
        <h5 class="modal-title text-white" id="addServiceMediaModalLabel" style="color:#fff !important; font-size:1.5rem; font-weight:700;">
          <i class="entypo-upload me-2 text-white"></i>Add New Service
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo e(route('admin.media.store')); ?>" method="POST" enctype="multipart/form-data" id="addServiceMediaForm">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <input type="hidden" name="section" value="services">
              <input type="hidden" name="return_to" value="<?php echo e(route('admin.hero_media.index')); ?>">
              <div class="form-group mb-3">
                <label for="svc_service_id" class="form-label">Service</label>
                <select class="form-control" id="svc_service_id" name="service_id" required>
                  <option value="">Select a service…</option>
                  <?php $__currentLoopData = $serviceOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($svc->id); ?>"
                            data-type="<?php echo e($svc->pricing_type); ?>"
                            data-price="<?php echo e($svc->price); ?>"
                            data-name="<?php echo e($svc->name); ?>"
                            data-description="<?php echo e(e($svc->description)); ?>">
                      <?php echo e($svc->name); ?> (ID: <?php echo e($svc->id); ?>)
                    </option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <small class="text-muted">Pick from your Services catalog. We'll use its ID for booking and auto-fill type/price.</small>
              </div>
              <div class="form-group mb-3">
                <label for="svc_title" class="form-label">Title</label>
                <input type="text" class="form-control" id="svc_title" name="title" required>
              </div>
              <div class="form-group mb-3">
                <label for="svc_description" class="form-label">Description</label>
                <textarea class="form-control" id="svc_description" name="description" rows="3"></textarea>
              </div>
              <div class="form-group mb-3" id="svcServiceFields">
                <label for="svc_price" class="form-label">Price</label>
                <input type="text" class="form-control" id="svc_price" name="price" placeholder="e.g., ₱299/hr or ₱75/sqm">
                <small class="text-muted">Suggested format: ₱299/hr, ₱75/sqm, or a fixed amount (e.g., ₱1500)</small>
                <label for="svc_service_type" class="form-label mt-2">Service Type</label>
                <select class="form-control" id="svc_service_type" name="service_type" required>
                  <option value="">Select Type</option>
                  <option value="hourly">Hourly</option>
                  <option value="sqm">Per Square Meter</option>
                  <option value="fixed">Fixed Price</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="svc_media_type" class="form-label">Media Type</label>
                <select class="form-control" id="svc_media_type" name="media_type" required>
                  <option value="image" selected>Image</option>
                  <option value="video">Video</option>
                </select>
                <small class="text-muted">For Services, Image is recommended</small>
              </div>
              <div class="form-group mb-3">
                <label for="svc_media_file" class="form-label">Upload File</label>
                <div class="file-upload-wrapper">
                  <input type="file" class="form-control" id="svc_media_file" name="media_file" accept="image/*" required>
                </div>
                <small class="form-text text-muted" id="svcFileHelp">Supported images: JPG, PNG, WEBP. Recommended ~1200×800. Max size: 10MB</small>
              </div>
              <div class="form-group mb-3">
                <label for="svc_display_order" class="form-label">Display Order</label>
                <input type="number" class="form-control" id="svc_display_order" name="display_order" value="<?php echo e((($servicesMedia->max('display_order') ?? 0) + 1)); ?>" min="1">
              </div>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="svc_is_active" name="is_active" value="1" checked>
                <label class="form-check-label" for="svc_is_active">Active (visible on website)</label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="entypo-upload me-2"></i>Upload Media
          </button>
        </div>
      </form>
    </div>
  </div>
  <script>
    // Initialize defaults and toggle fields for the Add Service Media modal
    document.addEventListener('DOMContentLoaded', function() {
      // Section field removed; defaulting to services via hidden input
      const mediaType = document.getElementById('svc_media_type');
      const fileInput = document.getElementById('svc_media_file');
      const fileHelp = document.getElementById('svcFileHelp');
      const serviceFields = document.getElementById('svcServiceFields');

      function applySectionRules() {
        // Always enforce Services section behavior
        serviceFields.style.display = 'block';
        mediaType.value = 'image';
        mediaType.querySelector('option[value="video"]').style.display = 'none';
        fileInput.setAttribute('accept', 'image/*');
        fileHelp.textContent = 'Supported images: JPG, PNG, WEBP. Max size: 10MB';
        document.getElementById('svc_service_type').setAttribute('required', 'required');
        document.getElementById('svc_service_id').setAttribute('required', 'required');
      }

      function applyMediaTypeRules() {
        if (mediaType.value === 'video') {
          fileInput.setAttribute('accept', 'video/*');
          fileHelp.textContent = 'Supported videos: MP4, WebM, Ogg. Max size: 10MB';
        } else {
          fileInput.setAttribute('accept', 'image/*');
          fileHelp.textContent = 'Supported images: JPG, PNG, WEBP. Max size: 10MB';
        }
      }

      // Auto-fill type and price from service selection
      const svcSelect = document.getElementById('svc_service_id');
      const svcType = document.getElementById('svc_service_type');
      const svcPrice = document.getElementById('svc_price');
      const svcTitle = document.getElementById('svc_title');
      const svcDesc = document.getElementById('svc_description');
      if (svcSelect) {
        svcSelect.addEventListener('change', function() {
          const opt = this.options[this.selectedIndex];
          if (!opt) return;
          const type = opt.getAttribute('data-type');
          const price = opt.getAttribute('data-price');
          const name = opt.getAttribute('data-name');
          const desc = opt.getAttribute('data-description');
          if (type) { svcType.value = type; }
          if (price) { svcPrice.value = price; }
          if (name) { svcTitle.value = name; }
          if (typeof desc === 'string') { svcDesc.value = desc; }
        });
      }

      // No section change listener; section is fixed to services
      mediaType.addEventListener('change', function() {
        applyMediaTypeRules();
      });

      // When modal opens, enforce default to Services and Image and required fields
      $('#addServiceMediaModal').on('show.bs.modal', function () {
        mediaType.value = 'image';
        applySectionRules();
        applyMediaTypeRules();
      });
    });
  </script>
</div>

      <!-- Alert Messages -->
      <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="entypo-check me-2"></i><?php echo e(session('success')); ?>

          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>
      <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="entypo-cancel me-2"></i><?php echo e(session('error')); ?>

          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <!-- Modern Card Layout -->
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 py-3">
          <h5 class="card-title mb-0 text-primary">
            <i class="entypo-image me-2"></i>Banner Management
          </h5>
        </div>
        <div class="card-body p-0">
          <?php if($items->isEmpty()): ?>
            <!-- Empty State -->
            <div class="text-center py-5">
              <div class="mb-4">
                <i class="entypo-image" style="font-size: 4rem; color: #e9ecef;"></i>
              </div>
              <h5 class="text-muted mb-3">No Banner Content Yet</h5>
              <p class="text-muted mb-4">Upload your first banner to get started. You can use videos or images.</p>
              <button type="button" class="btn btn-primary btn-modern" data-toggle="modal" data-target="#changeBannerModal">
                <i class="entypo-upload me-2"></i>Upload Your First Banner
              </button>
            </div>
          <?php else: ?>
            <!-- Banner Grid Layout -->
            <form method="POST" action="<?php echo e(route('admin.hero_media.reorder')); ?>" class="p-4">
              <?php echo csrf_field(); ?>
              <div class="row g-4">
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="col-lg-6 col-xl-4">
                    <div class="banner-item-card <?php echo e($item->is_active ? 'active' : 'inactive'); ?>">
                      <!-- Preview -->
                      <div class="banner-preview">
                        <?php if($item->media_type === \App\Models\HomeMedia::TYPE_VIDEO): ?>
                          <video src="<?php echo e($item->media_url); ?>" <?php if($item->poster_url): ?> poster="<?php echo e($item->poster_url); ?>" <?php endif; ?> controls class="preview-media"></video>
                          <div class="media-type-badge video">
                            <i class="entypo-video"></i> Video
                          </div>
                        <?php else: ?>
                          <img src="<?php echo e($item->media_url); ?>" alt="Preview" class="preview-media">
                          <div class="media-type-badge image">
                            <i class="entypo-image"></i> Image
                          </div>
                        <?php endif; ?>
                        
                        <!-- Status Badge -->
                        <div class="status-badge <?php echo e($item->is_active ? 'active' : 'inactive'); ?>">
                          <?php if($item->is_active): ?>
                            <i class="entypo-check"></i> Active
                          <?php else: ?>
                            <i class="entypo-minus"></i> Inactive
                          <?php endif; ?>
                        </div>
                      </div>
                      
                      <!-- Content -->
                      <div class="banner-content">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                          <h6 class="banner-title mb-0"><?php echo e($item->title ?: 'Untitled Banner'); ?></h6>
                          <div class="order-input">
                            <label class="form-label text-muted small mb-1">Order</label>
                            <input type="number" class="form-control form-control-sm" name="orders[<?php echo e($loop->index); ?>][display_order]" value="<?php echo e($item->display_order); ?>" min="0">
                            <input type="hidden" name="orders[<?php echo e($loop->index); ?>][id]" value="<?php echo e($item->id); ?>">
                          </div>
                        </div>
                        
                        <p class="text-muted small mb-3">Updated <?php echo e($item->updated_at->diffForHumans()); ?></p>
                        
                        <!-- Actions -->
                        <div class="banner-actions">
                          <button type="button" class="btn btn-primary btn-modern" data-toggle="modal" data-target="#changeBannerModal">
                            <i class="entypo-pencil"></i> Edit
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
              
              <!-- Save Button -->
              <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                  <p class="text-muted small mb-0">
                    <i class="entypo-info"></i> The homepage displays the first active banner (lowest order number)
                  </p>
                  <button type="submit" class="btn btn-primary btn-modern">
                    <i class="entypo-shuffle me-2"></i>Save Order
                  </button>
                </div>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>

      <!-- Service Images Management (Integrated) -->
      <div class="card shadow-sm border-0 mt-5">
        <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0 text-primary">
            <i class="entypo-grid me-2"></i>Service Image Management
          </h5>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-lg btn-modern me-2" data-toggle="modal" data-target="#addServiceMediaModal">
              <i class="entypo-upload me-2"></i>Add New Service
            </button>
          </div>
        </div>
        <div class="card-body p-0">
          <?php if($servicesMedia->isEmpty()): ?>
            <div class="text-center py-5">
              <div class="mb-4">
                <i class="entypo-grid" style="font-size: 4rem; color: #e9ecef;"></i>
              </div>
              <h5 class="text-muted mb-3">No Service Images Yet</h5>
              <p class="text-muted mb-4">Upload service images to showcase your offerings</p>
            </div>
          <?php else: ?>
            <div class="row g-4 p-4" id="servicesMediaContainer">
              <?php $__currentLoopData = $servicesMedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4" data-id="<?php echo e($item->id); ?>" data-section="services">
                  <div class="media-card sortable-item">
                    <div class="media-preview">
                      <img src="<?php echo e($item->media_url); ?>" alt="<?php echo e($item->title); ?>" class="media-thumbnail">
                      <div class="media-type-badge service">
                        <i class="entypo-briefcase"></i> Service
                      </div>
                    </div>
                    <div class="media-info">
                      <h6 class="media-title"><?php echo e($item->title ?? 'Untitled'); ?></h6>
                      <p class="media-description"><?php echo e(Str::limit($item->description ?? '', 60)); ?></p>
                      <?php if($item->price): ?>
                        <p class="service-price"><strong><?php echo e($item->price); ?></strong></p>
                      <?php endif; ?>
                      <div class="media-meta">
                        <span class="badge <?php echo e($item->is_active ? 'badge-success' : 'badge-secondary'); ?>">
                          <?php echo e($item->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                        <small class="text-muted">Order: <?php echo e($item->display_order); ?></small>
                      </div>
                    </div>
                    <div class="media-actions d-flex flex-column">
                      <button class="btn btn-primary btn-lg btn-modern rounded-pill w-100 mb-2" onclick="editMedia(<?php echo e($item->id); ?>)">
                        <i class="entypo-pencil" style="font-size:1.25rem;"></i> Edit
                      </button>
                      <button class="btn btn-danger btn-lg btn-modern rounded-pill w-100" onclick="openDeleteModal(<?php echo e($item->id); ?>)">
                        <i class="entypo-trash" style="font-size:1.25rem;"></i> Delete
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modern Upload Modal -->
<div class="modal fade" id="changeBannerModal" tabindex="-1" role="dialog" aria-labelledby="changeBannerModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header text-white border-0" style="background-color: #274c77;">
        <h4 class="modal-title text-white" id="changeBannerModalLabel" style="color:#fff !important; font-size:1.5rem; font-weight:700;">
          <i class="entypo-upload me-2 text-white"></i>Upload Banner Content
        </h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body p-4">
        <div class="upload-intro mb-4">
          <p class="text-muted mb-0">Upload a high-quality video or image for your homepage banner. This content will be the first thing visitors see.</p>
        </div>
        
        <form id="uploadBannerForm" method="POST" action="<?php echo e(route('admin.hero_media.upload')); ?>" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          
          <!-- File Upload Section -->
          <div class="upload-section mb-4">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="bannerType" class="form-label fw-bold">
                    <i class="entypo-folder-video me-1"></i>Content Type
                  </label>
                  <select class="form-control form-control-lg" id="bannerType" name="media_type" required>
                    <option value="video" selected>📹 Video Content</option>
                    <option value="image">🖼️ Image Content</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="bannerActive" class="form-label fw-bold">
                    <i class="entypo-eye me-1"></i>Visibility
                  </label>
                  <div class="custom-control custom-switch mt-2">
                    <input type="checkbox" class="custom-control-input" id="bannerActive" name="is_active" value="1" checked>
                    <label class="custom-control-label" for="bannerActive">Make this banner active</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- File Input -->
          <div class="form-group mb-4">
            <label for="bannerMedia" class="form-label fw-bold">
              <i class="entypo-upload me-1"></i>Select File
            </label>
            <div class="file-upload-wrapper">
              <input type="file" class="form-control form-control-lg" id="bannerMedia" name="media" accept="video/*,image/*" required>
              <div class="file-upload-help mt-2">
                <div class="row">
                  <div class="col-md-6">
                    <small class="text-muted">
                      <strong>Videos:</strong> MP4, WebM, Ogg<br>
                      <strong>Images:</strong> JPG, PNG, WEBP
                    </small>
                  </div>
                  <div class="col-md-6">
                    <small class="text-muted">
                      <strong>Recommended:</strong> 1920x1080px<br>
                      <strong>Max size:</strong> 50MB
                    </small>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Title Input -->
          <div class="form-group mb-4">
            <label for="bannerTitle" class="form-label fw-bold">
              <i class="entypo-text me-1"></i>Banner Title
            </label>
            <input type="text" class="form-control form-control-lg" id="bannerTitle" name="title" placeholder="e.g., Welcome to Smarthands Cleaning">
            <small class="text-muted">Optional: Add a descriptive title for this banner</small>
          </div>
        </form>
      </div>
      
      <div class="modal-footer bg-light border-0 p-4">
        <button type="button" class="btn btn-secondary btn-modern me-2" data-dismiss="modal">
          <i class="entypo-cancel me-1"></i>Cancel
        </button>
        <button type="submit" class="btn btn-primary btn-modern" form="uploadBannerForm">
          <i class="entypo-upload me-1"></i>Upload Banner
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Delete this media item? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/admin/hero_media.js')); ?>"></script>
<script>
// Edit existing service media using admin.media endpoints
function editMedia(id) {
  fetch(`/admin/media/${id}/edit`)
    .then(response => response.json())
    .then(data => {
      // Populate form fields
      document.getElementById('edit_section').value = data.section;
      document.getElementById('edit_title').value = data.title || '';
      document.getElementById('edit_description').value = data.description || '';
      document.getElementById('edit_price').value = data.price || '';
      document.getElementById('edit_service_type').value = data.service_type || '';
      document.getElementById('edit_service_id').value = data.service_id || '';
      document.getElementById('edit_media_type').value = data.media_type;
      document.getElementById('edit_display_order').value = data.display_order;
      document.getElementById('edit_is_active').checked = !!data.is_active;

      // Form action
      document.getElementById('editMediaForm').action = `/admin/media/${id}`;

      // Toggle service-only fields
      const serviceFields = document.getElementById('editServiceFields');
      serviceFields.style.display = (data.section === 'services') ? 'block' : 'none';

      // Preview
      const container = document.getElementById('currentMediaContainer');
      if (data.media_type === 'video') {
        container.innerHTML = `<video controls style="max-width: 200px; max-height: 150px;"><source src="${data.media_url}" type="video/mp4"></video>`;
      } else {
        container.innerHTML = `<img src="${data.media_url}" alt="${data.title}" style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 8px;">`;
      }

      // Set selected service in dropdown (if present)
      const editSvcSelect = document.getElementById('edit_service_id');
      if (editSvcSelect && typeof data.service_id !== 'undefined' && data.service_id !== null) {
        editSvcSelect.value = String(data.service_id);
      }

      // Show modal
      $('#editMediaModal').modal('show');
    })
    .catch(() => alert('Failed to load media data'));
}

// Delete service media via API (confirmation handled by modal)
function deleteMedia(id) {
  fetch(`/admin/media/${id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Content-Type': 'application/json',
    }
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      location.reload();
    } else {
      alert('Failed to delete media: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(() => alert('Failed to delete media'));
}

// Delete confirmation modal helpers
let deleteTargetId = null;
function openDeleteModal(id) {
  deleteTargetId = id;
  $('#confirmDeleteModal').modal('show');
}
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('confirmDeleteBtn');
  if (btn) {
    btn.addEventListener('click', function() {
      if (deleteTargetId) {
        deleteMedia(deleteTargetId);
        $('#confirmDeleteModal').modal('hide');
        deleteTargetId = null;
      }
    });
  }
});

// Enable drag-and-drop reorder for services
$(document).ready(function() {
  $('#servicesMediaContainer').sortable({
    items: '.col-md-6',
    handle: '.sortable-item',
    cursor: 'move',
    opacity: 0.8,
    placeholder: 'sortable-placeholder',
    update: function() { updateDisplayOrder(); }
  });
});

function updateDisplayOrder() {
  const items = [];
  $('#servicesMediaContainer .col-md-6').each(function(index) {
    const id = $(this).data('id');
    if (id) items.push({ id: id, display_order: index + 1 });
  });
  if (items.length === 0) return;
  fetch('/admin/media/reorder', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ items })
  })
  .then(r => r.json())
  .then(data => { if (!data.success) console.error('Failed to update order:', data.message); })
  .catch(err => console.error('Error updating order:', err));
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<!-- Edit Media Modal (reused for Service Images) -->
<div class="modal fade" id="editMediaModal" tabindex="-1" role="dialog" aria-labelledby="editMediaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header text-white" style="background-color: #274c77;">
        <h5 class="modal-title text-white" id="editMediaModalLabel" style="color:#fff !important; font-size:1.5rem; font-weight:700;">
          <i class="entypo-pencil me-2 text-white" style="color:#fff !important;"></i>Edit Media
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editMediaForm" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="edit_section" class="form-label">Section</label>
                <select class="form-control" id="edit_section" name="section" required>
                  <option value="">Select Section</option>
                  <option value="hero">Hero Banner</option>
                  <option value="services">Services</option>
                </select>
              </div>
              <div class="form-group mb-3">
                <label for="edit_title" class="form-label">Title</label>
                <input type="text" class="form-control" id="edit_title" name="title" required>
              </div>
              <div class="form-group mb-3">
                <label for="edit_description" class="form-label">Description</label>
                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
              </div>
              <div class="form-group mb-3" id="editServiceFields" style="display: none;">
                <label for="edit_price" class="form-label">Price</label>
                <input type="text" class="form-control" id="edit_price" name="price" placeholder="e.g., ₱299/hr">
                <label for="edit_service_type" class="form-label mt-2">Service Type</label>
                <select class="form-control" id="edit_service_type" name="service_type">
                  <option value="">Select Type</option>
                  <option value="hourly">Hourly</option>
                  <option value="sqm">Per Square Meter</option>
                  <option value="fixed">Fixed Price</option>
                </select>
                <label for="edit_service_id" class="form-label mt-2">Service</label>
                <select class="form-control" id="edit_service_id" name="service_id">
                  <option value="">Select a service…</option>
                  <?php $__currentLoopData = $allServiceOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($svc->id); ?>" data-type="<?php echo e($svc->pricing_type); ?>" data-price="<?php echo e($svc->price); ?>"><?php echo e($svc->name); ?> (ID: <?php echo e($svc->id); ?>)</option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="edit_media_type" class="form-label">Media Type</label>
                <select class="form-control" id="edit_media_type" name="media_type" required>
                  <option value="image">Image</option>
                  <option value="video">Video</option>
                </select>
              </div>
              <div class="form-group mb-3">
                <label for="edit_media_file" class="form-label">Upload New File (Optional)</label>
                <div class="file-upload-wrapper">
                  <input type="file" class="form-control" id="edit_media_file" name="media_file" accept="image/*,video/*">
                  <small class="form-text text-muted" id="editFileHelp">
                    Leave empty to keep current file. Supported: JPG, PNG, WEBP, MP4, WebM. Max size: 10MB
                  </small>
                </div>
              </div>
              <div class="form-group mb-3">
                <label for="edit_display_order" class="form-label">Display Order</label>
                <input type="number" class="form-control" id="edit_display_order" name="display_order" value="1" min="1">
              </div>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active" value="1">
                <label class="form-check-label" for="edit_is_active">Active (visible on website)</label>
              </div>
              <div class="mt-3" id="currentMediaPreview">
                <label class="form-label">Current Media:</label>
                <div id="currentMediaContainer"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="entypo-pencil me-2"></i>Update Media
          </button>
        </div>
      </form>
    </div>
  </div>
  <script>
    // Toggle service fields and file help inside modal
    document.addEventListener('change', function(e) {
      if (e.target && e.target.id === 'edit_section') {
        const serviceFields = document.getElementById('editServiceFields');
        const mediaType = document.getElementById('edit_media_type');
        const serviceTypeEl = document.getElementById('edit_service_type');
        const serviceIdEl = document.getElementById('edit_service_id');
        if (e.target.value === 'services') {
          serviceFields.style.display = 'block';
          mediaType.value = 'image';
          mediaType.querySelector('option[value="video"]').style.display = 'none';
          // Require fields for services
          serviceTypeEl && serviceTypeEl.setAttribute('required', 'required');
          serviceIdEl && serviceIdEl.setAttribute('required', 'required');
        } else {
          serviceFields.style.display = 'none';
          mediaType.querySelector('option[value="video"]').style.display = 'block';
          // Remove requirement if not services
          serviceTypeEl && serviceTypeEl.removeAttribute('required');
          serviceIdEl && serviceIdEl.removeAttribute('required');
        }
      }
      if (e.target && e.target.id === 'edit_media_type') {
        const fileHelp = document.getElementById('editFileHelp');
        const fileInput = document.getElementById('edit_media_file');
        if (e.target.value === 'video') {
          fileHelp.textContent = 'Leave empty to keep current file. Supported videos: MP4, WebM, Ogg.';
          fileInput.setAttribute('accept', 'video/*');
        } else {
          fileHelp.textContent = 'Leave empty to keep current file. Supported images: JPG, PNG, WEBP.';
          fileInput.setAttribute('accept', 'image/*');
        }
      }
      if (e.target && e.target.id === 'edit_service_id') {
        const opt = e.target.options[e.target.selectedIndex];
        if (opt) {
          const type = opt.getAttribute('data-type');
          const price = opt.getAttribute('data-price');
          if (type) { document.getElementById('edit_service_type').value = type; }
          if (price && !document.getElementById('edit_price').value) {
            document.getElementById('edit_price').value = price;
          }
        }
      }
    });
  </script>
</div>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/hero_media/index.blade.php ENDPATH**/ ?>