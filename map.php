<?php
  $domain = '/GoogleMapsAPI/';
  error_reporting(0); 
  if(!isset($mtype)) $mtype = '';
?>

<!DOCTYPE html>
<html>
  <head>
  	<title> GoogleMaps API </title>
  	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

	<!-- MyStyles -->
	<link rel="stylesheet" type="text/css" href="<?php echo $domain;?>css/map.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $domain;?>css/style.css">
	<!-- /MyStyles -->

  <!-- jQuery -->
  <script type="text/javascript" src='<?php echo $domain;?>js/jq.min.js'></script>
  <!-- /jQuery -->

  <!-- GoogleScripts -->
  <script type="text/javascript"
    src="http://maps.googleapis.com/maps/api/js?sensor=true&v=3.exp&libraries=places">
  </script>
  <!-- /GoogleScripts -->

  <!-- PreSet MAPs -->
  <script type="text/javascript">
    var mtype = '<?php echo $mtype; ?>';
    // Create the position
    var user_location = new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>); //Put here whatever came from the SMS
    var domain_url = <?php echo $domain; ?>;
  </script>
  <!-- /PreSet MAPs -->

	<!-- FrameworksScripts -->
	<!-- /FrameworksScripts -->

	<!-- MyScripts -->
	<script type="text/javascript" src='<?php echo $domain;?>js/app.js'></script>
	<script type="text/javascript" src='<?php echo $domain;?>js/map.js'></script>
	<!-- /MyScripts -->
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  </head>
  <body onload="initialize()">
    <div id="map_canvas"></div>
  </body>
</html>