<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// Modified display function to handle both templates
function hmwp_display_password_form($error = false) {
    $template = get_option('hmw_login_template', 'default');

    if ($template === 'wordpress') {
        require_once plugin_dir_path(__FILE__) . 'templates/hmw-wordpress-form.php';
        hmwp_display_wordpress_template($error);
    } elseif($template === 'default') {
        require_once plugin_dir_path(__FILE__) . 'templates/hmw-default-form.php';
        hmwp_display_default_template($error);
    }
}

//IP whitelisting
// function hmw_check_ip_whitelist() {
//     $whitelist = get_option('hmw_ip_whitelist', '');
//     error_log('REMOTE_ADDR');
//     error_log($whitelist);
//     error_log('ip');
//     $user_ip = $_SERVER['REMOTE_ADDR'];
//     error_log($user_ip);
//     if ($whitelist) {
//         $whitelist_ips = array_map('trim', explode("\n", $whitelist));
//         if (in_array($user_ip, $whitelist_ips)) {
//             return true;
//         }
//     }
//     return false;
// }

// Add a function to validate IP addresses (optional but recommended)
function hmw_validate_ip_whitelist($input) {
    $valid_ips = array();
    $ips = explode("\n", $input);
    
    foreach ($ips as $ip) {
        $ip = trim($ip);
        if (empty($ip)) continue;
        
        // Validate IPv4 and IPv6 addresses
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $valid_ips[] = $ip;
        }
    }
    
    return implode("\n", $valid_ips);
}