SwingMap V1.0
-------------
swingmap
	- index.php		// parameters	
	- config.php	// map configuration
	- model
		- db_connection.php	// database connection info
		- get_geojson.php	// function of generating the geojson object
	- view
		- map.htm		// interface, set the parameters for the map functions	
		- js
			- map.js	// map functions 
			- jquery.js
		- css
			- map.css
		- images
	- controller
		- geojson.php	// get the requested geojson
	- simulator
		- index.php
		- simu.bat		// simulator script file for windows 
		- data
			- geojson_example.json
			- swingmap_sql_dump.sql
			
Installation:
1.	copy the swingmap folder into www directory
2.	import the database with swingmap_sql_dump.sql

Test:
1.	Insert datas with swingmap/simulator/index.php or simu.bat(executable file)
	ex: swingmap/simulator/index.php?max= a number	Default: max=100
2	Show map swingmap/index.php
	ex: swingmap/index.php

DEMO: http://www.liangyaohua.com/swingmap
	
Any questions, please email to yliang@swingmobility.com or alex19891013@gmail.com
