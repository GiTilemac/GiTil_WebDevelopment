<?php
$error = filter_input(INPUT_GET, 'err', $filter = FILTER_SANITIZE_STRING);
 
if (! $error) {
    $error = 'Oops! There was an unidentified error.';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Error</title>
		<style>
			p.solid {border-style: solid;}
			p.ridge {border-style: ridge;}
			body {
				background-image: url("index_bg.jpg");
			}
		</style>
    </head>
    <body>
        <h1>There has been a problem</h1>
        <p class="error"><?php echo $error; ?></p>  
		<p>Return to <a href="http://api-site/index.php">homepage</a>.</p>
    </body>
</html>