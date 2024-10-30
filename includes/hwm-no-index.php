<?php

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
// Update robots.txt file and search engine visibility setting
function hmwp_update_robots_txt() {
    $prevent_indexing = get_option('hmw_prevent_indexing');
    
    // Update WordPress search engine visibility setting
    update_option('blog_public', $prevent_indexing ? '0' : '1');

    // Use WordPress function to get the content of robots.txt
    $robots_content = get_robots_txt();
    
    if ($prevent_indexing) {
        // Prepend our rules to the existing content
        $new_rules = "User-agent: *\nDisallow: /\n\n";
        $robots_content = $new_rules . $robots_content;
    } else {
        // Remove our rules if they exist
        $robots_content = preg_replace("/User-agent: \*\nDisallow: \/\n\n/", "", $robots_content);
    }
    
    // Use WordPress function to update robots.txt
    update_option('hmwp_robots_txt_content', sanitize_text_field(wp_unslash($robots_content)));
    
    // Add a filter to modify the robots.txt output
    add_filter('robots_txt', 'hmwp_filter_robots_txt', 10, 2);
}

function hmwp_filter_robots_txt($output, $public) {
    $custom_content = get_option('hmwp_robots_txt_content');
    return !empty($custom_content) ? $custom_content : $output;
}