<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Register all plugin settings
 */
function hmwp_register_settings() {
    // ==========================================
    // 1. GENERAL SETTINGS
    // ==========================================
    hmwp_register_general_settings();
       // ==========================================
    // 2. FAQ SETTINGS
    // ==========================================
    hmwp_faq_general_settings();

    // Add nonce field verification
    add_action('admin_init', 'hmwp_verify_nonce');
}

/**
 * Register General Settings
 */
function hmwp_register_general_settings() {
    // Register sections
    $general_settings_sections = array(
        'hmw_password_section' => array(
            'title' => 'Set Login Password',
            'callback' => 'hmwp_set_password_section_callback'
        ),
        'hmw_seo_section' => array(
            'title' => 'Search Engine Settings',
            'callback' => 'hmwp_seo_disable_section_callback'
        ),
        'hmw_template_section' => array(
            'title' => 'Template Settings',
            'callback' => 'hmwp_template_section_callback'
        )
    );

    // Add sections
    foreach ($general_settings_sections as $section_id => $section) {
        add_settings_section(
            $section_id,
            $section['title'],
            $section['callback'],
            'hmwp_general_options'
        );
    }

    // Register settings fields
    $general_settings = array(
        'hmw_password' => array(
            'label' => 'Password',
            'section' => 'hmw_password_section',
            'callback' => 'hmwp_password_field_callback',
            'sanitize_callback' => 'sanitize_text_field'
        ),
        'hmw_password_hint' => array(
            'label' => 'Password Hint',
            'section' => 'hmw_password_section',
            'callback' => 'hmwp_password_hint_field_callback',
            'sanitize_callback' => 'sanitize_text_field'
        ),
      
       'hmw_ip_whitelist_enabled' => array(
        'label' => 'Enable IP Whitelisting',
        'section' => 'hmw_password_section',
        'callback' => 'hmw_ip_whitelist_enabled_callback',
        'sanitize_callback' => 'absint'  // Use absint for checkbox
    ),
    'hmw_ip_whitelist' => array(
        'label' => 'IP Whitelist',
        'section' => 'hmw_password_section',
        'callback' => 'hmw_ip_whitelist_callback',
        'sanitize_callback' => 'sanitize_textarea_field'  // Use textarea sanitization
    ),
    'hmw_disable_protection' => array( 
        'label' => 'Disable the Password Protection(For Testing)',
        'section' => 'hmw_password_section',
        'callback' => 'hmwp_disable_protection_field_callback',
        'sanitize_callback' => 'sanitize_text_field'
   ),
        'hmw_prevent_indexing' => array(
            'label' => 'Prevent Search Indexing',
            'section' => 'hmw_seo_section',
            'callback' => 'hmwp_prevent_indexing_field_callback',
            'sanitize_callback' => 'sanitize_text_field'
        ),
        'hmw_robots_txt_status' => array(
            'label' => 'Robots.txt Management',
            'section' => 'hmw_seo_section',
            'callback' => 'hmw_robots_txt_status_callback',
            'sanitize_callback' => 'sanitize_text_field'
        ),
        'hmw_robots_txt_content' => array(
            'label' => 'Robots.txt Content',
            'section' => 'hmw_seo_section',
            'callback' => 'hmw_robots_txt_content_callback',
            'sanitize_callback' => 'sanitize_textarea_field'
        ),
        'hmw_login_template' => array(
            'label' => 'Template for Login Page',
            'section' => 'hmw_template_section',
            'callback' => 'hmwp_settings_template_field_callback',
            'sanitize_callback' => 'sanitize_text_field'
        )
    );

    // Register settings and add fields
    foreach ($general_settings as $setting_key => $setting) {
        register_setting(
            'hmwp_general_options', 
            $setting_key, 
            array(
                'sanitize_callback' => $setting['sanitize_callback']
            )
        );
        add_settings_field(
            $setting_key,
            $setting['label'],
            $setting['callback'],
            'hmwp_general_options',
            $setting['section']
        );
    }
}

/**
 * Register FAQ Settings
 */
