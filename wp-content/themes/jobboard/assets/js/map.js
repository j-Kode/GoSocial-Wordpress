
"use_strict";

// Google map functions
function initialize() {
	var latlng = new google.maps.LatLng( gmaps.latitude, gmaps.longitude ),
		zoom = parseInt(gmaps.zoom),
		target = gmaps.target;
		
	var mapOptions = {
		center: latlng,
		zoom: zoom,
		scrollwheel: false,
	};
	
	var map = new google.maps.Map(document.getElementById(target), mapOptions);
	
	var marker = new google.maps.Marker({
		position: latlng,
		map: map,
	});
}
google.maps.event.addDomListener(window, 'load', initialize);
    	