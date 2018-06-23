<?php

include_once 'psl-config.php';   // As functions.php is not included
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($mysqli->connect_error)
{
    header("Location: http://api-site/error.php?err=Unable to connect to MySQL");
    exit();
}

$mysqli->set_charset("utf8");

?>