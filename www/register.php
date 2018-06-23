<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>User registration</title>
    </head>
	<style>
		p.solid {border-style: solid;}
		p.ridge {border-style: ridge;}
		body {
			background-image: url("index_bg.jpg");
		}
	</style>
	
    <body>
        <!-- Registration form to be output if the POST variables are not
        set or if the registration script caused an error. -->
        <h1>Account creation</h1>
		
		<?php
		// INITIALIZE VARIABLES AND ERROR MESSAGES
		$nameErr = $emailErr = $pErr= "";
		$username = $email = $password = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty($_POST["username"])) {
			$nameErr = "<font color=red>Username is required</font>";
			} else {
				$username = test_input($_POST["username"]);
				if (!preg_match("/^[a-zA-Z ]*$/",$username)) {
					$nameErr = "<font color=red>Only english characters and spaces are allowed</font>";
				}
			}

			if (empty($_POST["password"])) {
			$pErr = "<font color=red>Password is required</font>";
			} else {
				$password = test_input($_POST["password"]);
			}

			if (empty($_POST["email"])) {
			$emailErr = "<font color=red>E-mail is required</font>";
			} else {
				$email = test_input($_POST["email"]);
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$emailErr = "<font color=red>Wrong E-mail form</font>";
				}
			}
		}

		if (!empty($error_msg)) {
			echo $error_msg;
		}
		?>
		<ul>
			<li>The username can contain only upper- and lowercase English characters, numbers and underscores</li>
			<li>The E-mail address must be in the valid form</li>
			<li>The password must be at least 6 characters in length</li>
			<li>The password must contain
				<ul>
					<li>At least one uppercase English letter (A..Z)</li>
					<li>At least one lowercase English letter (a..z)</li>
					<li>At least one number (0..9)</li>
				</ul>
			</li>
		</ul>
		<p><span class="error">* Required field.</span></p>
		</font>
		
		<!--$_SERVER["PHP_SELF"] = filename of currently executing script, sends data to current page itself-->
		<form   action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" 
				method="post" 
				name="registration_form">
			Username: <input type='text' 
				name='username' 
				id='username'
				value="<?php echo $username; ?>"/>
				<span class="error">* <?php echo $nameErr;?></span>
				<br><br>
			E-mail: <input type="text"
						  name="email"
						  id="email"
						  value = "<?php echo $email; ?>"/>
				<span class="error">* <?php echo $emailErr;?></span>
				<br><br>
			Password: <input type="password"
							 name="p" 
							 id="p"/>
					<span class="error">* <?php echo $pErr;?></span>
					<br><br>
		
				<input type="submit" 
					   name="submit" 
					   value="Register" /> 
		</form>
		<p>Return to <a href="index.php">homepage</a>.</p>
	</body>
</html>