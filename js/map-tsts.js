/* Simple map centralized with marker */
function initialize() {
	var mapOptions = {
		center: current_position,
		zoom: 12, // Increase to be more specific
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

	// To add the marker to the map, use the 'map' property
	var marker = new google.maps.Marker({
	    position: current_position,
	    map: map,
	    title:"Hello World!"
	});
}

/* ADD ANIMATE
// The following example creates a marker in Stockholm, Sweden
// using a DROP animation. Clicking on the marker will toggle
// the animation between a BOUNCE animation and no animation.

var stockholm = new google.maps.LatLng(59.32522, 18.07002); // Centralized at
var parliament = new google.maps.LatLng(59.327383, 18.06747); // Marked at
var marker;
var map;

function initialize() {
  var mapOptions = {
    zoom: 13,
    center: stockholm
  };

  map = new google.maps.Map(document.getElementById('map_canvas'),
          mapOptions);

  marker = new google.maps.Marker({
    map:map,
    draggable:true,
    animation: google.maps.Animation.DROP,
    position: parliament
  });
  google.maps.event.addListener(marker, 'click', toggleBounce);
}

function toggleBounce() {

  if (marker.getAnimation() != null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}

google.maps.event.addDomListener(window, 'load', initialize);
*/

/* ADD ICON
// This example adds a marker to indicate the position
// of Bondi Beach in Sydney, Australia
function initialize() {
  var mapOptions = {
    zoom: 12,
    center: new google.maps.LatLng(-33, 151)
  }
  var map = new google.maps.Map(document.getElementById('map_canvas'),
                                mapOptions);

  var image = 'images/map_azure.png';
  var myLatLng = new google.maps.LatLng(-33.890542, 151.274856);
  var beachMarker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      icon: image
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
*/