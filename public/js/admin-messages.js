$(document).ready(function() {
    function loadMessageCount() {
        $.get('/admin/contact-messages?ajax=1', function(data) {
            var count = data.unread_count || 0;
            $('#message-count').text(count);
            $('#message-count-text').text(count);
            if (count > 0) {
                $('#message-count').show();
            } else {
                $('#message-count').hide();
            }
        });
    }
    
    // Reset badge when message icon is clicked
    $('.messages.dropdown').on('click', function() {
        // Mark all messages as read
        $.post('/admin/contact-messages/mark-all-read', {
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function() {
            // Reset badge immediately
            $('#message-count').text('0').hide();
            $('#message-count-text').text('0');
        });
    });
    
    loadMessageCount();
    setInterval(loadMessageCount, 60000);
});
