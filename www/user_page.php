<?php
include ("init.html");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>

<div class="test1">
	<?php if (login_check($mysqli)): ?>
		<p>Welcome <?php echo htmlentities($_SESSION['username']); ?>!</p>
		<p>Return to <a href="index.php">homepage</a></p>
		
		<p><strong>Your API key:</strong> <u><?php echo $_SESSION['apikey']; ?></u></p>
		
		<div id="api_form"></div>
		<script>
			var apikey = '<?php echo $_SESSION['apikey']; ?>';
			print_form("api_form",apikey);
		</script>
			
	<?php else : ?>
		<p>
			<span class="error">You do not have access rights to this page.</span> Please <a href="index.php">sign in.</a>
		</p>
	<?php endif; ?>
</div>

</body>
</html>