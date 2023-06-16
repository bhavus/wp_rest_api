<?php
/**
 * Plugin Name: Rest API trigger
 * Plugin URI: https://github.com/yttechiepress/rest-api-trigger
 * Author: Techiepress
 * Author URI: https://github.com/yttechiepress/rest-api-trigger
 * Description: Trigger a button using the REST API and Ajax.
 * Version: 0.1.0
 * License: GPL2
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: rest-api-trigger
*/


//http://localhost/wp_new/wp-json/techiepress/trigger/v1/button-trigger


defined( 'ABSPATH') or die( 'Authorized Access Denied!' );

add_action( 'rest_api_init', 'techiepress_add_endpoint' );

function techiepress_add_endpoint() {
    register_rest_route(
        'techiepress/trigger/v1',
        'button-trigger',
        array(
            'method'              => 'GET',
            'permission_callback' => '__return_true',
            'callback'            => 'techiepress_info'
        )
    );
}

function techiepress_info() {
    return 'Techiepress was here!';
}

add_action( 'wp_enqueue_scripts', 'techiepress_footer_js' );

function techiepress_footer_js() {
    wp_enqueue_script( 'trigger_ajax_js', plugin_dir_url(__FILE__) . '/js/main.js', array( 'jquery' ), '1.0.0', true );

    wp_localize_script( 'trigger_ajax_js', 'rest_url', array(
        'url' => esc_url_raw( rest_url( 'techiepress/trigger/v1/button-trigger' ) )
    ) );
}
