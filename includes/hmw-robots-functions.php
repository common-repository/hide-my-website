<?php

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Callback to display robots.txt status and management options
function hmw_robots_txt_status_callback() {
    $robots_path = ABSPATH . 'robots.txt';
    $robots_exists = file_exists($robots_path);
    $robots_content = '';
    
    if ($robots_exists) {
        $robots_content = file_get_contents($robots_path);
    }
    
    // Display current status
    echo '<div class="robots-status">';
    if ($robots_exists) {
        echo '<p class="robots-exists" style="color: green;">' . 
             esc_html__('✓ robots.txt exists', 'hide-my-website') . '</p>';
    } else {
        echo '<p class="no-robots" style="color: red;">' . 
             esc_html__('✗ No robots.txt found', 'hide-my-website') . '</p>';
    }
    
    // Add management buttons
    echo '<div class="robots-management">';
    ?>
    <input type="button" 
           id="create_robots" 
           class="button button-secondary" 
           value="<?php esc_attr_e('Create robots.txt', 'hide-my-website'); ?>"
           <?php echo $robots_exists ? 'disabled' : ''; ?>>
           
    <input type="button" 
           id="delete_robots" 
           class="button button-secondary" 
           value="<?php esc_attr_e('Delete robots.txt', 'hide-my-website'); ?>"
           <?php echo !$robots_exists ? 'disabled' : ''; ?>>
           
    <?php
    echo '</div>';
    echo '<p>' . esc_html__('Sometimes,these changes will not work if you not have enough permissions.Try reset permissions for that case.', 'hide-my-website') . '</p>';
    
    // Add nonce field for security
    wp_nonce_field('hmw_robots_management', 'hmw_robots_nonce');
    
    // Add JavaScript for AJAX handling
    ?>
    <script type="text/javascript">
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
    </script>
    <?php
}

// Callback to display and edit robots.txt content
function hmw_robots_txt_content_callback() {
    $robots_path = ABSPATH . 'robots.txt';
    // $default_content = "User-agent: *\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php";
    $default_content = "User-agent: *\nDisallow: /";
    
    $content = file_exists($robots_path) ? file_get_contents($robots_path) : $default_content;
    ?>
    <textarea name="hmw_robots_txt_content" 
              id="hmw_robots_txt_content" 
              rows="10" 
              cols="50" 
              class="large-text code"><?php echo esc_textarea($content); ?></textarea>
    <p class="description">
        <?php esc_html_e('Edit the content of your robots.txt file. Changes will be saved when you update settings.', 'hide-my-website'); ?>
    </p>
    <?php
}

// AJAX handler for creating robots.txt
function hmw_ajax_create_robots() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'hmw_robots_management')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
    }
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Permission denied'));
    }
    
    $robots_path = ABSPATH . 'robots.txt';
    
    // Check if file already exists
    if (file_exists($robots_path)) {
        wp_send_json_error(array('message' => 'robots.txt already exists'));
    }
    
    // Default content
    // $content = "User-agent: *\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php";
    $content = "User-agent: *\nDisallow: /";
    
    // Try to create file
    if (is_writable(ABSPATH)) {
        if (file_put_contents($robots_path, $content) !== false) {
            wp_send_json_success(array('message' => 'robots.txt created'));
        } else {
            wp_send_json_error(array('message' => 'Failed to write file'));
        }
    } else {
        wp_send_json_error(array('message' => 'Directory not writable'));
    }
}
add_action('wp_ajax_hmw_create_robots', 'hmw_ajax_create_robots');

// AJAX handler for deleting robots.txt
function hmw_ajax_delete_robots() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'hmw_robots_management')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
    }
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Permission denied'));
    }
    
    $robots_path = ABSPATH . 'robots.txt';
    
    // Check if file exists
    if (!file_exists($robots_path)) {
        wp_send_json_error(array('message' => 'robots.txt does not exist'));
    }
    
    // Try to delete file
    if (is_writable($robots_path)) {
        if (unlink($robots_path)) {
            wp_send_json_success(array('message' => 'robots.txt deleted'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete file'));
        }
    } else {
        wp_send_json_error(array('message' => 'File not writable'));
    }
}
add_action('wp_ajax_hmw_delete_robots', 'hmw_ajax_delete_robots');

// Save robots.txt content when settings are saved
function hmw_save_robots_content($value) {
    if (!current_user_can('manage_options')) {
        return $value;
    }
    
    $robots_path = ABSPATH . 'robots.txt';
    
    if (file_exists($robots_path) && is_writable($robots_path)) {
        file_put_contents($robots_path, $value);
    }
    
    return $value;
}
add_filter('pre_update_option_hmw_robots_txt_content', 'hmw_save_robots_content');