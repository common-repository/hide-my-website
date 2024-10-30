<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 


// Section callback functions
function hmwp_set_password_section_callback() {
    echo '<p>' . esc_html__('Configure your login password settings below:', 'hide-my-website') . '</p>';
    echo '<p>' . esc_html__('Once your password is set, this plugin automatically enables protection. There is no switch to enable it manually. However, if you deactivate the plugin, it will also remove the protection. There is an option to disable the protection temporarily for testing purposes.', 'hide-my-website') . '</p>';
}

function hmwp_seo_disable_section_callback() {
    echo '<p>' . esc_html__('Configure search engine visibility settings below:', 'hide-my-website') . '</p>';
}

function hmwp_template_section_callback() {
    echo '<p>' . esc_html__('Configure template for settings below:', 'hide-my-website') . '</p>';
}

// Field callback functions
function hmwp_password_field_callback() {
    $value = get_option('hmw_password');
    ?>
    <input type="text" name="hmw_password" placeholder="<?php echo esc_attr__('Enter password', 'hide-my-website'); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <p><?php esc_html_e('Set your Protection Password here', 'hide-my-website'); ?></p>
    <?php
}

function hmwp_password_hint_field_callback() {
    $value = get_option('hmw_password_hint');
    ?>
    <input type="text" name="hmw_password_hint" placeholder="<?php echo esc_attr__('Enter password hint', 'hide-my-website'); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <p><?php esc_html_e('Set your Password hint here', 'hide-my-website'); ?></p>
    <?php
}

function hmwp_disable_protection_field_callback() {
    $value = get_option('hmw_disable_protection'); // Retrieve the current value
    ?>
    <input type="checkbox" name="hmw_disable_protection" value="1" <?php checked(1, $value, true); ?>>
    <label style='color:red'><?php esc_html_e('**IMPORTANT** : Leave this unchecked if you want to use password protection', 'hide-my-website'); ?></label>
    <?php
}


function hmw_ip_whitelist_enabled_callback() {
    $hmw_ip_whitelist_enabled = get_option('hmw_ip_whitelist_enabled', '0');
    ?>
    <input type='checkbox' 
           name='hmw_ip_whitelist_enabled' 
           value='1' 
           <?php checked(1, $hmw_ip_whitelist_enabled, true); ?> />
    <label><?php esc_html_e('Enable IP Whitelisting', 'hide-my-website'); ?></label>
    <?php
}

function hmw_ip_whitelist_callback() {
    $hmw_ip_whitelist = get_option('hmw_ip_whitelist', '');
    ?>
    <textarea name='hmw_ip_whitelist' 
              rows='5' 
              cols='50' 
              class="large-text code"><?php echo esc_textarea($hmw_ip_whitelist); ?></textarea>
    <p class="description"><?php esc_html_e('Enter IP addresses, one per line, to whitelist.', 'hide-my-website'); ?></p>
    <?php
}

function hmwp_prevent_indexing_field_callback() {
    $value = get_option('hmw_prevent_indexing');
    ?>
    <input type="checkbox" name="hmw_prevent_indexing" value="1" <?php checked(1, $value, true); ?>>
    <label><?php esc_html_e('Prevent search engines from indexing this site', 'hide-my-website'); ?></label>
    <p><?php esc_html_e('This will turn off discourage crawlers in the site, by turning on the WordPress Setting', 'hide-my-website'); ?></p>
    <?php
}

// Callback for the indexing prevention checkbox
function hmw_prevent_indexing_callback() {
    $prevent_indexing = get_option('hmw_prevent_indexing', '0');
    ?>
    <input type='checkbox' 
           name='hmw_prevent_indexing' 
           value='1' 
           <?php checked(1, $prevent_indexing, true); ?> />
    <label><?php esc_html_e('Prevent search engines from indexing this site', 'hide-my-website'); ?></label>
    <p class="description"><?php esc_html_e('This will modify robots.txt and add meta tags to prevent indexing', 'hide-my-website'); ?></p>
    <?php
}




function hmwp_settings_template_field_callback() {
    $current_template = get_option('hmw_login_template', 'default');
    ?>
    <div class="template-selection">
        <label>
            <input type="radio" name="hmw_login_template" value="default" <?php checked($current_template, 'default'); ?>>
            <?php esc_html_e('Default Template', 'hide-my-website'); ?>
        </label>
        <div class="template-preview">
            <img src="<?php echo esc_url(plugins_url('../assets/default-template.png', __FILE__)); ?>" alt="<?php esc_attr_e('Default template preview', 'hide-my-website'); ?>">
        </div>
        
        <label>
            <input type="radio" name="hmw_login_template" value="wordpress" <?php checked($current_template, 'wordpress'); ?>>
            <?php esc_html_e('WordPress Style Template', 'hide-my-website'); ?>
        </label>
        <div class="template-preview">
            <img src="<?php echo esc_url(plugins_url('../assets/wordpress-template.png', __FILE__)); ?>" alt="<?php esc_attr_e('WordPress style template preview', 'hide-my-website'); ?>">
        </div>
    </div>
    <?php

   
    
}