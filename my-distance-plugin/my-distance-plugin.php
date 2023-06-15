<?php
/**
 * Plugin Name: My Distance Plugin
 * Description: Calculate the distance between two cities using the Google Maps Distance Matrix API.
 * Version: 1.0.0
 * Author: Your Name
 */

// Register an action hook to trigger the distance calculation
add_action('admin_init', 'my_distance_plugin_calculate_distance');

function my_distance_plugin_calculate_distance() {
    // Check if the plugin settings form has been submitted
    if (isset($_POST['my_distance_plugin_submit'])) {
        // Retrieve the source and destination cities from the submitted form
        $source_city = sanitize_text_field($_POST['my_distance_plugin_source_city']);
        $destination_city = sanitize_text_field($_POST['my_distance_plugin_destination_city']);

        // Perform distance calculation
        $distance = calculate_distance($source_city, $destination_city);

        // Display the calculated distance
        echo "<div class='post-content'>";
        echo 'The distance between ' . $source_city . ' and ' . $destination_city . ' is ' . $distance . ' km.';
        echo "</div>";
    }
}

// Function to calculate the distance between two cities
function calculate_distance($source_city, $destination_city) {
    // Google Maps Distance Matrix API endpoint
    $api_endpoint = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    // Google API Key (replace with your own key)
    $api_key = 'AIzaSyA4mAlayK3AnWP3EkVw7dTvR_uAvyHL5wU';

    // Build the API request URL
    $request_url = $api_endpoint . '?origins=' . urlencode($source_city) . '&destinations=' . urlencode($destination_city) . '&key=' . $api_key;

    // Send the API request and retrieve the response
    $response = wp_remote_get($request_url);

    // Check if the request was successful and retrieve the response body
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $response_body = wp_remote_retrieve_body($response);
        $data = json_decode($response_body, true);

        // Check if the API response contains distance information
        if (isset($data['rows'][0]['elements'][0]['distance']['value'])) {
            // Extract the distance in meters and convert it to kilometers
            $distance_in_meters = $data['rows'][0]['elements'][0]['distance']['value'];
            $distance_in_km = $distance_in_meters / 1000;

            return $distance_in_km;
        }
    }

    return 'Distance calculation failed.';
}

// Plugin settings form
function my_distance_plugin_settings_form() {
    ?>
    <form method="post" action="">
        <label for="my_distance_plugin_source_city">Source City:</label>
        <input type="text" name="my_distance_plugin_source_city" id="my_distance_plugin_source_city" required>

        <label for="my_distance_plugin_destination_city">Destination City:</label>
        <input type="text" name="my_distance_plugin_destination_city" id="my_distance_plugin_destination_city" required>

        <input type="submit" name="my_distance_plugin_submit" value="Calculate Distance">
    </form>
    <?php
}

// Display the plugin settings form
add_action('admin_menu', 'my_distance_plugin_add_menu');

function my_distance_plugin_add_menu() {
    add_menu_page('Distance Plugin', 'Distance Plugin', 'manage_options', 'my-distance-plugin', 'my_distance_plugin_settings_page');
}

function my_distance_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Distance Plugin</h1>

        <?php my_distance_plugin_settings_form(); ?>
    </div>
    <?php
}
