<?php
include("init.html");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>

<?php if (login_check($mysqli)) : ?>
<h2 align=center>Registration results</h2>
<br /><br />

<?php
$code = $_POST["code"];
$name = $_POST["name"];
$lat = $_POST["lat"];
$lon = $_POST["lon"];

$query = "INSERT INTO station VALUES (null,'$code','$name','$lat','$lon');";
$result = quick_query($query);

if ($result) echo "<strong>Successful station registration.</strong>";
else echo "<strong>There was a problem with the station registration.</strong>";

echo "<br /><br />Returning to admin page in 3 seconds...";
header("Refresh: 3;url=admin_page.php");
die();

?>
<?php else : ?>
	<p>
		<span class="error">You do not have access rights to this page.</span> Please <a href="index.php">sign in.</a>
	</p>
<?php endif; ?>
</body>
</html>