<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$apikey = $_GET['apikey'];
$conn2 = new mysqli(HOST,USER,PASSWORD,DATABASE);
$conn2->set_charset("utf8");
$user_auth = $conn2->query("
	SELECT id,username,query3count
	FROM members
	WHERE apikey='$apikey';
");

if (mysqli_num_rows($user_auth))
{
	$user_row = $user_auth->fetch_array(MYSQLI_ASSOC);
	$q3count = intval($user_row["query3count"]) + 1;
	$user_id = $user_row["id"];
	$conn2->query("
		UPDATE members
		SET query3count=$q3count
		WHERE id=$user_id;
	");
	
	$conn = new mysqli("localhost","root","","apidb");
	$conn->set_charset("utf8");

	$type = $_GET['type'];
	$code = $_GET['code'];
	$start_year = $_GET['start_year'];
	$start_month = $_GET['start_month'];
	$start_day = $_GET['start_day'];
	$end_year = $_GET['end_year'];
	$end_month = $_GET['end_month'];
	$end_day = $_GET['end_day'];

	
	if ($code == "all") $station_code = "";
	else $station_code = "code='$code' AND";

	$start_date = "'".$start_year."-".$start_month."-".$start_day."'";
	$end_date = "'".$end_year."-".$end_month."-".$end_day."'";
	$span_sql = "date>=".$start_date." AND date<=".$end_date;

	$result = $conn->query("
	SELECT ROUND(AVG(value),1) as avg,ROUND(STDDEV(value),1) as std,lat,lon,code
	FROM station
	LEFT JOIN measurement ON id=st_id
	LEFT JOIN pollutant ON type = pollutant.id
	WHERE pollutant.name='$type'
	AND $station_code $span_sql
	GROUP BY code;
	");

	$outp = "[";
	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
		if ($outp != "[") {$outp .= ",";}
		$outp .= '{"avg":"' . $rs["avg"] . '",';
		$outp .= '"std":"' . $rs["std"] . '",';
		$outp .= '"lat":"'.$rs["lat"] . '",';
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