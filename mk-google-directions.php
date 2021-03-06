<?php

/*
  Plugin Name: MK Google Directions - Customized for ID
  Plugin URI:
  Description: Customized for ID MK Google Direction uses Google Directions API.
  Version: 2.2
  Author: Manoj Kumar
  Author URI:
  Tags:
 */

global $wp_version;

// Wordppress Version Check
if (version_compare($wp_version, '3.5', '<')) {
    exit($exit_msg . " Please upgrade your wordpress.");
}


/*
 * Add Stylesheet & Scripts for the plugin
 */

add_action('wp_enqueue_scripts', 'mkgd_scripts');

function mkgd_scripts() {
    wp_register_script('mkgd-google-map-places', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places&language=' . get_option('mkgd_language', 'en'), array('jquery'), '', true);
    wp_enqueue_script('mkgd-google-map-places');

    wp_register_style('mkgd-css', plugins_url('/css/mkgd-styles.css', __FILE__));
    wp_enqueue_style('mkgd-css');
}

/*
 * Add Footer Content
 */

add_action('wp_footer', 'mkgd_footer');

function mkgd_footer() {
    wp_enqueue_script('mkgd-google-map', plugins_url('/js/mkgd-google-map.js', __FILE__), array('jquery'));
}

/*
 * Initialize the map
 */

function mkgd_initialize() {
    $output = '<style> #mkgd-map-canvas{ width: ' . get_option("mkgd_width", "500px") . '; height: ' . get_option("mkgd_height", "500px") . '; }</style>';

    $output .='<div id="mkgd-wrap"><h4>Get Directions</h4>
    <div class="mkgd-form row-fluid">
      <div class="span12">';
    if (!get_option("mkgd_origin_hide")) {
        $output .='<label for="origin">Origin</label>';
        $type_origin = 'text';
    } else {
        $type_origin = 'hidden';
    }

    $output .='<input id="origin" name="origin" type="' . $type_origin . '" size="50" value="' . get_option("mkgd_origin") . '" />';

    if (!get_option("mkgd_destination_hide")) {
        $output .='<label for="destination">Destination</label>';
        $type_destination = 'text';
    } else {
        $type_destination = 'hidden';
    }
    $output .='<input id="destination" name="destination" type="' . $type_destination . '" size="50" value="' . get_option("mkgd_destination") . '" />';
    $output .='<input type="button" onclick="calcRoute();" name="btnMkgdSubmit" id="btnMkgdSubmit" value="Submit" class="btn btn-warning  btn-normal"/>
      </div>
    </ul><!-- End .mkgd-form -->
    <div id="mkgd-map-canvas"></div><!-- End #mkgd-map-canvas -->
    <div id="directions"></div><!-- End #directions -->
  </div><!-- End #mkgd-wrap -->';

    $output .='<script type="text/javascript">';
    $output .='jQuery("#btnMkgdSubmit").click(function() {';
    $output .='var start = document.getElementById(\'origin\').value;';
    $output .='var end = document.getElementById(\'destination\').value;';
    $output .='if(start == "" || end == ""){ alert("Please enter start and end points of your destination."); return false;}';
    $output .='jQuery(\'#directions\').html(\'<center><br/><img src="' . plugins_url('google-distance-calculator/images/loader.gif') . '" alt="Loading Directions" title="Loading Directions"/></center>\');';
    $output .='jQuery.post(\'' . plugins_url('/mkgd-ajax-handler.php', __FILE__) . '\', {origin: start, destination: end, language: \'' . get_option('mkgd_language', 'en') . '\', units: \'' . get_option('mkgd_units', 'metric') . '\'}, function(data) {';
    $output .='  jQuery(\'#directions\').html(data);';
    $output .='}); });';

    $output .='/*
     * Load the google map
     */ 
    function initialize() {
      directionsDisplay = new google.maps.DirectionsRenderer();';
    $output .='var specific_location = new google.maps.LatLng(' . get_option('mkgd_latitude', '43.6525') . ', ' . get_option('mkgd_longitude', '-79.3816667') . ');
      var mapOptions = {
        zoom:7,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: specific_location
      }
      map = new google.maps.Map(document.getElementById(\'mkgd-map-canvas\'), mapOptions);
      directionsDisplay.setMap(map);
    }
           
  </script>
      </div>';
    return $output;
}

/*
 * Add Shortcode Support
 */

function mkgd_shortcode($atts) {
    return mkgd_initialize();
}

add_shortcode('MKGD', 'mkgd_shortcode'); // Add shortcode [MKGD]

/*
 * Include Admin
 */
require_once 'mkgd-admin.php';


