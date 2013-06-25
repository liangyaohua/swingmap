var map;
var map_id; // "map_canvas"
var map_zoom; // 6
var map_center_lat; // 48.583 Strasbourg
var map_center_lng; // 7.750
var geojson; // "http://hostname/swingmap/model/genjson.php?device=&server="
var infoWindow = new google.maps.InfoWindow;
var styles;

// Google Map initialization 
function initialize() {
    map = new google.maps.Map(document.getElementById(map_id), {
		zoom: map_zoom,
		center: new google.maps.LatLng(map_center_lat, map_center_lng),
		styles: styles,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		mapTypeControlOptions: {	
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
			position: google.maps.ControlPosition.TOP_RIGHT
		}

    });
	
    // Create a <script> tag and set the USGS URL as the source.
    var script = document.createElement('script');
    script.src = geojson;
    document.getElementsByTagName('head')[0].appendChild(script);
}

// Loop through the results array and place a marker for each
// set of coordinates.
function setMarkers(results){
    for (var i = 0; i < results.features.length; i++) {
		// Get user
		var User = results.features[i];
		// User's informations
		var lat = User.geometry.coordinates[1];
		var lng = User.geometry.coordinates[0];
		var time = User.properties.time;
		var ip = User.properties.ip;
		var device = User.properties.device;
		var idClient = User.properties.idClient;
		var idServer = User.properties.idServer;
		var volume = User.properties.volume;
		// Create marker
		var latLng = new google.maps.LatLng(lat,lng);
		var marker = new google.maps.Marker({
			position: latLng,
			map: map,
			icon: getCircle(volume, device)
		});
		// Content of infoWindow
		var contentString = '<p>cordinates: [' + lat + ', ' + lng + ']</p><p>time: ' + time + '</p><p>ip: ' + ip + '</p><p>device: ' + device + '</p><p>idClient: ' + idClient + '</p><p>idServer: ' + idServer + '</p><p>volume: ' + volume + '</p>';
	
		bindInfoWindow(marker, map, infoWindow,contentString);
    }
	alert("Total users: " + results.features.length);
}

// Bind the infoWindows to the markers
function bindInfoWindow(marker, map, infoWindow, contentString) {
	google.maps.event.addListener(marker, 'mouseover', function() {
		infoWindow.setContent(contentString);
		infoWindow.open(map, marker);
	});
	google.maps.event.addListener(marker, 'mouseout', function() {
		infoWindow.close();
	});
}

// Marker's circle style
function getCircle(volume,device) {
	var _color;
	if (device == "ios") {
		_color = 'red';  
	} else if(device == "android") {
		_color = 'green';
	} else {
		_color = 'orange';
	}
	return {
		path: google.maps.SymbolPath.CIRCLE,
		fillColor: _color,
		fillOpacity: .8,
		scale: Math.sqrt(volume/Math.PI)/50,
		strokeColor: 'white',
		strokeWeight: 0.1
	};
}