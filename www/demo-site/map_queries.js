//Initialize map Markers and their Infowindows using query1 to apidb
function get_stations(apikey,map)
{
	infowindow = [];
	marker = new Array();
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var arr = JSON.parse(xmlhttp.responseText);
			var i;

			for(i = 0; i < arr.length; i++)
			{
				var desc = "<strong>" + arr[i].name + "</strong>";
				
				var lat = parseFloat(arr[i].lat);
				var lon = parseFloat(arr[i].lon);
				
				marker[i] = new google.maps.Marker({
					position: new google.maps.LatLng(lat, lon),
					map: map,
					title: arr[i].name + '(' + arr[i].code + ')',
				});
				
				infowindow[i] = new google.maps.InfoWindow({
					content: desc,
					id: arr[i].code,
					name: arr[i].name,
					pos: marker[i].position
				});
				
				google.maps.event.addListener(marker[i], 'click', function (j)
				{
					return function()
					{
						infowindow[j].open(map,marker[j]);
					}
				}(i));
			}
		}
	}
	xmlhttp.open("GET","http://api-site/query1.php?apikey=" + apikey, true);
	xmlhttp.send();
	return infowindow;
}

//Initialize heatmap points using query1 to apidb (heatmap points = station coordinates)
function init_heatmap(apikey)
{	
	heatMapData = [];

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var arr = JSON.parse(xmlhttp.responseText);
			var i;

			for(i = 0; i < arr.length; i++)
			{
				var lat = parseFloat(arr[i].lat);
				var lon = parseFloat(arr[i].lon);
				
				heatMapData[i] = {
					location: new google.maps.LatLng(lat, lon),
					code: arr[i].code,
					weight: 0.01
				};
			}
		}
	}
	xmlhttp.open("GET","http://api-site/query1.php?apikey=" + apikey, true);
	xmlhttp.send();

	return heatMapData;
}

//Receive measurements from apidb depending on selected date(s) / pollutant update Infowindows and Heatmap
function show_readings(apikey,infowindow,pointArray)
{
	var x = document.getElementById("f3");
	
	start_year = x.elements[0].value;
	start_month = x.elements[1].value;
	start_day = x.elements[2].value;
	hour = x.elements[3].value;
	end_year = x.elements[4].value;
	end_month = x.elements[5].value;
	end_day = x.elements[6].value;
	
	type = document.getElementById('pol').value;
	code = "all";
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var arr = JSON.parse(xmlhttp.responseText);
			var i, j, k;
			var string;
			
			//absolute value
			if (start_year==end_year && start_month==end_month && start_day==end_day)
			{
				for (j=0; j<infowindow.length; j++)
				{
					for (i=0; i<arr.length; i++)
					{
						if (infowindow[j].id == arr[i].code)
						{
							//Update Infowindow with values
							string = "<strong>" + infowindow[j].name + " </strong> - " + type + "<br>Τιμή: " + arr[i].value;
							
							for (k=0; k<infowindow.length; k++)
							{
								//Update PointArray heatmap data entry
								var temp_code = pointArray.getAt(k).code;
								if (arr[i].code == temp_code)
								{
									var temp_loc = pointArray.getAt(k).location;
									pointArray.setAt(k,{location: temp_loc, code: temp_code, weight: arr[i].value});
									break;
								}
							}
							break;
						}
						else
						{
							//Update Infowindow and PointArray with "zero" value (no measurement)
							string = "<strong>" + infowindow[j].name + " </strong>";
							var temp_code = pointArray.getAt(j).code;
							var temp_loc = pointArray.getAt(j).location;
							pointArray.setAt(j,{location: temp_loc, code: temp_code, weight: 0.01});
						}
					}
					infowindow[j].setContent(string);
				}
			}
			//mean value and standard deviation for timespan
			else
			{
				for (j=0; j<infowindow.length; j++)
				{
					for (i=0; i<arr.length; i++)
					{
						if (infowindow[j].id == arr[i].code)
						{
							string = "<strong>" + infowindow[j].name + " </strong> - " + type + "<br>Μέση Τιμή: " + arr[i].avg + "<br>Διασπορά: " + arr[i].std;
							
							for (k=0; k<infowindow.length; k++)
							{
								var temp_code = pointArray.getAt(k).code;
								if (arr[i].code == temp_code)
								{
									var temp_loc = pointArray.getAt(k).location;
									pointArray.setAt(k,{location: temp_loc, code: temp_code, weight: arr[i].avg});
									break;
								}
							}
							break;
						}
						else
						{
							string = "<strong>" + infowindow[j].name + " </strong>";
							var temp_code = pointArray.getAt(j).code;
							var temp_loc = pointArray.getAt(j).location;
							pointArray.setAt(j,{location: temp_loc, code: temp_code, weight: 0.01});
						}
					}
					infowindow[j].setContent(string);
				}
			}
		}
	}
	
	if (start_year==end_year && start_month==end_month && start_day==end_day)
	{
		xmlhttp.open("GET","http://api-site/query2.php?apikey=" + apikey + "&type=" + type + "&code=" + code + "&year=" + start_year + "&month=" + start_month + "&day=" + start_day + "&hour=" + hour, true);
	}
	else
	{
		xmlhttp.open("GET","http://api-site/query3.php?apikey=" + apikey + "&type=" + type + "&code=" + code + "&start_year=" + start_year + "&start_month=" + start_month + "&start_day=" + start_day + "&end_year=" + end_year + "&end_month=" + end_month + "&end_day=" + end_day, true);
	}
	xmlhttp.send();
}
