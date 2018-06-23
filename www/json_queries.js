function json_query1(apikey)
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var arr = JSON.parse(xmlhttp.responseText);
			var i;
			var out = "<table>";
			out += "<tr><th>Station ID</th><th>Station Name</th><th>Latitude</th><th>Longtitude</th></tr>"
			for(i = 0; i < arr.length; i++) {
				out += "<tr><td>" +
				arr[i].code +
				"</td><td>" +
				arr[i].name +
				"</td><td>" +
				arr[i].lat  +
				"</td><td>" +
				arr[i].lon  +
				"</td></tr>";
			}
			out += "</table>";
			document.getElementById("q1").innerHTML = out; 
		}
	}
	xmlhttp.open("GET","http://api-site/query1.php?apikey=" + apikey, true);
	xmlhttp.send();
}

function json_query2(apikey)
{
	var x = document.getElementById("f1");
	
	type = x.elements[0].value;
	code = x.elements[1].value;
	year = x.elements[2].value;
	month = x.elements[3].value;
	day = x.elements[4].value;
	hour = x.elements[5].value;
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var arr = JSON.parse(xmlhttp.responseText);
			var i;
			var out = "<table>";
			out += "<tr><th>Station Name</th><th>Pollutant Measurement</th><th>Latitude</th><th>Longtitude</th></tr>"
			for(i = 0; i < arr.length; i++) {
				out += "<tr><td>" +
				arr[i].code +
				"</td><td>" +
				arr[i].value +
				"</td><td>" +
				arr[i].lat  +
				"</td><td>" +
				arr[i].lon  +
				"</td></tr>";
			}
			out += "</table>";
			document.getElementById("q2").innerHTML = out; 
		}
	}
	xmlhttp.open("GET","http://api-site/query2.php?apikey=" + apikey + "&type=" + type + "&code=" + code + "&year=" + year + "&month=" + month + "&day=" + day + "&hour=" + hour, true);
	xmlhttp.send();
}

function json_query3(apikey)
{
	var x = document.getElementById("f2");
	
	type = x.elements[0].value;
	code = x.elements[1].value;
	start_year = x.elements[2].value;
	start_month = x.elements[3].value;
	start_day = x.elements[4].value;
	end_year = x.elements[5].value;
	end_month = x.elements[6].value;
	end_day = x.elements[7].value;
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var arr = JSON.parse(xmlhttp.responseText);
			var i;
			var out = "<table>";
			out += "<tr><th>Station Name</th><th>Mean</th><th>Standard Deviation</th><th>Latitude</th><th>Longtitude</th></tr>"
			for(i = 0; i < arr.length; i++) {
				out += "<tr><td>" +
				arr[i].code  +
				"</td><td>" +
				arr[i].avg +
				"</td><td>" +
				arr[i].std +
				"</td><td>" +
				arr[i].lat  +
				"</td><td>" +
				arr[i].lon  +
				"</td></tr>";
			}
			out += "</table>";
			document.getElementById("q3").innerHTML = out; 
		}
	}
	xmlhttp.open("GET","http://api-site/query3.php?apikey=" + apikey + "&type=" + type + "&code=" + code + "&start_year=" + start_year + "&start_month=" + start_month + "&start_day=" + start_day + "&end_year=" + end_year + "&end_month=" + end_month + "&end_day=" + end_day, true);
	xmlhttp.send();
}

function print_form(elem_id,apikey)
{
	document.getElementById(elem_id).innerHTML =
	'<p style="font-size: 20pt; text-align:center"> QUERY DATABASE </p>' +
	'<div align="center" style="width: 100%;  ">' +
	
		'<label><strong>Measuring Stations:</strong></label><br />' +
		'<input type = "button"' +
		'	value = "Show"' +
		'	onclick = json_query1(\'' + apikey + '\')' +
		'></input><br />' +
		'<div id="q1"></div><br />' +
		
		'<label><strong>Pollutant value search:</strong></label>' +
		'<form id="f1" >' +
			'Pollutant type:<br>' +
			'<input type="text" id="type" value="CO"><br />' +
			'Station ID:<br />' +
			'<input type="text" id="code" value="all"><br />' +
			'Year:<br />' +
			'<input type="text" id="year" value="2014"><br />' +
			'Month:<br />' +
			'<input type="text" id="month" value="01"><br />' +
			'Day:<br />' +
			'<input type="text" id="day" value="25"><br />' +
			'Hour:<br />' +
			'<input type="text" id="hour" value="9"><br />' +
		'</form>' +
		'<button onclick = "json_query2(\'' + apikey + '\')">Submit query</button>' +
		'<div id="q2"></div><br />' +

		'<label><strong>Mean and standard devation of a pollutant value for a specific timespan:</strong></label>' +
		'<form id="f2" >' +
			'Pollutant type:<br>' +
			'<input type="text" id="type" value="CO"><br />' +
			'Station ID:<br />' +
			'<input type="text" id="code" value="all"><br />' +
			'<strong>Initial date:</strong><br />' +
			'Year:<br />' +
			'<input type="text" id="start_year" value="2004"><br />' +
			'Month:<br />' +
			'<input type="text" id="start_month" value="01"><br />' +
			'Day:<br />' +
			'<input type="text" id="start_day" value="01"><br />' +
			'<strong>Final date:</strong><br />' +
			'Year:<br />' +
			'<input type="text" id="end_year" value="2014"><br />' +
			'Month:<br />' +
			'<input type="text" id="end_month" value="12"><br />' +
			'Day:<br />' +
			'<input type="text" id="end_day" value="30"><br />' +
		'</form>' +
		'<button onclick = "json_query3(\'' + apikey + '\')">Submit query</button>' +
		'<div id="q3"></div><br />' +
	'</div>';
}

function json_sumquery(elem_id,apikey)
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var arr = JSON.parse(xmlhttp.responseText);
			
			var out = "<table>";
			out += "<tr><th>keys given</th><th>Query 1</th><th>Query 2</th><th>Query 3</th></tr>"
			for(i = 0; i < arr.length; i++) {
				out += "<tr><td>" +
				arr[i].apikeys +
				"</td><td>" +
				arr[i].sum1 +
				"</td><td>" +
				arr[i].sum2 +
				"</td><td>" +
				arr[i].sum3 +
				"</td></tr>";
			}
			out += "</table>";
			document.getElementById(elem_id).innerHTML = out;
		}
	}

	xmlhttp.open("GET","http://api-site/sum_query.php?apikey=" + apikey, true);
	xmlhttp.send();
}

function json_top10query(elem_id,apikey)
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var arr = JSON.parse(xmlhttp.responseText);

			var out = "<table>";
			out += "<tr><th>Ranking</th><th>API key</th><th>API Requests</th></tr>"
			for(i = 0; i < arr.length; i++) {
				out += "<tr><td>" +
				"#" + (i+1) +
				"</td><td>" +
				arr[i].apikey +
				"</td><td>" +
				arr[i].total_queries +
				"</td></tr>";
			}
			out += "</table>";
			document.getElementById(elem_id).innerHTML = out;
		}
	}

	xmlhttp.open("GET","http://api-site/top10query.php?apikey=" + apikey, true);
	xmlhttp.send();
}