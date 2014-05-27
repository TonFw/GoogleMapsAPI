var map;
var infowindow;

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
    icon: 'images/police.png'
  });

  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(place.name);
    infowindow.open(map, this);
  });
}

function createMarkerHospital(place) {
  var placeLoc = place.geometry.location;
  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location,
    icon: 'images/hospital.png'
  });

  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(place.name);
    infowindow.open(map, this);
  });
}

google.maps.event.addDomListener(window, 'load', initialize);