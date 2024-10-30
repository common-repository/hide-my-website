<?php
/**
 * Add a settings link to the plugin page.
 */
add_filter( 'plugin_action_links_hide-my-website/hide-my-website.php', 'hmw_settings_link' );
function hmw_settings_link( $links ) {
	// Build and escape the URL.
	$url = esc_url( add_query_arg(
		'page',
		'hide-my-website',
		get_admin_url() . 'admin.php'
	) );
	// Create the link.
	$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
	// Adds the link to the end of the array.
	array_push(
		$links,
		$settings_link
	);
	return $links;
}