<?php
//WordPress template

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'custom-style-2', // Handle for the style.
        plugin_dir_url(__FILE__) . '../../render/css/hmw-wordpress-form-style.css', // Path to the CSS file.
        array(), // Dependencies (empty array for none).
        '1.0.0', // Version.
        'all' // Media type.
    );
});

function hmwp_display_wordpress_template($error = false) {
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php bloginfo('name'); ?> - Protected Site</title>
        <?php wp_head(); ?>

    </head>
    <body class="login">
    <form class="login-form" method="post">
        <?php wp_nonce_field('hmw_password_action', 'hmw_nonce'); ?>
        <h2>This site is password protected</h2>
        <?php if ($error): ?>
            <div class="error-message">
                Incorrect password. Please try again.
            </div>
        <?php endif; ?>
        <p>
            <label for="hmw_password">Password</label>
            <input type="password" name="hmw_password" id="hmw_password" class="input" required autofocus>
            <p>Password Hint: <?php echo esc_html(get_option('hmw_password_hint')); ?></p>
        </p>
        <p>
            <input type="submit" name="wp-submit" value="Log In" class="button button-primary button-large">
        </p>
        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
}