<?php
/**
Plugin Name: Security Headers Plugin
Description: Allows you to set and manage security headers for your WordPress site.
Plugin Name: Security Headers Plugin
GitHub Plugin URI: https://github.com/samrat54/ws-security-plugins
GitHub Branch: main
Description: Allows you to set and manage security headers for your WordPress site.
Version: 2.0.0
 */

// Add a menu item in the admin menu for your plugin
add_action('admin_menu', 'shp_add_menu_item');
function shp_add_menu_item() {
    add_menu_page('Security Headers', 'Security Headers', 'manage_options', 'security-headers', 'shp_settings_page');
}

// Create the settings page
function shp_settings_page() {
    ?>
    <div class="wrap">
        <h2>Security Headers Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('shp-settings-group');
            do_settings_sections('shp-settings-group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Strict-Transport-Security</th>
                    <td><input type="text" name="shp_hsts" value="<?php echo esc_attr(get_option('shp_hsts', 'max-age=63072000')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Content-Security-Policy</th>
                    <td><input type="text" name="shp_csp" value="<?php echo esc_attr(get_option('shp_csp', "object-src 'none'")); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">X-XSS-Protection</th>
                    <td><input type="text" name="shp_xss" value="<?php echo esc_attr(get_option('shp_xss', '1; mode=block')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">X-Content-Type-Options</th>
                    <td><input type="text" name="shp_xcto" value="<?php echo esc_attr(get_option('shp_xcto', 'nosniff')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Cross-Origin-Embedder-Policy</th>
                    <td><input type="text" name="shp_coep" value="<?php echo esc_attr(get_option('shp_coep', 'unsafe-none')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Cross-Origin-Opener-Policy</th>
                    <td><input type="text" name="shp_coop" value="<?php echo esc_attr(get_option('shp_coop', 'unsafe-none')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">X-Frame-Options</th>
                    <td><input type="text" name="shp_xfo" value="<?php echo esc_attr(get_option('shp_xfo', 'SAMEORIGIN')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Permissions-Policy</th>
                    <td><input type="text" name="shp_pp" value="<?php echo esc_attr(get_option('shp_pp', 'accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=(), interest-cohort=()')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Referrer-Policy</th>
                    <td><input type="text" name="shp_rp" value="<?php echo esc_attr(get_option('shp_rp', 'origin-when-cross-origin')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register and initialize the settings
add_action('admin_init', 'shp_initialize_settings');
function shp_initialize_settings() {
    register_setting('shp-settings-group', 'shp_hsts');
    register_setting('shp-settings-group', 'shp_csp');
    register_setting('shp-settings-group', 'shp_xss');
    register_setting('shp-settings-group', 'shp_xcto');
    register_setting('shp-settings-group', 'shp_coep');
    register_setting('shp-settings-group', 'shp_coop');
    register_setting('shp-settings-group', 'shp_xfo');
    register_setting('shp-settings-group', 'shp_pp');
    register_setting('shp-settings-group', 'shp_rp');
}

// Add security headers to the site based on user settings
add_action('send_headers', 'shp_set_security_headers');
function shp_set_security_headers() {
    header('Strict-Transport-Security: ' . esc_attr(get_option('shp_hsts', 'max-age=63072000')));
    header('Content-Security-Policy: ' . esc_attr(get_option('shp_csp', "object-src 'none'")));
    header('X-XSS-Protection: ' . esc_attr(get_option('shp_xss', '1; mode=block')));
    header('X-Content-Type-Options: ' . esc_attr(get_option('shp_xcto', 'nosniff')));
    header('Cross-Origin-Embedder-Policy: ' . esc_attr(get_option('shp_coep', 'unsafe-none')));
    header('Cross-Origin-Opener-Policy: ' . esc_attr(get_option('shp_coop', 'unsafe-none')));
    header('X-Frame-Options: ' . esc_attr(get_option('shp_xfo', 'SAMEORIGIN')));
    header('Permissions-Policy: ' . esc_attr(get_option('shp_pp', 'accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=(), interest-cohort=()')));
    header('Referrer-Policy: ' . esc_attr(get_option('shp_rp', 'origin-when-cross-origin')));
}

// Disable security headers
function shp_disable_security_headers() {
    header_remove('Strict-Transport-Security');
    header_remove('Content-Security-Policy');
    header_remove('X-XSS-Protection');
    header_remove('X-Content-Type-Options');
    header_remove('Cross-Origin-Embedder-Policy');
    header_remove('Cross-Origin-Opener-Policy');
    header_remove('X-Frame-Options');
    header_remove('Permissions-Policy');
    header_remove('Referrer-Policy');
}

// Hook to disable headers if needed
if (get_option('shp_enable_plugin', 1) === 0) {
    add_action('send_headers', 'shp_disable_security_headers');
}
