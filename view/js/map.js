var map;
var map_id; // "map_canvas"
var map_zoom; // 6
var map_center_lat; // 48.583 Strasbourg
var map_center_lng; // 7.750
var styles;
var markersArray = [];
var geojson; // geojson objet from http://hostname/swingmap/controller/geojson.php?device=&server=
var infoWindow = new google.maps.InfoWindow;
var markerStyleOption; // color, icon, svg, circle

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
	/* old method to get the markers
    // Create a <script> tag and set the USGS URL as the source.
    var script = document.createElement('script');
    script.src = geojson;
    document.getElementsByTagName('head')[0].appendChild(script);
	*/
}

// clear markers
function clearOverlays() {
	for (var i = 0; i < markersArray.length; i++ ) {
		markersArray[i].setMap(null);
	}
	markersArray = [];
}

// Loop through the results array and place a marker for each
// set of coordinates.
function setMarkers(geojson){
    for (var i = 0; i < geojson.features.length; i++) {
		// Get user
		var User = geojson.features[i];
		// User's informations
		addMarker(User);
    }
	if(geojson.features.length > 0) {
		infoWindow.open(map, markersArray[Math.floor((Math.random()*i))]);
	}
	if(markersArray) {
		for(i in markersArray) {
			markersArray[i].setMap(map);
		}
	}
}

// function for adding a marker
function addMarker(User) {
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
		icon: markerStyle(markerStyleOption, device)	// option: circle size depends on volume getCircle(volume,device)
	});
	// push marker to the markersArray
	markersArray.push(marker);
	// Content of infoWindow
	var contentString = '<div id="infoWindow"><p>coords: [' + lat + ', ' + lng + ']</p><p>time: ' + time + '</p><p>ip: ' + ip + '</p><p>device: ' + device + '</p><p>idClient: ' + idClient + '</p><p>idServer: ' + idServer + '</p><p>volume: ' + volume + '</p></div>';
	
	bindInfoWindow(marker, map, infoWindow,contentString);	
}

// Bind the infoWindows to the markers
function bindInfoWindow(marker, map, infoWindow, contentString) {
	google.maps.event.addListener(marker, 'mouseover', function() {
		infoWindow.setContent(contentString);
		infoWindow.open(map, marker);
	});
	/*google.maps.event.addListener(marker, 'mouseout', function() {
		infoWindow.close();
	});*/
}

function markerStyle(markerStyleOption, device) {
	switch(markerStyleOption) {
		case "color":
			return colorMarker(device);
			break;
		case "icon":
			return iconMarker(device);
			break;
		case "svg":
			return svgMarker(device,map_zoom);
			break;
	}
}

// Select Marker's color
function colorMarker(device) {
	switch(device) {
		case "ios":
			return 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
			break;
		case "android":
			return 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
			break;
		case "wp":
			return 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
			break;
		case "server":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/c/c1/AWS_Simple_Icons_Non-Service_Specific_Traditional_Server.svg',null, null, null, new google.maps.Size(24,24));
			break;
	}
}

// png icon
function iconMarker(device) {
	switch(device) {
		case "ios":
			return 'http://localhost/swingmap/view/images/ios.png';
			break;
		case "android":
			return 'http://localhost/swingmap/view/images/android.png';
			break;
		case "wp":
			return 'http://localhost/swingmap/view/images/windows.png';
			break;
		case "server":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/c/c1/AWS_Simple_Icons_Non-Service_Specific_Traditional_Server.svg',null, null, null, new google.maps.Size(24,24));
			break;
	}
}

// svg icon
function svgMarker(device,map_zoom) {
	switch(device) {
		case "ios":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/8/84/Apple_Computer_Logo_rainbow.svg',null, null, null, new google.maps.Size(500*0.05*map_zoom/6,550*0.05*map_zoom/6));
			break;
		case "android":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/f/f1/Android_sample.svg',null, null, null, new google.maps.Size(262*0.1*map_zoom/6,372*0.1*map_zoom/6));
			break;	
		case "wp":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/6/6c/Windows_Phone_7.5_logo.svg',null, null, null, new google.maps.Size(44*0.5*map_zoom/6,44*0.5*map_zoom/6));
			break;
		case "server":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/c/c1/AWS_Simple_Icons_Non-Service_Specific_Traditional_Server.svg',null, null, null, new google.maps.Size(24*map_zoom/6,24*map_zoom/6));
			break;
	}
}

// Marker's circle style
function getCircle(volume,device) {
	var _color;
	switch(device) {
		case "ios":
			_color = 'red';
			break;
		case "android":
			_color = 'green';
			break;
		case "wp":
			_color = 'blue';
			break;
		case "server":
			_color = 'orange';
			break;
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