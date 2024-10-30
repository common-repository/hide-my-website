<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Register Settings
require_once plugin_dir_path(__FILE__) . 'hmw-settings-register.php';



// Add settings page
function hmwp_add_settings_page() {
    add_options_page('Hide My Website Settings', 'Hide My Website', 'manage_options', 'hide-my-website', 'hmwp_render_settings_page');
}
add_action('admin_menu', 'hmwp_add_settings_page');

// Render settings page with tabs
function hmwp_render_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

  
    $nonce_field = wp_nonce_field('hmw_settings_action', 'hmwp_settings_nonce', true, false);
    $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'general';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hmwp_settings_nonce'])) {
        // Sanitize and verify the nonce.
        $nonce = sanitize_text_field(wp_unslash($_POST['hmwp_settings_nonce']));
        if (!wp_verify_nonce($nonce, 'hmw_settings_action')) {
            wp_die(esc_html__('Nonce verification failed!', 'hide-my-website'));
        }
    
        
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=hide-my-website&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
            <a href="?page=hide-my-website&tab=faq" class="nav-tab <?php echo $active_tab == 'faq' ? 'nav-tab-active' : ''; ?>">FAQ</a>
        </h2>
        <form method="post" action="options.php">
            <?php
        echo wp_kses($nonce_field, [
            'input' => [
                'type'  => [],
                'name'  => [],
                'id'    => [],
                'value' => [],
            ]
        ]);

            if ($active_tab == 'general') {
                settings_fields('hmwp_general_options');
                do_settings_sections('hmwp_general_options');
                submit_button();
            }
            if ($active_tab == 'faq') {
                settings_fields('hmwp_faq_options');
                do_settings_sections('hmwp_faq_options');
            }
               
            ?>
        </form>
    </div>
    <?php
}

