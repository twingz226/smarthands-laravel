@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <!-- Modern Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-1 text-gray-800">Media Management</h1>
          <p class="text-muted mb-0">Manage homepage banners and service images</p>
        </div>
        <button type="button" class="btn btn-primary btn-modern" data-toggle="modal" data-target="#addMediaModal">
          <i class="entypo-upload me-2"></i>Add New Media
        </button>
      </div>

      <!-- Alert Messages -->
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="entypo-check me-2"></i>{{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="entypo-cancel me-2"></i>{{ session('error') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <!-- Tab Navigation -->
      <ul class="nav nav-tabs mb-4" id="mediaTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero" type="button" role="tab">
            <i class="entypo-image me-2"></i>Hero Banners
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">
            <i class="entypo-grid me-2"></i>Service Images
          </button>
        </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content" id="mediaTabContent">
        <!-- Hero Banners Tab -->
        <div class="tab-pane fade show active" id="hero" role="tabpanel">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 py-3">
              <h5 class="card-title mb-0 text-primary">
                <i class="entypo-image me-2"></i>Hero Banner Management
              </h5>
            </div>
            <div class="card-body p-0">
              @if($heroMedia->isEmpty())
                <div class="text-center py-5">
                  <div class="mb-4">
                    <i class="entypo-image" style="font-size: 4rem; color: #e9ecef;"></i>
                  </div>
                  <h5 class="text-muted mb-3">No Hero Banner Yet</h5>
                  <p class="text-muted mb-4">Upload your first hero banner to get started</p>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMediaModal" data-section="hero">
                    <i class="entypo-upload me-2"></i>Upload Hero Banner
                  </button>
                </div>
              @else
                <div class="row g-4 p-4" id="heroMediaContainer">
                  @foreach($heroMedia as $item)
                    <div class="col-md-6 col-lg-4" data-id="{{ $item->id }}" data-section="hero">
                      <div class="media-card sortable-item">
                        <div class="media-preview">
                          @if($item->media_type === 'video')
                            <video class="media-thumbnail" controls>
                              <source src="{{ $item->media_url }}" type="video/mp4">
                            </video>
                            <div class="media-type-badge video">
                              <i class="entypo-video"></i> Video
                            </div>
                          @else
                            <img src="{{ $item->media_url }}" alt="{{ $item->title }}" class="media-thumbnail">
                            <div class="media-type-badge image">
                              <i class="entypo-image"></i> Image
                            </div>
                          @endif
                        </div>
                        <div class="media-info">
                          <h6 class="media-title">{{ $item->title ?? 'Untitled' }}</h6>
                          <p class="media-description">{{ Str::limit($item->description ?? '', 60) }}</p>
                          <div class="media-meta">
                            <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-secondary' }}">
                              {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <small class="text-muted">{{ $item->updated_at->diffForHumans() }}</small>
                          </div>
                        </div>
                        <div class="media-actions">
                          <button class="btn btn-sm btn-outline-primary" onclick="editMedia({{ $item->id }})">
                            <i class="entypo-pencil"></i>
                          </button>
                          <button class="btn btn-sm btn-outline-danger" onclick="openDeleteModal({{ $item->id }})">
                            <i class="entypo-trash"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        </div>

        <!-- Service Images Tab -->
        <div class="tab-pane fade" id="services" role="tabpanel">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 py-3">
              <h5 class="card-title mb-0 text-primary">
                <i class="entypo-grid me-2"></i>Service Image Management
              </h5>
            </div>
            <div class="card-body p-0">
              @if($servicesMedia->isEmpty())
                <div class="text-center py-5">
                  <div class="mb-4">
                    <i class="entypo-grid" style="font-size: 4rem; color: #e9ecef;"></i>
                  </div>
                  <h5 class="text-muted mb-3">No Service Images Yet</h5>
                  <p class="text-muted mb-4">Upload service images to showcase your offerings</p>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMediaModal" data-section="services">
                    <i class="entypo-upload me-2"></i>Add New Service
                  </button>
                </div>
              @else
                <div class="row g-4 p-4" id="servicesMediaContainer">
                  @foreach($servicesMedia as $item)
                    <div class="col-md-6 col-lg-4" data-id="{{ $item->id }}" data-section="services">
                      <div class="media-card sortable-item">
                        <div class="media-preview">
                          <img src="{{ $item->media_url }}" alt="{{ $item->title }}" class="media-thumbnail">
                          <div class="media-type-badge service">
                            <i class="entypo-briefcase"></i> Service
                          </div>
                        </div>
                        <div class="media-info">
                          <h6 class="media-title">{{ $item->title ?? 'Untitled' }}</h6>
                          <p class="media-description">{{ Str::limit($item->description ?? '', 60) }}</p>
                          @if($item->price)
                            <p class="service-price"><strong>{{ $item->price }}</strong></p>
                          @endif
                          <div class="media-meta">
                            <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-secondary' }}">
                              {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <small class="text-muted">Order: {{ $item->display_order }}</small>
                          </div>
                        </div>
                        <div class="media-actions">
                          <button class="btn btn-sm btn-outline-primary" onclick="editMedia({{ $item->id }})">
                            <i class="entypo-pencil"></i>
                          </button>
                          <button class="btn btn-sm btn-outline-danger" onclick="openDeleteModal({{ $item->id }})">
                            <i class="entypo-trash"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Media Modal -->
<div class="modal fade" id="editMediaModal" tabindex="-1" role="dialog" aria-labelledby="editMediaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="editMediaModalLabel">
          <i class="entypo-pencil me-2"></i>Edit Media
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editMediaForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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

                <label for="edit_service_id" class="form-label mt-2">Service ID</label>
                <input type="number" class="form-control" id="edit_service_id" name="service_id" placeholder="Service ID for booking">
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
                  <div class="file-upload-text">
                    <i class="entypo-upload"></i>
                    <span>Click to select new file or leave empty to keep current</span>
                  </div>
                </div>
                <small class="form-text text-muted" id="editFileHelp">
                  Leave empty to keep current file. Supported: JPG, PNG, WEBP, MP4, WebM. Max size: 10MB
                </small>
              </div>

              <div class="form-group mb-3">
                <label for="edit_display_order" class="form-label">Display Order</label>
                <input type="number" class="form-control" id="edit_display_order" name="display_order" value="1" min="1">
              </div>

              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active" value="1">
                <label class="form-check-label" for="edit_is_active">
                  Active (visible on website)
                </label>
              </div>

              <!-- Current Media Preview -->
              <div class="mt-3" id="currentMediaPreview">
                <label class="form-label">Current Media:</label>
                <div id="currentMediaContainer"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">
            <i class="entypo-pencil me-2"></i>Update Media
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Media Modal -->
<div class="modal fade" id="addMediaModal" tabindex="-1" role="dialog" aria-labelledby="addMediaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addMediaModalLabel">
          <i class="entypo-upload me-2"></i>Add New Media
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="section" class="form-label">Section</label>
                <select class="form-control" id="section" name="section" required>
                  <option value="">Select Section</option>
                  <option value="hero">Hero Banner</option>
                  <option value="services">Services</option>
                </select>
              </div>

              <div class="form-group mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
              </div>

              <div class="form-group mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
              </div>

              <div class="form-group mb-3" id="serviceFields" style="display: none;">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" placeholder="e.g., ₱299/hr">
                
                <label for="service_type" class="form-label mt-2">Service Type</label>
                <select class="form-control" id="service_type" name="service_type">
                  <option value="">Select Type</option>
                  <option value="hourly">Hourly</option>
                  <option value="sqm">Per Square Meter</option>
                  <option value="fixed">Fixed Price</option>
                </select>

                <label for="service_id" class="form-label mt-2">Service</label>
                <select class="form-control" id="service_id" name="service_id">
                  <option value="">Select a service…</option>
                  @isset($serviceOptions)
                    @foreach($serviceOptions as $svc)
                      <option value="{{ $svc->id }}"
                              data-type="{{ $svc->pricing_type }}"
                              data-price="{{ $svc->price }}"
                              data-name="{{ $svc->name }}"
                              data-description="{{ e($svc->description) }}">
                        {{ $svc->name }} (ID: {{ $svc->id }})
                      </option>
                    @endforeach
                  @endisset
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="media_type" class="form-label">Media Type</label>
                <select class="form-control" id="media_type" name="media_type" required>
                  <option value="image">Image</option>
                  <option value="video">Video</option>
                </select>
              </div>

              <div class="form-group mb-3">
                <label for="media_file" class="form-label">Upload File</label>
                <div class="file-upload-wrapper">
                  <input type="file" class="form-control" id="media_file" name="media_file" accept="image/*,video/*" required>
                  <div class="file-upload-text">
                    <i class="entypo-upload"></i>
                    <span>Click to select file or drag and drop</span>
                  </div>
                </div>
                <small class="form-text text-muted" id="fileHelp">
                  Supported: JPG, PNG, WEBP, MP4, WebM. Max size: 10MB
                </small>
              </div>

              <div class="form-group mb-3">
                <label for="display_order" class="form-label">Display Order</label>
                <input type="number" class="form-control" id="display_order" name="display_order" value="1" min="1">
              </div>

              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                <label class="form-check-label" for="is_active">
                  Active (visible on website)
                </label>
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

@push('scripts')
<script>
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
// Show/hide service fields based on section selection
document.getElementById('section').addEventListener('change', function() {
  const serviceFields = document.getElementById('serviceFields');
  const mediaType = document.getElementById('media_type');
  const serviceType = document.getElementById('service_type');
  const serviceId = document.getElementById('service_id');
  
  if (this.value === 'services') {
    serviceFields.style.display = 'block';
    // For services, default to image
    mediaType.value = 'image';
    mediaType.querySelector('option[value="video"]').style.display = 'none';
    // Require service-specific fields when Services selected
    serviceType && serviceType.setAttribute('required', 'required');
    serviceId && serviceId.setAttribute('required', 'required');
  } else {
    serviceFields.style.display = 'none';
    mediaType.querySelector('option[value="video"]').style.display = 'block';
    // Remove required if not Services
    serviceType && serviceType.removeAttribute('required');
    serviceId && serviceId.removeAttribute('required');
  }
});

// Update file help text based on media type
document.getElementById('media_type').addEventListener('change', function() {
  const fileHelp = document.getElementById('fileHelp');
  const fileInput = document.getElementById('media_file');
  
  if (this.value === 'video') {
    fileHelp.textContent = 'Supported videos: MP4, WebM, Ogg. Keep videos short for faster loading.';
    fileInput.setAttribute('accept', 'video/*');
  } else {
    fileHelp.textContent = 'Supported images: JPG, PNG, WEBP. Aim for optimized sizes for faster loading.';
    fileInput.setAttribute('accept', 'image/*');
  }
});

// Manual modal trigger for Add New Media buttons
document.addEventListener('click', function(e) {
  // Check if clicked element or its parent has modal trigger attributes
  let target = e.target;
  while (target && target !== document) {
    if (target.hasAttribute('data-target') && target.getAttribute('data-target') === '#addMediaModal') {
      e.preventDefault();
      
      // Get section if specified
      const section = target.getAttribute('data-section');
      if (section) {
        document.getElementById('section').value = section;
        document.getElementById('section').dispatchEvent(new Event('change'));
      }
      
      // Show modal manually
      $('#addMediaModal').modal('show');
      return;
    }
    target = target.parentElement;
  }
});

// Set section when modal is opened from specific tab
document.addEventListener('click', function(e) {
  if (e.target.hasAttribute('data-section')) {
    const section = e.target.getAttribute('data-section');
    document.getElementById('section').value = section;
    document.getElementById('section').dispatchEvent(new Event('change'));
  }
});

// Auto-fill fields when a Service is selected
document.addEventListener('change', function(e) {
  if (e.target && e.target.id === 'service_id') {
    const opt = e.target.options[e.target.selectedIndex];
    if (!opt) return;
    const type = opt.getAttribute('data-type');
    const price = opt.getAttribute('data-price');
    const name = opt.getAttribute('data-name');
    const desc = opt.getAttribute('data-description');

    const titleEl = document.getElementById('title');
    const descEl = document.getElementById('description');
    const priceEl = document.getElementById('price');
    const typeEl = document.getElementById('service_type');

    if (type && typeEl) typeEl.value = type;
    if (price && priceEl) priceEl.value = price;
    if (name && titleEl) titleEl.value = name;
    if (typeof desc === 'string' && descEl) descEl.value = desc;
  }
});

function editMedia(id) {
  // Fetch media data and populate edit modal
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
      document.getElementById('edit_is_active').checked = data.is_active;
      
      // Set form action
      document.getElementById('editMediaForm').action = `/admin/media/${id}`;
      
      // Show/hide service fields
      const serviceFields = document.getElementById('editServiceFields');
      if (data.section === 'services') {
        serviceFields.style.display = 'block';
      } else {
        serviceFields.style.display = 'none';
      }
      
      // Show current media preview
      const container = document.getElementById('currentMediaContainer');
      if (data.media_type === 'video') {
        container.innerHTML = `<video controls style="max-width: 200px; max-height: 150px;"><source src="${data.media_url}" type="video/mp4"></video>`;
      } else {
        container.innerHTML = `<img src="${data.media_url}" alt="${data.title}" style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 8px;">`;
      }
      
      // Show modal
      $('#editMediaModal').modal('show');
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Failed to load media data');
    });
}

function deleteMedia(id) {
  fetch(`/admin/media/${id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Content-Type': 'application/json',
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Reload page to show updated list
      location.reload();
    } else {
      alert('Failed to delete media: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Failed to delete media');
  });
}

// Handle edit section change
document.getElementById('edit_section').addEventListener('change', function() {
  const serviceFields = document.getElementById('editServiceFields');
  const mediaType = document.getElementById('edit_media_type');
  
  if (this.value === 'services') {
    serviceFields.style.display = 'block';
    mediaType.value = 'image';
    mediaType.querySelector('option[value="video"]').style.display = 'none';
  } else {
    serviceFields.style.display = 'none';
    mediaType.querySelector('option[value="video"]').style.display = 'block';
  }
});

// Handle edit media type change
document.getElementById('edit_media_type').addEventListener('change', function() {
  const fileHelp = document.getElementById('editFileHelp');
  const fileInput = document.getElementById('edit_media_file');
  
  if (this.value === 'video') {
    fileHelp.textContent = 'Leave empty to keep current file. Supported videos: MP4, WebM, Ogg.';
    fileInput.setAttribute('accept', 'video/*');
  } else {
    fileHelp.textContent = 'Leave empty to keep current file. Supported images: JPG, PNG, WEBP.';
    fileInput.setAttribute('accept', 'image/*');
  }
});

// Initialize sortable functionality
$(document).ready(function() {
  // Make hero media sortable
  $('#heroMediaContainer').sortable({
    items: '.col-md-6',
    handle: '.sortable-item',
    cursor: 'move',
    opacity: 0.8,
    placeholder: 'sortable-placeholder',
    update: function(event, ui) {
      updateDisplayOrder('hero');
    }
  });

  // Make services media sortable
  $('#servicesMediaContainer').sortable({
    items: '.col-md-6',
    handle: '.sortable-item',
    cursor: 'move',
    opacity: 0.8,
    placeholder: 'sortable-placeholder',
    update: function(event, ui) {
      updateDisplayOrder('services');
    }
  });
});

function updateDisplayOrder(section) {
  const container = section === 'hero' ? '#heroMediaContainer' : '#servicesMediaContainer';
  const items = [];
  
  $(container + ' .col-md-6').each(function(index) {
    const id = $(this).data('id');
    if (id) {
      items.push({
        id: id,
        display_order: index + 1
      });
    }
  });

  if (items.length > 0) {
    fetch('/admin/media/reorder', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ items: items })
    })
    .then(response => response.json())
    .then(data => {
      if (!data.success) {
        console.error('Failed to update order:', data.message);
        // Optionally show user notification
      }
    })
    .catch(error => {
      console.error('Error updating order:', error);
    });
  }
}
</script>
@endpush
@endsection
