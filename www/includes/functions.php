<?php
include_once 'psl-config.php';

function db_connect($err)
{
	$mysqli = new mysqli("localhost","root","","apidb");
	if ($mysqli->connect_error) header("Location: http://api-site/error.php?err=$err");
	$mysqli->set_charset("utf8");
    
	return $mysqli;
}

//Initializes session parameters and starts the PHP session
function sec_session_start()
{
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: http://api-site/error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
							  $cookieParams["path"],
							  $cookieParams["domain"],
							  $secure,
							  $httponly);
							  
    // Sets the session name to the one set above.
    session_name($session_name);
	
    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
}

//Keeps check of login attempts to avoid brute force attacks
function checkbrute($user_id, $mysqli)
{
    // Get timestamp of current time 
    $now = time();
 
    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);
 
	// Check if login limit for this user_id has been reached
    if ($stmt = $mysqli->prepare("SELECT time 
                             FROM login_attempts 
                             WHERE user_id = ? 
                            AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
 
        // Execute the prepared query. 
        $stmt->execute();
        $stmt->store_result();
 
        // If there have been more than 5 failed logins 
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
	} else {
    // Could not create a prepared statement
    header("Location: http://api-site/error.php?err=Database error: cannot prepare statement");
    exit();
    }
}

//
function login($email, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT id, username, password, apikey
								  FROM members
								  WHERE email = ?
								  LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password, $apikey);
        $stmt->fetch();
 
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts 
 
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked 
                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted. 
				if ($password == $db_password) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
					
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
					
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                "", 
                                                                $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = $db_password;
					$_SESSION['apikey'] = $apikey;
					
                    // Login successful.
                    return true;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    if (!$mysqli->query("INSERT INTO login_attempts(user_id, time) 
                                    VALUES ('$user_id', '$now')")) {
                        header("Location: http://api-site/error.php?err=Database error: login_attempts");
                        exit();
                    }
					
					return false;
                }
            }
        } else {
            // No user exists.
            return false;
		}
	} else {
        // Could not create a prepared statement
        header("Location: http://api-site/error.php?err=Database error: cannot prepare statement");
        exit();
    }
}

function login_check($mysqli) {
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], 
                        $_SESSION['username'], 
                        $_SESSION['login_string'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT password 
                                      FROM members 
                                      WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();

				$login_check = $password;
 
                if (hash_equals($login_check, $login_string) ){
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Could not prepare statement
            header("Location: http://api-site/error.php?err=Database error: cannot prepare statement");
            exit();
        }
    } else {
        // Not logged in 
        return false;
    }
}

function admin_check($mysqli) {
	if (login_check($mysqli)) {	
		if ($stmt = $mysqli->prepare("SELECT admin 
									  FROM members 
									  WHERE id = ? LIMIT 1")) {
			// Bind "$user_id" to parameter. 
			$user_id = $_SESSION['user_id'];
			$stmt->bind_param('i', $user_id);
			$stmt->execute();   // Execute the prepared query.
			$stmt->store_result();

			if ($stmt->num_rows == 1) {
				// If the user exists get variables from result.
				$stmt->bind_result($admin);
				$stmt->fetch();
				
				if ($admin==1)
					return true;
				else
					return false;
			}
		} else {
		// Could not prepare statement
		header("Location: http://api-site/error.php?err=Database error: cannot prepare statement");
		exit();
		}
	}
}
function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
	//Search for $strip chars in $url, replace with '', and $count replacements
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
	//Replace character with HTML equivalents
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function print_csv($filename)
{
	echo "<p>Contents of ".basename($filename).":<br />";
	echo '<div style="height:500px;width:65%;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">';

	$row = 1;
	if (($handle = fopen("uploads/".$filename, "r")) !== FALSE)
	{
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
		{
			$num = count($data);
			echo "$row: ";
			$row++;
			for ($c=0; $c < $num; $c++)
			{
				echo $data[$c] . " \n";
			}
			echo "<br />";
		}
		fclose($handle);
	}
	
	echo "</div>";
}

// Input processing
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

function quick_query($query)
{
	$conn = db_connect("quick_query connection error");
	$result = $conn->query($query);
	$conn->close();
	
	return $result;
}
?>