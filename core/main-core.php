<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Main functionality
function hmwp_check_password() {
    // Only check for non-admin, non-logged-in users
    if (!is_admin() && !is_user_logged_in()) {
        $password = get_option('hmw_password');
        $disable_protection = get_option('hmw_disable_protection');

         // Check IP whitelist first
         $ip_whitelist_enabled = get_option('hmw_ip_whitelist_enabled', '0');
         if ($ip_whitelist_enabled) {
             $ip_whitelist = get_option('hmw_ip_whitelist', '');
             $visitor_ip = $_SERVER['REMOTE_ADDR'];
            //  error_log($visitor_ip);
            //  error_log($visitor_ip);
             $whitelisted_ips = array_map('trim', explode("\n", $ip_whitelist));
             
             // If visitor IP is in whitelist, allow access
             if (in_array($visitor_ip, $whitelisted_ips)) {
                 return;
             }
         }

        // If protection is not disabled AND (password exists AND access cookie is not valid)
        if (!$disable_protection && 
            !empty($password) && 
            (!isset($_COOKIE['hmw_access']) || $_COOKIE['hmw_access'] !== md5($password))
        ) {
            $error = false;

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hmw_password'])) {
                // Verify nonce
                if (isset($_POST['hmw_nonce']) && 
                    wp_verify_nonce(
                        sanitize_text_field(wp_unslash($_POST['hmw_nonce'])), 
                        'hmw_password_action'
                    )
                ) {
                    if ($_POST['hmw_password'] === $password) {
                        setcookie('hmw_access', md5($password), time() + 3600, '/', '', true, true);
                        wp_safe_redirect($_SERVER['REQUEST_URI']);
                        exit;
                    } else {
                        $error = true;
                    }
                } else {
                    $error = true;
                }
            }

            hmwp_display_password_form($error);
            exit;
        }
    }
}
add_action('template_redirect', 'hmwp_check_password');