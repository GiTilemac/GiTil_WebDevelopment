<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$apikey = $_GET['apikey'];
$conn2 = new mysqli(HOST,USER,PASSWORD,DATABASE);
$conn2->set_charset("utf8");
$user_auth = $conn2->query("
	SELECT admin
	FROM members
	WHERE apikey='$apikey';
");

if (mysqli_num_rows($user_auth))
{
	$user_row = $user_auth->fetch_array(MYSQLI_ASSOC);

	if ($user_row["admin"])
	{	
		$conn = new mysqli(HOST,USER,PASSWORD,DATABASE);
		$conn->set_charset("utf8");
		$result = $conn->query("
			SELECT 	apikey, (query1count+query2count+query3count) AS total_queries
			FROM members
			ORDER BY total_queries DESC
			LIMIT 10;
		");

		$outp = "[";
		while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
			if ($outp != "[") {$outp .= ",";}
			$outp .= '{"apikey":"' . $rs["apikey"] . '",';
			$outp .= '"total_queries":"'.$rs["total_queries"] . '"}';
		}		
		$outp .="]";

		$conn->close();

		echo "$outp";
	}
	else echo 'Access is not allowed to users without admin rights.';
}
else if (mysqli_num_rows($user_auth) == 0)
{
	echo 'Wrong API key.';
}
else echo 'Unknown error with the API key.';
?>