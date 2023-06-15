<?php
/*
Plugin Name: My Custom Post CRUD Plugin
Description: This plugin enables CRUD operations on a custom post type using the WordPress REST API.
Version: 1.0.0
Author: Your Name
*/

// Register custom post type
add_action('init', 'register_custom_post_type');

function register_custom_post_type() {
  $args = array(
    'public' => true,
    'label' => 'Custom Posts',
    'supports' => array('title', 'editor'),
  );

  register_post_type('custom_post', $args);
}

// Register REST API endpoints
add_action('rest_api_init', 'register_custom_post_crud_endpoints');

function register_custom_post_crud_endpoints() {
  // Get custom posts
  register_rest_route('my-custom-post-crud-plugin/v1', '/custom-posts', array(
    'methods' => 'GET',
    'callback' => 'get_custom_posts',
  ));

  // Get a specific custom post
  register_rest_route('my-custom-post-crud-plugin/v1', '/custom-posts/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_custom_post',
  ));

  // Create a custom post
  register_rest_route('my-custom-post-crud-plugin/v1', '/custom-posts', array(
    'methods' => 'POST',
    'callback' => 'create_custom_post',
    'permission_callback' => 'is_user_logged_in',
  ));

  // Update a custom post
  register_rest_route('my-custom-post-crud-plugin/v1', '/custom-posts/(?P<id>\d+)', array(
    'methods' => 'PUT',
    'callback' => 'update_custom_post',
    'permission_callback' => 'is_user_logged_in',
  ));

  // Delete a custom post
  register_rest_route('my-custom-post-crud-plugin/v1', '/custom-posts/(?P<id>\d+)', array(
    'methods' => 'DELETE',
    'callback' => 'delete_custom_post',
    'permission_callback' => 'is_user_logged_in',
  ));
}

// Get all custom posts
function get_custom_posts($request) {
  $args = array(
    'post_type' => 'custom_post',
    'posts_per_page' => -1,
  );
  $posts = get_posts($args);

  return $posts;
}

// Get a specific custom post
function get_custom_post($request) {
  $post_id = $request['id'];
  $post = get_post($post_id);

  return $post;
}

// Create a custom post
function create_custom_post($request) {
  $post_data = array(
    'post_title' => $request['title'],
    'post_content' => $request['content'],
    'post_status' => 'publish',
    'post_type' => 'custom_post',
  );
  $post_id = wp_insert_post($post_data);

  if ($post_id) {
    $post = get_post($post_id);
    return $post;
  } else {
    return new WP_Error('failed_to_create_custom_post', 'Failed to create a new custom post.', array('status' => 500));
  }
}

// Update a custom post
function update_custom_post($request) {
  $post_id = $request['id'];
  $post_data = array(
    'ID' => $post_id,
    'post_title' => $request['title'],
    'post_content' => $request['content'],
  );
  $updated = wp_update_post($post_data);

  if ($updated) {
    $post = get_post($post_id);
    return $post;
  } else {
    return new WP_Error('failed_to_update_custom_post', 'Failed to update the custom post.', array('status' => 500));
  }
}

// Delete a custom post
function delete_custom_post($request) {
  $post_id = $request['id'];
  $deleted = wp_delete_post($post_id, true);

  if ($deleted) {
    return array('message' => 'Custom post deleted successfully.');
  } else {
    return new WP_Error('failed_to_delete_custom_post', 'Failed to delete the custom post.', array('status' => 500));
  }
}



/*

Once the plugin is activated, you can use the following REST API endpoints to perform CRUD operations on your custom post type:

Retrieve all custom posts: GET /wp-json/my-custom-post-crud-plugin/v1/custom-posts
Retrieve a specific custom post by ID: GET /wp-json/my-custom-post-crud-plugin/v1/custom-posts/{id}
Create a new custom post: POST /wp-json/my-custom-post-crud-plugin/v1/custom-posts
Update a custom post by ID: PUT /wp-json/my-custom-post-crud-plugin/v1/custom-posts/{id}
Delete a custom post by ID: DELETE /wp-json/my-custom-post-crud-plugin/v1/custom-posts/{id}
Remember to adjust the endpoint prefix (my-custom-post-crud-plugin/v1), the custom post type name (custom_post), and any additional logic based on your specific requirements.

*/