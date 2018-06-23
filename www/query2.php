<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$apikey = $_GET['apikey'];
$conn2 = new mysqli(HOST,USER,PASSWORD,DATABASE);
$conn2->set_charset("utf8");
$user_auth = $conn2->query("
	SELECT id,username,query2count
	FROM members
	WHERE apikey='$apikey';
");

if (mysqli_num_rows($user_auth))
{
	$user_row = $user_auth->fetch_array(MYSQLI_ASSOC);
	$q2count = intval($user_row["query2count"]) + 1;
	$user_id = $user_row["id"];
	$conn2->query("
		UPDATE members
		SET query2count=$q2count
		WHERE id=$user_id;
	");
		
	$conn = new mysqli("localhost","root","","apidb");
	$conn->set_charset("utf8");

	$type = $_GET['type'];
	$code = $_GET['code'];
	$year = $_GET['year'];
	$month = $_GET['month'];
	$day = $_GET['day'];
	$hour = $_GET['hour'];

	if ($code == "all") $station_code = "";
	else $station_code = "code='$code' AND";
	$result = $conn->query("
	SELECT lat,lon,value,code
	FROM station
	LEFT JOIN measurement ON id=st_id
	LEFT JOIN pollutant ON type = pollutant.id
	WHERE pollutant.name='$type'
	AND $station_code date='$year-$month-$day'
	AND hour='$hour';
	");

	$outp = "[";
	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
		if ($outp != "[") {$outp .= ",";}
		$outp .= '{"lat":"'.$rs["lat"] . '",';
		$outp .= '"value":"'.$rs["value"] . '",';
		$outp .= '"lon":"'.$rs["lon"] . '",';
		$outp .= '"code":"'.$rs["code"] . '"}';
	}
	$outp .="]";

	$conn->close();

	echo "$outp";
}
else if (mysqli_num_rows($user_auth) == 0)
{
	echo 'Wrong API key.';
}
else echo 'Unknown error with the API key.';
?>