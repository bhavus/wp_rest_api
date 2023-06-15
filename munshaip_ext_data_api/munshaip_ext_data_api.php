<?php
/*
*Plugin Name: Munshaip External Api Shortcode
*Description: My plugin to Display Custom Post testimonial data with shortcode [external_data] 
*Version: 1.0
*Author: Shailesh Parmar
*Author URI: https://xyz.com/
*/

defined('ABSPATH')  || die('Unauthorized Access');

// Action when user logs into admin panel
add_shortcode('external_data', 'callback_function_name');

function callback_function_name( $atts ) {

	if ( is_admin() ) {
		return '<p>This is where the shortcode [external_data] will show up.</p>';
	}

    $defaults = [
      'title'  => 'Table title'
    ];
      
    $atts = shortcode_atts(
        $defaults,
        $atts,
        'external_data'
    );          

    $url = 'https://jsonplaceholder.typicode.com/users';
    //$url = 'http://localhost/wp_new/wp-json/wp/v2/users';

    $arguments = array(
        'method' => 'GET' 
    );

    $response = wp_remote_get($url, $arguments);
   
    if (is_wp_error($response) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
    } 
    
    $results = json_decode( wp_remote_retrieve_body($response) );


    $html = '<h2>' . $atts['title'] . '</h2>
		<table>
			<tr>
				<td>id</td>
				<td>Name</td>
				<td>Username</td>
				<td>Email</td>
				<td>Address</td>
			</tr>';
    
    foreach( $results as $result ) {
		$html .= '<tr>' ;
		$html .= '<td>'  .  $result->id . '</td>' ;
		$html .= '<td>'  .  $result->name . '</td>' ;
		$html .= '<td>'  .  $result->username . '</td>' ;
		$html .= '<td>'  .  $result->email . '</td>' ;
		$html .= '<td>'  .  $result->address->street  .  ', ' . $result ->address->suite .  ', '  .  $result->address->city .  ', ' . $result->address->zipcode . '</td>';
		$html .= '</ tr>' ;
    }

    $html .= '</table>' ;

    return $html ;    
}    