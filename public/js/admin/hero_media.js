(function($){
  // Namespace for fallback controls
  window.HeroBannerModal = window.HeroBannerModal || {
    open: function(){
      var $m = $('#changeBannerModal');
      if ($.fn.modal) {
        $m.modal('show');
        return;
      }
      // Fallback (no Bootstrap modal plugin)
      if (!$('.modal-backdrop').length) {
        $('<div class="modal-backdrop fade in"></div>').appendTo(document.body);
      }
      $m.addClass('in').show();
      $('body').addClass('modal-open');
      updateBannerHelp();
    },
    close: function(){
      var $m = $('#changeBannerModal');
      if ($.fn.modal) {
        $m.modal('hide');
        return;
      }
      // Fallback
      $m.removeClass('in').hide();
      $('.modal-backdrop').remove();
      $('body').removeClass('modal-open');
    }
  };

  // Ensure modal opens on button click reliably
  $(document).on('click', '[data-target="#changeBannerModal"]', function(e){
    e.preventDefault();
    window.HeroBannerModal.open();
  });

  // Close on elements with data-dismiss="modal" when Bootstrap is missing
  $(document).on('click', '[data-dismiss="modal"]', function(e){
    // If Bootstrap present, it will handle; fallback otherwise
    if (!$.fn.modal) {
      e.preventDefault();
      window.HeroBannerModal.close();
    }
  });

  // Update help text and file accept attribute based on selected media type
  function updateBannerHelp() {
    var $type = $('#bannerType');
    if ($type.length === 0) return;
    var val = $type.val();
    var help = $('#bannerHelp');
    var input = $('#bannerMedia');
    if(val === 'image'){
      help.text('Supported images: JPG, PNG, WEBP. Aim for optimized sizes for faster loading.');
      input.attr('accept', 'image/*');
    } else {
      help.text('Supported videos: MP4, WebM, Ogg. Keep videos short for faster loading.');
      input.attr('accept', 'video/*');
    }
  }

  $(document).on('change', '#bannerType', updateBannerHelp);

  // Initialize on first open as well
  $(document).on('shown.bs.modal', '#changeBannerModal', updateBannerHelp);

  // Make the entire upload wrapper clickable to trigger the file dialog
  $(document).on('click', '.file-upload-wrapper', function(e){
    // Avoid double opening when clicking directly on the input
    if ($(e.target).is('#bannerMedia')) return;
    var $input = $(this).find('#bannerMedia');
    if ($input.length) {
      $input.trigger('click');
    }
  });

  // Show selected file name and a quick preview (image/video)
  $(document).on('change', '#bannerMedia', function(){
    var file = this.files && this.files[0];
    var $wrapper = $(this).closest('.file-upload-wrapper');
    if (!file || !$wrapper.length) return;

    // Add/Update filename display
    var $name = $wrapper.find('.selected-file-name');
    if (!$name.length) {
      $name = $('<div class="selected-file-name small mt-2 text-muted"></div>').appendTo($wrapper);
    }
    var sizeKB = Math.max(1, Math.round(file.size / 1024));
    $name.text(file.name + ' (' + sizeKB + ' KB)');

    // Add/Update preview container
    var $preview = $wrapper.find('.selected-file-preview');
    if (!$preview.length) {
      $preview = $('<div class="selected-file-preview mt-3"></div>').appendTo($wrapper);
    }
    $preview.empty();

    try {
      var url = URL.createObjectURL(file);
      if (file.type && file.type.indexOf('image/') === 0) {
        $('<img>', {
          src: url,
          alt: 'Selected image preview',
          class: 'img-responsive',
          css: { maxWidth: '100%', borderRadius: '8px' }
        }).appendTo($preview);
      } else if (file.type && file.type.indexOf('video/') === 0) {
        $('<video>', {
          src: url,
          controls: true,
          class: 'w-100',
          css: { maxHeight: '240px', borderRadius: '8px' }
        }).appendTo($preview);
      } else {
        // Unsupported type preview: show generic icon/text
        $('<div class="text-muted small">Selected file: ' + file.name + '</div>').appendTo($preview);
      }
    } catch (err) {
      // Fallback: just show the file name
      $('<div class="text-muted small">Selected file: ' + file.name + '</div>').appendTo($preview);
    }
  });

})(jQuery);
