<?php
// Add settings page
function wpastf_add_admin_menu() {
    add_options_page(
        'WP Auto Share to Facebook', 
        'Auto Share to Facebook', 
        'manage_options', 
        'wp-auto-share-to-facebook', 
        'wpastf_options_page'
    );
}
add_action('admin_menu', 'wpastf_add_admin_menu');

// Register settings
function wpastf_settings_init() {
    register_setting('wpastf_options_group', 'wpastf_facebook_page_id');
    register_setting('wpastf_options_group', 'wpastf_facebook_access_token');

    add_settings_section(
        'wpastf_section', 
        'Facebook API Settings', 
        'wpastf_section_callback', 
        'wp-auto-share-to-facebook'
    );

    add_settings_field(
        'wpastf_facebook_page_id', 
        'Facebook Page ID', 
        'wpastf_facebook_page_id_render', 
        'wp-auto-share-to-facebook', 
        'wpastf_section'
    );

    add_settings_field(
        'wpastf_facebook_access_token', 
        'Facebook Access Token', 
        'wpastf_facebook_access_token_render', 
        'wp-auto-share-to-facebook', 
        'wpastf_section'
    );
}
add_action('admin_init', 'wpastf_settings_init');

function wpastf_facebook_page_id_render() {
    $page_id = get_option('wpastf_facebook_page_id');
    echo '<input type="text" name="wpastf_facebook_page_id" value="' . esc_attr($page_id) . '" />';
}

function wpastf_facebook_access_token_render() {
    $access_token = get_option('wpastf_facebook_access_token');
    echo '<input type="text" name="wpastf_facebook_access_token" value="' . esc_attr($access_token) . '" />';
}

function wpastf_section_callback() {
    echo 'Enter your Facebook Page ID and Access Token.';
}

function wpastf_options_page() {
    ?>
    <form action="options.php" method="post">
        <h2>WP Auto Share to Facebook</h2>
        <?php
        settings_fields('wpastf_options_group');
        do_settings_sections('wp-auto-share-to-facebook');
        submit_button();
        ?>
    </form>
    <?php
}