function hmwp_faq_general_settings() {
    // Register sections
    $faq_settings_sections = array(
        'hmw_faq_section' => array(
            'title' => 'FAQ',
            'callback' => 'hmwp_set_faq_section_callback'
        ),
        'hmw_support_section' => array(
            'title' => 'Support',
            'callback' => 'hmwp_support_section_callback'
        )
    );

    // Add sections
    foreach ($faq_settings_sections as $section_id => $section) {
        add_settings_section(
            $section_id,
            $section['title'],
            $section['callback'],
            'hmwp_faq_options'
        );
    }

    // Register settings fields
    $faq_settings = array(
        'faq_1' => array(
            'label' => '',
            'section' => 'hmw_faq_section',
            'callback' => 'faq_1_callback'
        ),
        'support_button' => array(
            'label' => 'Send an email to me:',
            'section' => 'hmw_support_section',
            'callback' => 'support_button_callback'
        )
    );

    // Register settings and add fields
    foreach ($faq_settings as $setting_key => $setting) {
        register_setting(
            'hmwp_faq_options', 
            $setting_key
        );
        add_settings_field(
            $setting_key,
            $setting['label'],
            $setting['callback'],
            'hmwp_faq_options',
            $setting['section']
        );
    }
}

/**
 * Support Callbacks
 */
function hmwp_set_faq_section_callback() {
    echo '<p>Here is the Most asked questions.</p>';
}


/**
 * FAQ Callbacks
 */
function hmwp_support_section_callback() {
    echo '<p>If you encounter any bugs or have suggestions for improvement, please dont hesitate to reach out. You can contact me directly using the link below, or post your question in the WordPress Plugin Support forum. I actively monitor both channels and will respond promptly to your inquiries.</p>';
}


function faq_1_callback() {
    echo '<h3>What is Hide My Website?</h3>';
    echo '<p>Hide My Website is a plugin that helps protect your WordPress site from unauthorized access. This plugin is mainly used during development when no visitors are allowed.</p>';
    
    echo '<h3>How does it work?</h3>';
    echo '<p>It adds a password protection layer to your website, preventing access to those without the correct password. Adding a password will automatically turn on the password protection. There is no enable button for this. However, if you want to disable the protection for testing (without deactivating the plugin), you can choose the <b>Disable the Password Protection (For Testing)</b> option for that.</p>';
    
    echo '<h3>What is IP whitelisting?</h3>';
    echo '<p>IP whitelisting allows users with specific IPs to access the site while preventing others. You can add multiple IPs, one per line. Remember to turn on the IP whitelisting settings before adding IPs.</p>';
    
    echo '<h3>What is Prevent Search Indexing?</h3>';
    echo '<p>This option enables the "Discourage search engine indexing" setting in WordPress, thus preventing search engine crawlers from indexing your site (please note this is up to the crawlers).</p>';
    
    echo '<h3>What is Robots.txt Management?</h3>';
    echo '<p>This option creates, deletes, or modifies the robots.txt file, which is used for SEO controls. By default, there is content that blocks all bots once it is active. To know more about robots.txt, visit this <a href="https://wp-zip.com/understanding-the-robots-txt-file-a-guide-for-webmasters/">link</a>.</p>';
    
    echo '<h3>What is Templates for Login Page?</h3>';
    echo '<p>This option allows you to switch login templates (more are coming soon!).</p>';
}


function support_button_callback() {
    $support_email = 'lyractk@outlook.com';
    $subject = urlencode('Hidemywebsite-Support-Direct');
    $email_link = "mailto:$support_email?subject=$subject";
    echo '<a href="' . esc_url($email_link) . '" class="button button-primary">Contact Support</a>';
}
// Hook to WordPress admin initialization
add_action('admin_init', 'hmwp_register_settings');

/**
 * Verify nonce for settings form submission
 */
function hmwp_verify_nonce() {
    if (isset($_POST['option_page']) && $_POST['option_page'] == 'hmwp_general_options') {
        if (!isset($_POST['hmwp_settings_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hmwp_settings_nonce'])), 'hmw_settings_action')) {
            add_settings_error('hmw_messages', 'hmw_message', __('Invalid nonce specified', 'hide-my-website'), 'error');
            set_transient('hmwp_settings_errors', get_settings_errors(), 30);
            wp_safe_redirect(add_query_arg('settings-updated', 'false', wp_get_referer()));
            exit;
        }
    }
}

/**
 * Section Callbacks
 */
function hmwp_general_section_callback() {
    echo '<p>Configure general settings for hiding your website.</p>';
}

// Add other section callbacks here (hmwp_set_password_section_callback, hmwp_seo_disable_section_callback, hmwp_template_section_callback)

/**
 * Field Callbacks
 */
// Add your field callback functions here (hmwp_password_field_callback, hmwp_password_hint_field_callback, etc.)

// Remember to add the nonce field in your settings form
function hmwp_add_nonce_to_form() {
    wp_nonce_field('hmw_settings_action', 'hmwp_settings_nonce');
}
add_action('hmw_before_settings_form', 'hmwp_add_nonce_to_form');