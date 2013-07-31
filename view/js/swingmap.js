var map;
var map_id; // "map_canvas"
var map_zoom; // 6
var map_center_lat; // 48.583 Strasbourg
var map_center_lng; // 7.750
var styles; // map styles

var autoRefreshFreq; // 30000
var geojsonUrl; // http://hostname/controller/geojson.php
var imgUrl;	// http://hostname/view/img/

var geojson; // geojson obje
var markersArray = [];
var markerStyleOption; // color, icon, svg, circle
var infoWindow = new google.maps.InfoWindow;
var showInfoWindowFreq = 2000;

// Google Map initialization
function initialize() {
	map = new google.maps.Map(document.getElementById(map_id), {
		zoom: map_zoom,
		maxZoom: 9,
		minZoom: 3,
		center: new google.maps.LatLng(map_center_lat, map_center_lng),
		styles: styles,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		streetViewControl: false
	});
}

// Clear markers
function clearOverlays() {
	for (var i = 0; i < markersArray.length; i++ ) {
		markersArray[i].setMap(null);
	}
	markersArray = [];
}

// Loop through the results array and place a marker for each
// set of coordinates.
function setMarkers(geojson) {
	for (var i = 0; i < geojson.features.length; i++) {
		var User = geojson.features[i];
		var lat = User.geometry.coordinates[1];
		var lng = User.geometry.coordinates[0];
		if(lat != 0 && lng != 0)
			addMarker(User);
	}
	if(markersArray) {
		for(i in markersArray) {
			markersArray[i].setMap(map);
		}
	}
}

// Create and add marker
function addMarker(User) {
	var lat = User.geometry.coordinates[1];
	var lng = User.geometry.coordinates[0];
	var time = User.properties.time;
	var ip = User.properties.ip;
	var device = User.properties.device;
	var idDevice = User.properties.idDevice;
	var idClient = User.properties.idClient;
	var idServer = User.properties.idServer;
	var volume = User.properties.volume;
	
	// Create marker
	var latLng = new google.maps.LatLng(lat,lng);
	var marker = new google.maps.Marker({
		position: latLng,
		//optimized: false,
		icon: markerStyleOption=="circle"?getCircle(device,volume):markerStyle(markerStyleOption, device)
	});
	
	// push marker to the markersArray
	markersArray.push(marker);
	
	// Content of infoWindow
	var contentString = '<div id="infoWindow"><p>' + time + '</p><p>ip: ' + ip + '</p><p>device: ' + device + '</p><p>idDevice: ' + idDevice + '</p><p>idClient: ' + idClient + '</p><p>idServer: ' + idServer + '</p><p>volume: ' + volume + '</p></div>';
	
	bindInfoWindow(marker, map, infoWindow,contentString);
}
	
// Bind infoWindows to markers
function bindInfoWindow(marker, map, infoWindow, contentString) {
	google.maps.event.addListener(marker, 'click', function() {
		infoWindow.setContent(contentString);
		infoWindow.open(map, marker);
	});
}

// Random show infoWindow
function showInfoWindow() {
	return setInterval(function() {
		if(geojson.features.length > 0) {
			google.maps.event.trigger(markersArray[Math.floor((Math.random()*geojson.features.length))], 'click');
		}
	},showInfoWindowFreq);
}

// Markers style option
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

// color pin style
function colorMarker(device) {
	switch(device) {
		case "iPhone":
			return imgUrl + 'red-dot.png';
			break;
		case "iPad":
			return imgUrl + 'purple-dot.png';
			break;
		case "Android":
			return imgUrl + 'green-dot.png';
			break;
		case "W32":
			return imgUrl + 'blue-dot.png';
			break;
		case "Server":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/c/c1/AWS_Simple_Icons_Non-Service_Specific_Traditional_Server.svg',null, null, null, new google.maps.Size(24,24));
			break;
		case "Undetermined":
			return imgUrl + 'yellow-dot.png';
			break;
	}
}

// png icon
function iconMarker(device) {
	switch(device) {
		case "iPhone":
		case "iPad":
			return imgUrl + 'ios.png';
			break;
		case "Android":
			return imgUrl + 'android.png';
			break;
		case "W32":
			return imgUrl + 'windows.png';
			break;
		case "Server":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/c/c1/AWS_Simple_Icons_Non-Service_Specific_Traditional_Server.svg',null, null, null, new google.maps.Size(24,24));
			break;
		case "Undetermined":
			return imgUrl + 'yellow-dot.png';
			break;
	}
}

