<?php
include_once 'db_connect.php';
include_once 'psl-config.php';

$error_msg = "";
$admin = 0;

if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
	// Sanitize and validate the data passed in
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	$email = filter_var($email, FILTER_VALIDATE_EMAIL);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		//Not a valid email
		$error_msg .= '<p class="error">The E-mail address you have provided is not valid.</p>';
	}
	$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
	
	$prep_stmt = "SELECT * FROM members";
	$stmt = $mysqli->prepare($prep_stmt);
	if ($stmt) {
		$stmt->execute();
		$stmt->store_result();
		
		if ($stmt->num_rows == 0) {
			$admin = 1;
		}
	}
		
	// Username validity and password validity have been checked client side.
	$prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";
	$stmt = $mysqli->prepare($prep_stmt);
	
	//check existing email
	if ($stmt) {
		$stmt->bind_param('s',$email);
		$stmt->execute();
		$stmt->store_result();
		
		if ($stmt->num_rows ==1) {
			// A user with this email address already exists
			$error_msg .= '<p class="error">A user with this E-mail address already exists.</p>';
			$stmt->close();
		}
	} else {
		$error_msg .= '<p class="error">Database error line 39</p>';
		$stmt->close();
	}
	
	// check existing username
	$prep_stmt = "SELECT id FROM members WHERE username = ? LIMIT 1";
	$stmt = $mysqli->prepare($prep_stmt);
	
	if ($stmt) {
		$stmt->bind_param('s',$username);
		$stmt->execute();
		$stmt->store_result();
		
		if ($stmt->num_rows == 1) {
			// A user this username already exists
			$error_msg .= '<p class="error">A user with this name already exists</p>';
		}
	} else {
		$error_msg .= '<p class="error">Database error line 55</p>';
	}
	
	if(empty($error_msg)) {
		//md5 hash
		$salt = "aB1cD2eF3G";
		$password = md5($salt.$password);
		$apikey = md5($salt.rand());
		
		// Insert the new user into the database
		if ($insert_stmt = $mysqli->prepare("INSERT INTO members (username, email, password, apikey, admin) VALUES (?, ?, ?, ?, ?)")) {
			$insert_stmt->bind_param('ssssi', $username, $email, $password, $apikey, $admin);	
		    // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                header('Location: http://api-site/error.php?err=Registration failure: INSERT');
				exit();
            }
		}
	    header('Location: http://api-site/register_success.php');
		exit();
    }
}
