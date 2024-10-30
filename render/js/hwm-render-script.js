jQuery(document).ready(function($) {
    // Create robots.txt
    $('#create_robots').click(function() {
        var nonce = $('#hmw_robots_nonce').val();
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'hmw_create_robots',
                nonce: nonce
            },
            success: function(response) {
                if(response.success) {
                    alert('robots.txt created successfully');
                    location.reload();
                } else {
                    alert('Failed to create robots.txt: ' + response.data.message);
                }
            }
        });
    });

    // Delete robots.txt
    $('#delete_robots').click(function() {
        if(confirm('Are you sure you want to delete robots.txt?')) {
            var nonce = $('#hmw_robots_nonce').val();
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'hmw_delete_robots',
                    nonce: nonce
                },
                success: function(response) {
                    if(response.success) {
                        alert('robots.txt deleted successfully');
                        location.reload();
                    } else {
                        alert('Failed to delete robots.txt: ' + response.data.message);
                    }
                }
            });
        }
    });
});
