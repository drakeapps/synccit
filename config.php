<?php



// SETUP
// Database info
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "rddtsync";



// Base URL
// if on root, make it blank
// otherwise /foldername with no trailing slash
$baseurl = "/rsync";


// API location
$apiloc = "http://localhost/rsync/api/";


// Pretty URLs. Need server configured properly
$prettyurls = false;




$mysql = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if($mysql->connect_errno) {
	echo "database connection failure <!-- ".$mysql->connect_error." -->";
	die;
}