// svg icon
function svgMarker(device,map_zoom) {
	switch(device) {
		case "iPhone":
		case "iPad":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/8/84/Apple_Computer_Logo_rainbow.svg',null, null, null, new google.maps.Size(500*0.05*map_zoom/6,550*0.05*map_zoom/6));
			break;
		case "Android":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/f/f1/Android_sample.svg',null, null, null, new google.maps.Size(262*0.1*map_zoom/6,372*0.1*map_zoom/6));
			break;
		case "W32":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/6/6c/Windows_Phone_7.5_logo.svg',null, null, null, new google.maps.Size(44*0.5*map_zoom/6,44*0.5*map_zoom/6));
			break;
		case "Server":
			return new google.maps.MarkerImage('http://upload.wikimedia.org/wikipedia/commons/c/c1/AWS_Simple_Icons_Non-Service_Specific_Traditional_Server.svg',null, null, null, new google.maps.Size(24*map_zoom/6,24*map_zoom/6));
			break;
		case "Undetermined":
			return imgUrl + 'yellow-dot.png';
			break;
	}
}

// circle style
function getCircle(device,volume) {
	var _color;
	switch(device) {
		case "iPhone":
			_color = 'red';
			break;
		case "iPad":
			_color = 'purple';
			break;
		case "Android":
			_color = 'green';
			break;
		case "W32":
			_color = 'blue';
			break;
		case "Server":
			_color = 'black';
			break;
		case "Undetermined":
			_color = 'orange';
			break;
	}
	return {
		path: google.maps.SymbolPath.CIRCLE,
		fillColor: _color,
		fillOpacity: .8,
		scale: Math.sqrt(volume/Math.PI)/40,
		strokeColor: 'white',
		strokeWeight: 0.1
	};
}

// jQuery code for map control
$(function(){
	initialize();
	// ajax refresh markers
	$("#setMarkers").click(function(){
		$("#showtime").hide();
		var _device = $("#device").val();
		var _server = $("#server").val();
		var _interval = $("#interval").val();
		var _datetime = $("#datetime").val();
		markerStyleOption = $("#markerStyleOption").val();
		autoRefreshFreq = $("#frequency").val();
		map_zoom = map.getZoom();
		
		var date = new Date();
		var month = date.getMonth()>=9?(date.getMonth()+1):("0"+(date.getMonth()+1));
		var day = date.getDate()>=10?date.getDate():("0"+date.getDate());
		var hour = date.getHours()>=10?date.getHours():("0"+date.getHours());
		var minute = date.getMinutes()>=10?date.getMinutes():("0"+date.getMinutes());
		var second = date.getSeconds()>=10?date.getSeconds():("0"+date.getSeconds());
		var curtime = date.getFullYear() + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
		var endtime = _datetime==""?curtime:_datetime;
		
		var downloadUrl = geojsonUrl + "?device=" + _device + "&server=" + _server + "&interval=" + _interval + "&datetime=" + endtime;
		
		// ajax
		var xmlhttp;
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				clearOverlays();
				geojson = $.parseJSON(xmlhttp.responseText);
				setMarkers(geojson);
				$("#result").html("<br>Total users: " + geojson.features.length);
				$("#showtime").html(endtime).fadeIn().fadeOut().fadeIn();
			} else {
				$("#result").html("<br>loading...");
			}
		}
		xmlhttp.open("GET", downloadUrl, true);
		xmlhttp.send();
	});
	// auto refresh
	var AR;
	var IW;
	// play button start live mode
	$("#play").click(function(){
		$("#datetime").val("");
		$("#datetimepicker").fadeOut();
		$("#setMarkers").click();
		clearInterval(AR);
		clearInterval(IW);
		AR = autoRefresh();
		IW = showInfoWindow();
		// option change trigger
		$("#device,#server,#markerStyleOption,#interval").change(function(){$("#setMarkers").click()});
		$("#frequency").change(function(){
			clearInterval(AR);
			$("#setMarkers").click();
			AR = autoRefresh();
		});
	});
	// pause button stop live mode
	$("#pause").click(function(){
		clearInterval(AR);
		clearInterval(IW);
		$("#datetime").val("");
		$("#datetimepicker").fadeOut();
	});
	// playback button see the history recode
	$("#backward").click(function(){
		$("#pause").click();
		$("#datetimepicker").fadeIn();
	});
	// map control bar
	$("#map_control").hide();
	$("#show_control").click(function(){
		if($("#arrow").attr("class") == "icon-chevron-down") {
			$("#map_control").slideDown();
			$("#arrow").removeClass("icon-chevron-down").addClass("icon-chevron-up");
			// show the scrollbar
			$("html,body,#map_canvas").css("overflow","auto");
		} else {
			$("#map_control").slideUp();
			$("#arrow").removeClass("icon-chevron-up").addClass("icon-chevron-down");
			$("html,body,#map_canvas").css("overflow","hidden");
		}
	});
	// date time picker
	$('#datetimepicker').datetimepicker({
		language: 'en'
	});
	$("#datetimepicker").hide();
});
// auto refresh function
function autoRefresh() {
	return setInterval(function(){$("#setMarkers").click()},autoRefreshFreq);
}