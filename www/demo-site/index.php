<!DOCTYPE html>
<html>
<head>
	<p style="font-size: 30pt; text-align:center"> POLLUTANTS OBSERVATORY </p>
	<link rel="stylesheet" type="text/css" href="http://api-site/styles.css">

</head>
<body>
	<style>
	    body{background-image: url("factory.jpg");}
		.bgbox{background-color: orange;}
	</style>
	<script src="http://api-site/json_queries.js"></script>
	<script src="http://demo-site/map_queries.js"></script>
	<script> 
		var map,heatmap,infowindow,pointArray,heatMapData;
		function initMap()
		{
			//Create map
			var uluru = {lat: 38.50, lng: 23.71};
			map = new google.maps.Map(document.getElementById('map'), {
				zoom: 6,
				center: uluru
			});

			//Initialize heatmap gradient
			var gradient =
			[
			  'rgba(0, 255, 255, 0)',
			  'rgba(0, 255, 255, 1)',
			  'rgba(0, 191, 255, 1)',
			  'rgba(0, 127, 255, 1)',
			  'rgba(0, 63, 255, 1)',
			  'rgba(0, 0, 255, 1)',
			  'rgba(0, 0, 223, 1)',
			  'rgba(0, 0, 191, 1)',
			  'rgba(0, 0, 159, 1)',
			  'rgba(0, 0, 127, 1)',
			  'rgba(63, 0, 91, 1)',
			  'rgba(127, 0, 63, 1)',
			  'rgba(191, 0, 31, 1)',
			  'rgba(255, 0, 0, 1)'
			]

			//Initialize Stations (Markers and Infowindows
			infowindow = get_stations(apikey,map);
			
			//Get heatmap points
			heatMapData = init_heatmap(apikey);
			pointArray = new google.maps.MVCArray(heatMapData);
			
			//Initialize heatmap
			heatmap = new google.maps.visualization.HeatmapLayer
			({
				data: pointArray,
				radius: 50,
				gradient: gradient,
				opacity: 0.5,
				map: map
			});
		}

	</script>

	<p style="font-size: 20pt; text-align:center"> MAP VISUALIZATION </p>
	<div style="width: 98%; height: 450px; display: table; border: 2px solid black; padding: 4px; margin: auto;">
	    <div class="bgbox" style="display: table-row">
			<div align="Center" style="width: 15%; display: table-cell; margin: auto;">
			
				<form id="f0" >
					<strong>Pollutant:</strong><br />
					<select id="pol" >
						<option value="CO" selected>CO</option>
						<option value="SO2">SO2</option>
						<option value="NO2">NO2</option>
						<option value="Smoke">Smoke</option>
						<option value="NOX">NOX</option>
						<option value="O3">O3</option>
						<option value="NO">NO</option>
						<option value="PM10">PM10</option>
					</select><br />
				</form>
				
				<form id="f3" >
					<strong>Initial Date:</strong><br />
					Year:<br />
					<input type="text" id="start_year" value="2004"><br />
					Month:<br />
					<input type="text" id="start_month" value="01"><br />
					Day:<br />
					<input type="text" id="start_day" value="01"><br />
					Hour:<br />
					<input type="text" id="hour" value="9"><br />
					<strong>Final Date:</strong><br />
					Year:<br>
					<input type="text" id="end_year" value="2014"><br />
					Month:<br />
					<input type="text" id="end_month" value="12"><br />
					Day:<br />
					<input type="text" id="end_day" value="30"><br />
				</form>
				<button onclick = "show_readings(apikey,infowindow,pointArray)">Submit</button>
				
			</div>
			
			<div id="map" style="display: table-cell; border: 2px solid black;">
			</div>
		
			<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBRgod6kGjmZgN6IQV9YB1JtWU4-yG4Zs&libraries=visualization&callback=initMap"
			async defer></script>
		</div>
	</div>
	
	</br></br></br></br></br></br></br></br>
	
	<div style="display: table;  margin: auto;">
		<div style="display: table-row">
			<div style="width:100px; display: table-cell;"></div>
			<div class="bgbox" id="api_form" align="Center" style="width: 70%; border: 2px solid black; display: table-cell; ">
			</div>
			<div style="width:100px; display: table-cell;"></div>
		</div>
	</div>
	
	<script>
		//Print default query forms using apikey
		apikey = "9264e44b706543ef1ffb01cc760c16ef";
		print_form("api_form",apikey);
	</script>

</body>
</html>