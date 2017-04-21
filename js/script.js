window.onload=function() {
	var myForm = $('#myForm');

	myForm.submit(function() {
		// set values
		var streetAd = $('#streetAd').val();
		var city = $('#city').val();
		var state = $('#state').val();
		var zip = $('#zip').val();
		var search = $('#search').val();
		var locationRadius = $('#locationRadius').val();

		// ajax call to get and set latitude/longitude
		$.ajax({
			url: "assets/submit-location.php",
			type: "POST",
			data: {
				streetAd: streetAd,
				city: city,
				state: state,
				search: search,
				zip: zip
			},
			success: function(data) {
			 	var lat = getLat(data);
			 	var lng = getLng(data);

				//ajax call to get videos
				$.ajax({
					url: 'assets/submit-video.php',
					type: "POST",
					data: {
						search: search,
						lat: lat,
						lng: lng,
						locationRadius: locationRadius
					},
					success: function(data) {
						var map;
						var youtubeJSON = JSON.parse(data);
						initMap(lat,lng,youtubeJSON, youtubeJSON.length);
					}
				})
			 }
		});
		return false;
	});
}

//create map
function initMap(lat, lng, youtubeJSON, length) {
	map = new google.maps.Map(document.getElementById('map'), {
		zoom: 4,
		center: new google.maps.LatLng(lat,lng),
		mapTypeId: 'terrain'
	});

	//create markers
	for (var i = 0; i < length; i++) {
		var latLng = new google.maps.LatLng(youtubeJSON[i][2],youtubeJSON[i][3]);
		var marker = new google.maps.Marker({
			position: latLng,
			map: map,
			title: youtubeJSON[i][0],
			url: "https://youtu.be/"+youtubeJSON[i][1]
		});
		google.maps.event.addListener(marker, 'click', function() {
			window.open(this.url);
    });
	}
}

// parse json and pull lat
function getLat(json) {
	var parsedLocation = JSON.parse(json);
	var lat = parsedLocation.results[0].geometry.location.lat.toString();
	return lat;
}

// pasre json and pull lng
function getLng(json) {
	var parsedLocation = JSON.parse(json);
	var lng = parsedLocation.results[0].geometry.location.lng.toString();
	return lng;
}
