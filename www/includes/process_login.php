<?php
include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); //starting a PHP session.

if (isset($_POST['email'], $_POST['p']))
{
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['p'];
	
	//md5 hash password
	$salt = "aB1cD2eF3G";
	$password = md5($salt.$password);

    if (login($email, $password, $mysqli) == true)
	{
		if ($stmt = $mysqli->prepare("SELECT admin FROM members WHERE id = ? LIMIT 1"))
		{
			$user_id = $_SESSION['user_id'];
			$stmt->bind_param('i', $user_id);
			$stmt->execute();   // Execute the prepared query.
			$stmt->store_result();

			if ($stmt->num_rows == 1)
			{
				// If the user exists get variables from result.
				$stmt->bind_result($admin);
				$stmt->fetch();
				// Login success 
				if ($admin==1)
					header('Location: http://api-site/');
				else
					header('Location: http://api-site/user_page.php');
			}
			else
			{
				// User does not exist
				header('Location: http://api-site?error=1');
			}
			
			exit();
		}
		else
		{
			// Statement failed 
			header('Location: http://api-site?error=2');
			exit();
		}
	}
	else
	{
		// Login failed
		header('Location: http://api-site?error=3');
		exit();
	}
}
else
{
    // The correct POST variables were not sent to this page
    header('Location: http://api-site/error.php?error=4');
    exit();
}
