<?php



// SETUP
// Database info
$dbhost = "localhost";
$dbuser = "redditsync";
$dbpass = "GYn5UZGGqGWU9PzA";
$dbname = "redditsync";


$mysql = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if($mysql->connect_errno) {
	echo "database connection failure <!-- ".$mysql->connect_error." -->";
	die;
}

