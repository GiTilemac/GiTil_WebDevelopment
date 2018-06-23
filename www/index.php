<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
include("init.html");
?>

<strong>Homepage</strong> &gt 
<?php
$admin = admin_check($mysqli);
$user = login_check($mysqli);

if ($admin)
{
	echo '<p>You are signed in as ' . htmlentities($_SESSION['username']) . '.</p>';
	echo '<p>Do you wish to sign out? <a href="includes/logout.php">Sign Out</a>.</p>';
	echo '<br /><a href="admin_page.php">API Administrator Page</a><br /><a href="user_page.php">API User Page</a><br />';
}
else if ($user)
{
	echo '<p>You are signed in as ' . htmlentities($_SESSION['username']) . '.</p>';
	echo '<p>Do you wish to sign out? <a href="includes/logout.php">Sign Out</a>.</p>';
	echo '<a href="user_page.php">API User Page</a><br />';
}
else
{
	echo "<br />";
	
	if (!empty($_GET))
	{
		switch ($_GET["error"])
		{
			case '1': echo "<strong>User does not exist</strong>";
			break;
			case '2': echo "<strong>Statement failed</strong>";
			break;
			case '3': echo "<strong>Login failed</strong>";
			break;
			case '4': echo "<strong>The correct POST variables were not sent to this page</strong>";
			break;
			default:
		}
	}
	
	echo "<p>If you do not have an account, please <a href='register.php'>register</a>.</p><br>";
	echo "User sign-in<br><br>";
	$nameErr = $emailErr = $pErr= "";
	$username = $email = $password = "";

	echo '<form action="includes/process_login.php" method="post" name="login_form">
		E-mail: <input type="text" name="email" id="email"/><br /><br />
		Password: <input type="password" name="p" id="p"/><br /><br />
	<input type="submit" name="submit" value="Sign in" /> 
	</form>';
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (empty($_POST["username"])) $nameErr = "Username is required";
		else
		{
			$username = test_input($_POST["username"]);
			if (!preg_match("/^[a-zA-Z ]*$/",$username)) $nameErr = "Only English characters and spaces are allowed";
		}

		if (empty($_POST["password"])) $pErr = "Password is required";
		else $password = test_input($_POST["password"]);

		if (empty($_POST["email"])) $emailErr = "E-mail is required";
		else
		{
			$email = test_input($_POST["email"]);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $emailErr = "Wrong E-mail form";
		}
	}
}
?>

</body>
</html> 