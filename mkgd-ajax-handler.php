<?php
if ($_POST['origin'] != "" && $_POST['destination'] != "") {
  $origin = str_replace(' ', '+', $_POST['origin']);
  $destination = str_replace(' ', '+', $_POST['destination']);
  $language = $_POST['language'];
  $units = $_POST['units'];

  $url = "http://maps.googleapis.com/maps/api/directions/json?origin=" . $origin . "&destination=" . $destination . "&sensor=false&language=" . $language . '&units=' . $units;

// sendRequest
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_REFERER, 'http://yourdemolink.com/php/googlemaps-json/index.php');
  $body = curl_exec($ch);
  curl_close($ch);

  $json = json_decode($body);
//  echo "<pre>";print_r($json);echo "</pre>";

  if ($json->status != 'ZERO_RESULTS') {
    $legs = $json->routes[0]->legs[0];
    $drivingSteps = $json->routes[0]->legs[0]->steps;
    ?>
    <h4>Details</h4>
    <p>Distance between <strong><?php echo $legs->start_address; ?></strong> and <strong><?php echo $legs->end_address; ?></strong> is: <strong><?php echo $legs->distance->text; ?></strong></p>
    <p>Approx. time of journey: <strong><?php echo $legs->duration->text; ?></strong></p>
    
    <h4>Driving directions:</h4>
    <h5>Start: <?php echo $legs->start_address; ?></h5>
    <ul>
      <?php foreach ($drivingSteps as $drivingStep) { ?>
        <li><div class="dir-tt dir-tt-<?php echo $drivingStep->maneuver;?>"><img src="//maps.gstatic.com/tactile/directions/text_mode/maneuvers-2x.png" width="19" height="630" jstcache="0"></div><?php echo $drivingStep->html_instructions; echo '<span class="distance-time">'.$drivingStep->distance->text.' / '.$drivingStep->duration->text.'</span>';?></li>
        <?php
      }?>
      <h5>End: <?php echo $legs->end_address; ?></h5>
    <?php } else {
      echo "<h4 class=\"mkgd-error\">Google cannot find directions for the Origin addess that you entered.</h4>";
    }
    
  }
  ?>

