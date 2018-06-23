<?php
include("init.html");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>

<?php if (login_check($mysqli)) : ?>
	<h2 align=center>Upload results</h2>
	<br /><br />

	<?php

	$pollutant_id = $_POST["pollutant_id"];
	$station_id = $_POST["station_id"];

	$accepted_types = array("csv","dat");
	$filename = $_FILES["fileToUpload"]["name"];
	$filetype = pathinfo($filename,PATHINFO_EXTENSION);

	$success = 0;
	if ($filetype == "dat" || $filetype == "csv")
	{
		$exists = (quick_query("SELECT * FROM file WHERE filename='$filename'")->num_rows > 0);
		if ($exists)
		{
			echo "The data of file <strong>".basename($filename)."</strong> already exists in the database.<br />";
		}
		else
		{
			$test = quick_query("INSERT INTO file VALUES (null,'$filename',$pollutant_id,$station_id);");
			$file_id = quick_query("SELECT id FROM file WHERE filename='$filename' LIMIT 1;")->fetch_assoc();
			$file_id = $file_id['id'];

			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "uploads/".$filename))
			{
				//echo "File <strong>".basename($filename)."</strong> has been successfully uploaded.<br />";
				
				$row = 1;
				$query = "INSERT INTO measurement VALUES ";
				
				if (($handle = fopen("uploads/".$filename, "r")) !== FALSE)
				{
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
					{
						$date = $data[0];
						$date = substr("$date",6,6).substr("$date",3,-5).substr("$date",0,2);
						for ($hour=1;$hour<=24;$hour++)
						{
							if ($data[$hour] != -9999)
							{
								$query .= "($pollutant_id,'$date',$hour,$data[$hour],$station_id,$file_id),";
							}
						}
					
					}
					fclose($handle);
					
					$query = substr("$query",0,-1);
					$query .= ";";
				}

				$result = quick_query($query);
				
				if ($result) echo "<strong>Successful submission to the database.</strong>";
				else echo "<strong>There was a problem with the submission to the database.</strong>";
			}
			else echo "<strong>Uploading failed for an unknown reason.</strong><br />";
		}
	}
	else
	{
		echo "<strong>Uploading has been rejected because the file was not in .csv or .dat format.</strong><br />";
	}

	echo "<br />Returning to admin page in 3 seconds...";
	header("Refresh: 3;url=admin_page.php");
	die();

	?>
<?php else : ?>
	<p>
		<span class="error">You can no access rights to this page.</span> Please <a href="index.php">sign in</a>.
	</p>
<?php endif; ?>
</body>
</html>