<?php
/*
Plugin Name: WP Auto Share to Facebook
Plugin URI: https://github.com/nkrastev/Wordpress
Description: Automatically shares WordPress posts to Facebook when they are published.
Version: 1.1
Author: Nikolay Krastev
Author URI: https://github.com/nkrastev/
License: GPL2
*/

include_once(plugin_dir_path(__FILE__) . 'wp-auto-share-to-facebook-admin.php');

function fb_share_on_publish($new_status, $old_status, $post) {
    // Ensure this is not an auto-draft, revision, or something other than a post
    if ('post' !== $post->post_type || 'publish' !== $new_status || 'publish' === $old_status) {
        return;
    }

    // Get post data
    $post_id = $post->ID;
    $post_title = $post->post_title;
    $post_url = get_permalink($post_id);

    // Get the featured image URL
    $thumbnail_id = get_post_thumbnail_id($post_id);
    $thumbnail_url = wp_get_attachment_url($thumbnail_id);

    // Get Facebook Page ID and Access Token from settings
    $page_id = get_option('wpastf_facebook_page_id');
    $access_token = get_option('wpastf_facebook_access_token');
    
    if (empty($page_id) || empty($access_token)) {
        return; // Do nothing if the settings are not configured
    }
    
    // Create post data for Facebook
    $fb_post_data = array(
        'message' => $post_title . ' ' . $post_url,
        'link' => $post_url,
        'access_token' => $access_token
    );

    // Include the featured image if available
    if ($thumbnail_url) {
        $fb_post_data['picture'] = $thumbnail_url;
    }

    // Send request to Facebook Graph API
    $response = wp_remote_post("https://graph.facebook.com/{$page_id}/feed", array(
        'body' => $fb_post_data
    ));

    if (is_wp_error($response)) {
        // Handle error
        error_log('Error sharing post to Facebook: ' . $response->get_error_message());
    }
}

add_action('transition_post_status', 'fb_share_on_publish', 10, 3);
