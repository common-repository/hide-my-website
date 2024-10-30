<?php
//uninstall file


// Add uninstall cleanup
function hmw_plugin_uninstall() {
    // Remove the physical robots.txt file if it was created by our plugin
    $robots_path = ABSPATH . 'robots.txt';
    if (file_exists($robots_path)) {
        $content = file_get_contents($robots_path);
        if (strpos($content, "User-agent: *\nDisallow: /") !== false) {
            unlink($robots_path);
        }
    }
    
    // Clean up options
    delete_option('hmw_prevent_indexing');
    delete_option('hmw_custom_robots');
}
hmw_plugin_uninstall();