var map;
var infowindow;
var global_police_place_details = [];
var global_hospital_place_details = [];

function initialize() {
  var pyrmont = current_position;

  map = new google.maps.Map(document.getElementById('map_canvas'), {
    center: current_position,
    zoom: 15
  });

  var request_police = {
    location: pyrmont,
    radius: 5000,
    types: ['police']
  };

  var request_hospital = {
    location: pyrmont,
    radius: 5000,
    types: ['hospital']
  };

  var markerMe = new google.maps.Marker({
      position: current_position,
      map: map
  });

  infowindow = new google.maps.InfoWindow();
  var service = new google.maps.places.PlacesService(map);
  if(mtype == 'panic') { 
    service.nearbySearch(request_police, callback_police); 
  }else if(mtype == 'sos') {
    service.nearbySearch(request_hospital, callback_hospital);
  }else {
    service.nearbySearch(request_police, callback_police);
    service.nearbySearch(request_hospital, callback_hospital);
  }
}

function callback_police(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      createMarkerPolice(results[i]);
    }
  }
}

function callback_hospital(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      createMarkerHospital(results[i]);
    }
  }
}

function createMarkerPolice(place) {
  var placeLoc = place.geometry.location;
  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location,
    icon: domain_url + 'images/police.png'
  });

  var request = { reference: place.reference };
  var service = new google.maps.places.PlacesService(map);
  service.getDetails(request, function(details, status) {
    global_police_place_details.push(details);

    google.maps.event.addListener(marker, 'click', function() {
      details = find_current_phone(place.name);
      infowindow.setContent('<strong>Name: </strong>' + place.name + '<br>' + '<strong>Phone: </strong>' + details.formatted_phone_number);
      infowindow.open(map, this);
    });
  });
}

function createMarkerHospital(place) {
  var placeLoc = place.geometry.location;
  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location,
    icon: domain_url + 'images/hospital.png'
  });

  var request = { reference: place.reference };
  var service = new google.maps.places.PlacesService(map);
  service.getDetails(request, function(details, status) {
    global_hospital_place_details.push(details);

    google.maps.event.addListener(marker, 'click', function() {
      details = find_current_phone(place.name);
      console.log(global_hospital_place_details);
      infowindow.setContent('<strong>Name: </strong>' + place.name + '<br>' + '<strong>Phone: </strong>' + details.formatted_phone_number);
      infowindow.open(map, this);
    });
  });
}

function find_current_phone(place_name) {
  for(global_place_detail in global_police_place_details){
    current_detail = global_police_place_details[global_place_detail];
    if(current_detail != null && current_detail.name == place_name) return current_detail;
  }

  return {};
}

google.maps.event.addDomListener(window, 'load', initialize);