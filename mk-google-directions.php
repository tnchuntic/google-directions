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
  wp_register_script('mkgd-google-map-places', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places&language=' . get_option('mkgd_language', 'en'),array('jquery'),'',true);
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
?>
    <style>
    #mkgd-map-canvas{
      width: <?php echo get_option("mkgd_width", "500px")?>;
      height: <?php echo get_option("mkgd_height", "500px")?>;
    }
   </style>

    <div id="mkgd-wrap"><h4>Get Directions</h4>
    <div class="mkgd-form row-fluid">
      <div class="span12">
        <?php if(!get_option("mkgd_origin_hide")){?>
        <label for="origin">Origin</label>
        <?php $type_origin = 'text'; }else{$type_origin = 'hidden';}?>
        <input id="origin" name="origin" type="<?php echo $type_origin;?>" size="50" value="<?php echo get_option("mkgd_origin"); ?>" />
        
        <?php if(!get_option("mkgd_destination_hide")){?>
        <label for="destination">Destination</label>
        <?php $type_destination = 'text'; }else{$type_destination = 'hidden';}?>
        <input id="destination" name="destination" type="<?php echo $type_destination;?>" size="50" value="<?php echo get_option("mkgd_destination"); ?>" />
        <input type="button" onclick="calcRoute();" name="btnMkgdSubmit" id="btnMkgdSubmit" value="Submit" class="btn btn-warning  btn-normal"/>
      </div>
    </ul><!-- End .mkgd-form -->
    <div id="mkgd-map-canvas"></div><!-- End #mkgd-map-canvas -->
    <div id="directions"></div><!-- End #directions -->
  </div><!-- End #mkgd-wrap -->
  
  <script type="text/javascript">
    jQuery("#btnMkgdSubmit").click(function() {        
      var start = document.getElementById('origin').value;
      var end = document.getElementById('destination').value;
      if(start == "" || end == ""){ alert("Please enter start and end points of your destination."); return false;}        
      jQuery('#directions').html('<center><br/><img src="<?php plugins_url('google-distance-calculator/images/loader.gif'); ?>" alt="Loading Directions" title="Loading Directions"/></center>');
      jQuery.post('<?php echo plugins_url('/mkgd-ajax-handler.php', __FILE__); ?>', {origin: start, destination: end, language: '<?php echo get_option('mkgd_language', 'en');?>', units: '<?php echo get_option('mkgd_units', 'metric');?>'}, function(data) {
        jQuery('#directions').html(data);
      });
    });

    /*
     * Load the google map
     */ 
    function initialize() {
      directionsDisplay = new google.maps.DirectionsRenderer();
      var specific_location = new google.maps.LatLng(<?php echo get_option('mkgd_latitude', '43.6525'); ?>, <?php echo get_option('mkgd_longitude', '-79.3816667');?>);
      var mapOptions = {
        zoom:7,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: specific_location
      }
      map = new google.maps.Map(document.getElementById('mkgd-map-canvas'), mapOptions);
      directionsDisplay.setMap(map);
    }
           
  </script>
<?php
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


