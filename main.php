<?php
//all connections are from here
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//admin
require_once plugin_dir_path(__FILE__) . 'includes/hmw-general-functions.php';
//core
require_once plugin_dir_path(__FILE__) . 'includes/hmw-https-protection.php';

 //Robots
 require_once plugin_dir_path(__FILE__) . 'includes/hmw-robots-functions.php';

//search engine discourage control
add_action('admin_init', 'hmwp_disable_crawlers_load');


/**
 * Enable crawl
 */
function hmwp_revert_robots_txt() {
    // error_log('blog_publicdeact');
    update_option('blog_public', '1');  // Allow search engines
}


/**
 * Disable crawl
 */
function hmwp_disable_crawlers_load() {
    
    $value = get_option('hmw_prevent_indexing');
    
    if($value){
        update_option('blog_public', '0');  // Disallow search engines
    }else{
        update_option('blog_public', '1');  // Allow search engines
    }
}

