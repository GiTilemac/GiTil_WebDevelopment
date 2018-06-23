<?php
include("init.html");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<?php if (login_check($mysqli)) : ?>
<?php

$f = $_POST["filename"];

$query = "DELETE FROM file WHERE filename='$f';";
$result = quick_query($query);

header("Refresh: 0;url=admin_page.php");
die();

?>
<?php else : ?>
	<p>
		<span class="error">You do not have access rights to this page.</span> Please <a href="index.php">sign in.</a>
	</p>
<?php endif; ?>

</body>
</html>