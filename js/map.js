var infowindow;
var map;

//This is the code to initialize and center the map on the Welty House, as well as to add the icons
//for the search
function initialize() {

  // Options the SetUp the map to be rendered
  map_options = {
      center : user_location,
      zoom : 15,
      mapTypeId : google.maps.MapTypeId.ROADMAP
  };

  // Set where (ON THE HTML) to render the map with the options
  map = new google.maps.Map(document.getElementById("map_canvas"), map_options);
  iconMe = '';
  positionMe = '';
  markedMe = markOn(map, user_location, iconMe, positionMe);

  // Perimeto
  pyrmont = user_location;
  var police_request = {
    location: pyrmont,
    radius: 5000,
    types: ['police']
  };
  var request_hospital = {
    location: pyrmont,
    radius: 5000,
    types: ['hospital']
  };

  // Search elements
  infowindow = new google.maps.InfoWindow();
  var service = new google.maps.places.PlacesService(map);
  if(mtype == 'panic') { 
    service.nearbySearch(request_police, search_place_callback); 
  }else if(mtype == 'sos') {
    service.nearbySearch(request_hospital, search_place_callback);
  }else {
    service.nearbySearch(request_police, search_place_callback);
    service.nearbySearch(request_hospital, search_place_callback);
  }
}

// map = google.maps.Map
// at = google.maps.LatLng
// icon = URL of the image
function markOn(map, at, icon) {
  return new google.maps.Marker({
      position: at,
      map: map,
      icon: icon
  });  
}

/**
  * Called when the place search is end.
  * Mark the places on the result.
**/
function search_place_callback(results, status) {
  console.log(results);
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      var place = results[i];
      create_place(place);
    }
  }
}

// Create the place with its options
function create_place(place) {
  var placeLoc = place.geometry.location;
      
  // Mark the place on the map
  marker = markOn(map, placeLoc, case_icon(place.types[0]));

  // Event click on the map
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent('<strong>Name: </strong>' + place.name + '<br>' + '<strong>Phone: </strong>' + place.formatted_phone_number);
    infowindow.open(map, this);
  });
}

// Switch case the Icon per Type
function case_icon(type) {
  switch(type) {
    case 'police':
      return domain_url+'images/police.png';
    break;
    case 'hospital':
      return domain_url+'images/hospital.png';
    break;
    default:
      return '';
    break;
  }
}

//adding the map to the page after the DOM is loaded
google.maps.event.addDomListener(window, 'load', initialize);