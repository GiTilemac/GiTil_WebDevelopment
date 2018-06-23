<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$apikey = $_GET['apikey'];
$conn2 = new mysqli(HOST,USER,PASSWORD,DATABASE);
$conn2->set_charset("utf8");
$user_auth = $conn2->query("
	SELECT id,username,query1count
	FROM members
	WHERE apikey='$apikey';
");

if (mysqli_num_rows($user_auth))
{
	$user_row = $user_auth->fetch_array(MYSQLI_ASSOC);
	$q1count = intval($user_row["query1count"]) + 1;
	$user_id = $user_row["id"];
	$conn2->query("
		UPDATE members
		SET query1count=$q1count
		WHERE id=$user_id;
	");
	
	$conn = new mysqli("localhost","root","","apidb");
	$conn->set_charset("utf8");
	$result = $conn->query("SELECT code,name,lat,lon FROM station");

	$outp = "[";
	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
		if ($outp != "[") {$outp .= ",";}
		$outp .= '{"name":"' . $rs["name"] . '",';
		$outp .= '"code":"' . $rs["code"] . '",';
		$outp .= '"lat":"'.$rs["lat"] . '",';
		$outp .= '"lon":"'.$rs["lon"] . '"}';
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