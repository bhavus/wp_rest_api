<?php
/*
*Plugin Name: wp weather api
*Description: My plugin to explain example of custom code that you can use to display the weather [weather] 
*Version: 1.0
*Author: Shailesh Parmar
*Author URI: https://xyz.com/
*/ 

function display_weather() {

    $api_key = '8c23d6a317399d8a024419a3d28c04f0';
    $city = 'varanasi'; // Replace with your desired city
    $country_code = 'in';

    $api_url = "https://api.openweathermap.org/data/2.5/weather?q={$city},{$country_code}&units=metric&appid={$api_key}";

    $response = wp_remote_get($api_url);

    if (is_array($response) && !is_wp_error($response)) {
    

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        if($data){

            // Access the weather data
            $temperature = round($data->main->temp);
            $description = ucwords($data->weather[0]->description);
            $icon = "https://openweathermap.org/img/w/{$data->weather[0]->icon}.png";
            

            // Display the weather information
            echo "<div class='weather'>
                    <img src='{$icon}' alt='{$description}' />
                    <span>{$temperature}&deg;C , {$city}</spam>
                    <p>Description: {$description}</p>
                  </div>";
    
            // echo "<h2>Weather in {$city}</h2>";
           
           }
      }
}

// Create a shortcode to display the weather

add_shortcode('weather', 'display_weather');
