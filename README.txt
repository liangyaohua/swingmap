SwingMap V1.0
-------------
swingmap
	- index.php			
	- config.php	// parameters configuration
	- model
		- db_connection.php	// database connection
		- get_geojson.php	// function of generating the geojson object
	- view
		- swingmap.htm		// interface	
		- js
			- swingmap.js	// map functions 
			- jquery-1.10.1.min.js
			- bootstrap.min.js
			- bootstrap-datetimepicker.min.js
		- css
			- swingmap.css
			- bootstrap.min.css
			- bootstrap-datetimepicker.min.css
		- img
	- controller
		- geojson.php	// get the requested geojson
	- simulator
		- index.php
		- simu.bat		// simulator script file for windows 
		- data
			- geojson_example.json
			- swingmap_sql_dump.sql

Installation:
1.	copy the swingmap folder into your www directory
2.	import the database with swingmap_sql_dump.sql

Test:
1.	Insert datas with swingmap/simulator/index.php or simu.bat(for windows)
	ex: swingmap/simulator/index.php?max= a number	Default: max=100
2	Show map swingmap/index.php
	ex: swingmap/index.php

DEMO: http://www.liangyaohua.com/swingmap
	
Any questions, please email to yliang@swingmobility.com or alex19891013@gmail.com
