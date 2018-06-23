<?php
include("init.html");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>

<?php if (admin_check($mysqli)) : ?>
		
	<p>Welcome <?php echo htmlentities($_SESSION['username']); ?>!</p>

	<a href="index.php">Homepage</a> &gt
	<strong>Admin Page</strong> &gt 
	<br /><br />
	
	<div class="section">
		<h2 align="center">API Statistics</h2>

		<div id="stats1"></div><br />
		<div id="stats2"></div>
					
		<script>
			var apikey = '<?php echo $_SESSION['apikey']; ?>';
			setInterval(function() {json_sumquery("stats1",apikey);},1000);
			setInterval(function() {json_top10query("stats2",apikey);},1000);
		</script>
	</div>
	<br /><br />
	
	<div class="section">
		<h2 align=center>File Upload</h2>

		<form action="upload.php" method="post" enctype="multipart/form-data">
			Choose pollutant:
			<select name="pollutant_id">
				
				<option value="" selected="selected"></option>
				<?php
					$result = quick_query("SELECT id,name FROM pollutant");
					
					if ($result->num_rows > 0)
					{
						while ($row = $result->fetch_assoc())
						{
							echo "<option value=".$row['id'].">".$row['name']."</option>\n";
						}
					}
				?>
			</select>
			<br /><br />
			
			Choose station:
			<select name="station_id">
				
				<option value="" selected="selected"></option>
				<?php
					$result = quick_query("SELECT id,name FROM station");
					
					if ($result->num_rows > 0)
					{
						while ($row = $result->fetch_assoc())
						{
							echo "<option value=".$row['id'].">".$row['name']."</option>";
						}
					}
				?>
			</select>
			<br /><br />
			
			Choose file name to upload:<br /><br />
			<input type="file" name="fileToUpload" id="fileToUpload" />
			<input type="submit" value="Upload" name="submit" />
		</form>
		<br /><br />
		
		<strong>Stored files:</strong><br />

		<?php
		$result = quick_query("SELECT * FROM file");

		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				echo $row['filename']." ";
			}

			echo '
				<br /><br />
				<form action="del_file.php" method="POST" autocomplete="off">
				Choose a file for deletion:
				<select name="filename">';
				$result = quick_query("SELECT * FROM file");
					echo '<option value="" selected="selected"></option>';
					while ($row = $result->fetch_assoc())
					{
						echo "<option value=".$row['filename'].">".$row['filename']."</option>";
					}
			echo '
				</select>
				<input type="submit" value="Delete">
				</form>';
		}
		else echo "None";
		?>
	</div>
	<br /><br />

	<div class="section">
		<br /><br />
		<h2 align=center>Measuring stations</h2>
		<strong>New station details:</strong>:<br /><br />
		<form action="new_station.php" autocomplete="off" method="POST">
			ID:<br />
			<input type="text" name="code" value=""><br /><br />
			Name:<br />
			<input type="text" name="name" value=""><br /><br />
			Latitude:<br />
			<input type="text" name="lat" value=""><br /><br />
			Longtitude:<br />
			<input type="text" name="lon" value=""><br /><br />
			<input type="submit" value="Submit">
		</form>
		<br /><br />
	
		<strong>Stored stations:</strong><br />
		<?php

		$result = quick_query("SELECT * FROM station");

		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				echo "<strong>ID</strong>: ".$row["code"].", <strong>name</strong>: ".$row["name"].", <strong>latitude</strong>: ".$row["lat"].", <strong>longtitude</strong>: ".$row["lon"];
				echo '<br />';
			}
			
			echo '
				<br /><br />
				<form action="del_station.php" method="POST" autocomplete="off">
				Choose a station for deletion:
				<select name="code">';
				$result = quick_query("SELECT * FROM station");
					echo '<option value="" selected="selected"></option>';
					while ($row = $result->fetch_assoc())
					{
						echo "<option value=".$row['code'].">".$row['name']." (".$row['code'].")"."</option>";
					}
			echo '
				</select>
				<input type="submit" value="Delete">
				</form>';
		}
		else echo "None";
		?>
	</div>
	<br /><br />

<?php else : ?>
<p>
	<span class="error">You do not have access rights to this page.</span> Please <a href="index.php">sign in.</a>
</p>
<?php endif; ?>

</body>
</html>